<template>
  <div class="container">
    <div class="row justify-content-center text-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3>Today's Screening</h3>
          </div>
          <div class="card-body">
            <div class="row justify-content-center">
              <h3 class="row">
                Status:&nbsp;
                <span v-if="locked" class="text-danger">NOT CLEARED</span>
                <span v-else class="text-success">CLEARED</span>
              </h3>
            </div>

            <div
              class="row text-danger justify-content-center"
              v-if="locked"
            >You exhibit health symptoms that indicate potential community risk. Please do not go to class, chapel, and/or work, and report to the Student Health Services office for testing.</div>
            <div
              class="row text-success justify-content-center"
              v-else
            >Your ID card has been activated for {{ created_date }}</div>

            <hr />

            <div
              class="row justify-content-center"
              v-for="answer in screening.answers"
              :key="answer.id"
            >
              <h4 v-if="answer.value">
                <span class="col-8">{{ answer.question.text }}</span>
                <span class="col-4 text-danger">Yes</span>
              </h4>
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

    created_date() {
      return moment(this.screening.created_at).format("MMMM D, YYYY");
    }
  },

  components: { VueQrious }
};
</script>