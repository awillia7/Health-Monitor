<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="card mb-2">
        <div class="card-body">
          <div class="row mb-2 ml-2 mr-2">
            Testing OptIn HIPPA Mumbo Jumbo Lorem ipsum dolor sit amet,
            consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
            labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
            exercitation ullamco laboris nisi ut aliquip ex ea commodo
            consequat. Duis aute irure dolor in reprehenderit in voluptate velit
            esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat
            cupidatat non proident, sunt in culpa qui officia deserunt mollit
            anim id est laborum.
          </div>
          <hr />
          <div class="form-group row mb-2">
            <div class="col-md-10">
              <label>Signature</label>
              <input
                :disabled="optinStatus"
                type="text"
                class="form-control form-control-lg"
                id="hippa_signature"
                v-model="userData.test_optin_signature"
              />
            </div>
            <div class="col-md-2">
              <label>&nbsp;</label>
              <div class="text-center">
                <button
                  :disabled="optinStatus"
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
export default {
  props: ["user"],

  data() {
    return {
      userData: null,
    };
  },

  created() {
    let id = this.user.id;
    let test_optin_date = this.user.formatted_test_optin_date;
    let test_optin_signature = this.user.test_optin_signature;
    let test_print_date = this.user.formatted_test_print_date;

    this.userData = {
      id,
      test_optin_date,
      test_optin_signature,
      test_print_date,
    };
  },

  computed: {
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
  },

  methods: {
    today() {
      return new Date().toJSON().slice(0, 10);
    },

    sign() {
      const optin_date = this.today();
      axios
        .put("/testing-optin", {
          signature: this.userData.test_optin_signature,
          optin_date,
          print_date: optin_date,
        })
        .then(({ data }) => {
          this.userData.id = data.id;
          this.userData.test_optin_date = data.test_optin_date;
          this.userData.test_optin_signature = data.test_optin_signature;
          this.userData.test_print_date = data.test_print_date;

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