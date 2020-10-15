<template>
  <div class="container">
    <div class="row justify-content-center">
      <div v-if="test.id" class="w-100 justify-content-center text-center">
        <div class="card mb-2">
          <div class="card-body">
            <div class="row mb-2">
              <span
                class="col-12 d-flex flex-row justify-content-center align-items-center"
                ><span class="font-weight-bold text-large"
                  >Latest Test Date: &nbsp;</span
                ><span>{{ formatted_test_date }}</span></span
              >
            </div>
          </div>
        </div>
      </div>
      <div class="card mb-2">
        <h3 class="card-header text-center">
          COVID-19 Testing Opt-In Agreement
        </h3>
        <div class="card-body">
          <div class="row mb-2 ml-2 mr-2">
            <p>
              The SARS CoV-2 Surveillance Test is conducted by depositing saliva
              in a test tube wherein sampling is performed. The only test that
              will be performed on the samples will be for the SARS CoV-2. All
              samples will be destroyed immediately after testing is complete.
              Test results will only be released to MVNU health employees.
            </p>
            <p>
              I understand this is not a diagnosis but an indication. If further
              diagnosis is necessary, I will be advised and directed to Student
              Health Services. My indication below is acceptance to this
              program.
            </p>
          </div>
          <hr v-if="!optinStatus" />
          <div
            v-if="!optinStatus"
            class="form-check form-check-inline col-md-12 mb-2"
          >
            <div class="col-md-10">
              <input
                type="checkbox"
                :disabled="disableOptinCheck"
                v-model="optinCheck"
                id="newOptin"
                class="form-check-input"
              />
              <label for="newOptin" class="form-check-label">I Agree</label>
            </div>
            <div class="col-md-2 d-flex align-items-center">
              <div class="text-center">
                <button
                  :disabled="disableOptinButton"
                  @click="sign()"
                  class="btn btn-primary"
                >
                  Opt-In
                </button>
              </div>
            </div>
          </div>
          <hr v-if="optinStatus" />
          <div class="row" v-if="optinStatus">
            <div class="form-group col-md-5">
              <label>Opt-In Date</label>
              <div>
                <input
                  class="form-control align-self-center"
                  type="text"
                  disabled
                  v-model="userData.test_optin_date"
                />
              </div>
            </div>
            <div class="form-group col-md-5">
              <label>Label Request Date</label>
              <div>
                <input
                  class="form-control align-self-center"
                  type="text"
                  disabled
                  v-model="userData.test_print_date"
                />
              </div>
            </div>
            <div class="form-group col-md-2">
              <label>&nbsp;</label>
              <div class="text-center">
                <button
                  class="btn btn-primary"
                  :disabled="printStatus"
                  @click="print"
                >
                  Request Labels
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import moment from "moment";

export default {
  props: ["user", "test"],

  data() {
    return {
      userData: null,
      optinCheck: false,
      disableOptinCheck: false,
    };
  },

  created() {
    let id = this.user.id;
    let test_optin_date = this.user.formatted_test_optin_date;
    let test_print_date = this.user.formatted_test_print_date;

    this.userData = {
      id,
      test_optin_date,
      test_print_date,
    };

    if (test_optin_date) {
      this.disableOptinCheck = true;
      this.optinCheck = true;
    }
  },

  computed: {
    disableOptinButton() {
      return this.userData.test_optin_date ? true : !this.optinCheck;
    },

    optinStatus() {
      return this.userData.test_optin_date ? true : false;
    },

    printStatus() {
      if (this.optinStatus) {
        const print_date = new Date(this.userData.test_print_date);
        const week_ago = new Date(
          new Date(new Date().setDate(new Date().getDate() - 7)).toDateString()
        );

        return print_date >= week_ago;
      }

      return false;
    },

    resultClass() {
      return this.test.result === "NEGATIVE" ? "text-success" : "text-danger";
    },

    formatted_test_date() {
      return moment(this.test.test_date).format("MMMM D, YYYY");
    },
  },

  methods: {
    today() {
      return new Date().toJSON().slice(0, 10);
    },

    sign() {
      const optin_date = this.today();
      axios
        .put("/testing-optin", {
          optin_date,
          print_date: optin_date,
        })
        .then(({ data }) => {
          this.userData.id = data.id;
          this.userData.test_optin_date = data.test_optin_date;
          this.userData.test_print_date = data.test_print_date;
          this.disableOptinCheck = true;

          this.$toast.success(data.message, "Success", { timeout: 3000 });
        })
        .catch(({ response }) => {
          this.$toast.error(response.data.message, "Error", { timeout: 3000 });
        });
    },

    print() {
      const print_date = this.today();

      axios
        .put("/testing-optin", {
          print_date,
        })
        .then(({ data }) => {
          this.userData.test_print_date = data.test_print_date;

          this.$toast.success(data.message, "Success", { timeout: 3000 });
        })
        .catch(({ response }) => {
          this.$toast.error(response.data.message, "Error", { timeout: 3000 });
        });
    },
  },
};
</script>

<style scoped>
.text-large {
  font-size: 125%;
}
</style>