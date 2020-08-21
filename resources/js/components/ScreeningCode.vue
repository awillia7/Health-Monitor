<template>
  <div class="container">
    <div class="row justify-content-center text-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header bg-dark">
            <h4 class="row justify-content-center text-center text-white">Today's Screening</h4>
            <div class="row justify-content-center">
              <h3 class="row">
                <span v-if="overrideFlag" class="text-warning">OVERRIDE</span>
                <span v-else-if="locked" class="text-danger">NOT CLEARED</span>
                <span v-else class="text-success">CLEARED</span>
              </h3>
            </div>

            <span
              class="row text-white justify-content-center"
              v-if="overrideFlag"
            >Your ID card has been activated for {{ created_date }}</span>
            <span class="row text-white justify-content-center" v-else-if="locked">
              <div class="d-inline">
                You exhibit health symptoms that indicate potential community risk. Please do not go to class, chapel, and/or work, and report to the Student Health Services office for testing. Please click
                <a
                  :href="`https://mvnu.pharos360.com/referral_create.php?username=${screening.user.erp_id}`"
                  class="d-inline text-danger"
                >here</a> for a short form about your symptoms. Login is required, and then in the middle of the page on the Referral drop-down menu, please select COVID-19 Self-Referral.
              </div>
            </span>
            <span
              class="row text-white justify-content-center"
              v-else
            >Your ID card has been activated for {{ created_date }}</span>
          </div>
          <div class="card-body">
            <div v-for="answer in screening.answers" :key="answer.id">
              <div
                v-if="answer.value"
                class="row align-items-center justify-content-center align-middle mb-2"
              >
                <span class="col-8">{{ answer.question.text }}</span>
                <span class="col-4 text-center text-danger">Yes</span>
              </div>
            </div>

            <vue-qrious class="mt-2" :value="codeUrl" :size="250" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import VueQrious from "vue-qrious";
import moment from "moment";

export default {
  props: ["screening"],

  computed: {
    codeUrl() {
      const url = window.location.href;
      return `${url}screenings/${this.screening.id}`;
    },

    locked() {
      return this.screening.score >= this.screening.fail_score;
    },

    overrideFlag() {
      return this.screening.override_user_id !== null;
    },

    created_date() {
      return moment(this.screening.created_at).format("MMMM D, YYYY");
    },
  },

  components: { VueQrious },
};
</script>