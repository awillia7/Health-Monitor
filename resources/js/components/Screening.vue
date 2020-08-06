<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12">
        <div class="card">
          <div class="card-header bg-dark">
            <div class="row justify-content-center text-center text-white">
              <h4>{{ screeningData.user.name }} - {{ created_date }}</h4>
            </div>
            <div v-if="addOverrideButton" class="row justify-content-center text-center">
              <button
                @click="overrideScreening(screeningData)"
                class="btn btn-outline-primary text-right"
              >Override</button>
            </div>

            <div class="row justify-content-center">
              <h3>
                <span v-if="overrideFlag" class="text-warning">OVERRIDE</span>
                <span v-else-if="locked" class="text-danger">NOT CLEARED</span>
                <span v-else class="text-success">CLEARED</span>
              </h3>
            </div>
          </div>

          <div class="card-body">
            <div
              class="row align-items-center justify-content-center align-middle mb-2"
              v-for="answer in screeningData.answers"
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
  props: ["screening", "override"],

  data() {
    return {
      screeningData: null,
    };
  },

  created() {
    this.screeningData = JSON.parse(JSON.stringify(this.screening));
  },

  computed: {
    locked() {
      return this.screeningData.score >= this.screeningData.fail_score;
    },

    overrideFlag() {
      return this.screening.override_user_id !== null;
    },

    addOverrideButton() {
      return this.locked && this.override && !this.overrideFlag;
    },

    created_date() {
      return moment(this.screeningData.created_at).format("MMMM D, YYYY");
    },
  },

  methods: {
    overrideScreening(screening) {
      axios
        .patch(`/screenings/${screening.id}/override`)
        .then(({ data }) => {
          this.screeningData.override_user_id = data.screening.override_user_id;
          this.screeningData.override_at = data.screening.override_at;

          this.$toast.success(data.message, "Success", { timeout: 3000 });
        })
        .catch(({ response }) => {
          this.$toast.error(response.data.message, "Error", { timeout: 3000 });
        });
    },
  },
};
</script>