<template>
  <div class="container">
    <div class="row justify-content-center">
      <div
        v-if="test.unreadable"
        class="alert alert-warning w-100 justify-content-center text-center"
      >
        <span
          class="col-12 d-flex flex-row justify-content-center align-items-center"
        >
          Unfortunately, the latest sample you submitted was unreadable,
          possibly due to one of these factors
        </span>
        <span
          class="col-12 d-flex flex-row text-left justify-content-center align-items-center"
        >
          <ul class="list mb-0">
            <li class="">
              Eating or drinking within 30 minutes of submitting the sample
            </li>
            <li class="">
              Brushing teeth within 30 minutes of submitting the sample
            </li>
            <li class="">Sample size was too small</li>
          </ul>
        </span>
        <span
          class="col-12 d-flex flex-row justify-content-center align-items-center"
        >
          To receive credit for testing this week, please deposit another sample
          in any collection box around campus by 4 pm on Thursday
        </span>
      </div>
      <div v-if="test.id" class="w-100 justify-content-center text-center">
        <div class="card mb-2">
          <div class="card-body">
            <div class="row mb-2">
              <span
                class="col-12 d-flex flex-row justify-content-center align-items-center"
                ><span class="font-weight-bold text-large"
                  >Test Label ID: &nbsp;</span
                ><span v-if="displayUserId"
                  >{{ test.user_id }} &nbsp;
                  <button
                    @click="displayUserId = !displayUserId"
                    class="btn btn-secondary btn-sm"
                  >
                    Hide
                  </button></span
                ><span v-else
                  ><button
                    @click="displayUserId = !displayUserId"
                    class="btn btn-primary btn-sm"
                  >
                    Show
                  </button></span
                ></span
              >
            </div>
            <div class="row mb-2">
              <span
                class="col-12 d-flex flex-row justify-content-center align-items-center"
                ><span class="font-weight-bold text-large"
                  >Latest Test Date: &nbsp;</span
                ><span>{{ formatted_test_date }}</span></span
              >
            </div>
            <div class="row mb-2">
              <span
                class="col-12 d-flex flex-row justify-content-center align-items-center"
                ><span class="font-weight-bold text-large"
                  >Next Step: &nbsp;</span
                ><span v-if="displayResult"
                  >{{ getStep }} &nbsp;<button
                    @click="displayResult = !displayResult"
                    class="btn btn-secondary btn-sm"
                  >
                    Hide
                  </button></span
                ><span v-else
                  ><button
                    @click="displayResult = !displayResult"
                    class="btn btn-primary btn-sm"
                  >
                    Show
                  </button></span
                ></span
              >
            </div>
            <div v-if="displayResult" class="row mb-2">
              <span
                class="col-12 d-flex flex-row justify-content-center align-items-center"
              >
                {{ getStepDetail }}
              </span>
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
            <div class="form-group col-md-2" v-if="!printStatus">
              <label>&nbsp;</label>
              <div class="text-center">
                <button class="btn btn-primary" @click="print">
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
      displayResult: false,
      displayUserId: false,
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

    getStep() {
      // return this.test.result === "POSITIVE"
      //   ? "Contact Student Health Services"
      //   : "No further action needed";
        return this.test.result === "POSITIVE"
        ? "The test date listed above indicates that you are flagged as having high likelihood of being infected with COVID"
        : "No further action needed";
    },

    getStepDetail() {
      let message = "";
      switch (this.test.result) {
        case "POSITIVE":
          // message =
          //   "If you have not already heard from an SHS staff member, please text your name to 740-507-0257.";
          message =
             "If this is the result of an exit test administered between November 19-23, please eliminate interactions with others immediately and make arrangements with friends or family to isolate off campus to prevent the spread of the disease.  Although the Yale Saliva Test is highly accurate, we recommend that you submit a PCR test to confirm the test results.  The best way to do so is contact your county’s health department to determine what testing options you have in your area.";
          break;
        case "NEGATIVE":
          message = "The sample you submitted does not require follow up.";
          break;
        case "WAIVER":
          message = `You have been waived from testing until ${moment(
            this.user.test_waiver_end_date
          ).format("MMMM D, YYYY")}.`;
          break;
      }

      return message;
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