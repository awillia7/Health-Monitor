<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <div class="row justify-content-center text-center">
              <h4>{{ screening.user.name }} - {{ created_date }}</h4>
            </div>
          </div>

          <div class="card-body">
            <div class="row justify-content-center">
              <h3>
                <span v-if="locked" class="text-danger">Locked</span>
                <span v-else class="text-success">Open</span>
              </h3>
            </div>

            <hr />

            <div
              class="row align-items-center justify-content-center align-middle mb-2"
              v-for="answer in screening.answers"
              :key="answer.id"
            >
              <span class="col-8">{{ answer.question.text }}</span>
              <span v-if="answer.value" class="col-4 text-center text-danger">Yes</span>
              <span v-else class="col-4 text-center text-success">No</span>
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
  props: ["screening"],

  computed: {
    locked() {
      return this.screening.score >= this.screening.fail_score;
    },

    created_date() {
      return moment(this.screening.created_at).format("MMMM D, YYYY");
    }
  }
};
</script>