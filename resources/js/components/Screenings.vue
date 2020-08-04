<template>
  <div class="container">
    <div class="row justify-content-center text-center">
      <div class="w-100">
        <div class="card mb-2">
          <h3 class="card-header">Today's Uncleared Screenings</h3>
          <div class="card-body">
            <div class="row justify-content-center align-items-center mb-2">
              <span class="col-4 font-weight-bold">Name</span>
              <span class="col-2 font-weight-bold">Score</span>
              <span class="col-2 font-weight-bold">Fail Score</span>
              <span class="col-2 font-weight-bold">Override</span>
              <span class="col-2"></span>
            </div>
            <div
              class="row justify-content-center align-items-center mb-2"
              v-for="(screening) in screeningsData"
              :key="screening.id"
            >
              <span class="col-4">
                <a :href="`/screenings/${screening.id}`">{{ screening.user_name }}</a>
              </span>
              <span class="col-2">{{ screening.score }}</span>
              <span class="col-2">{{ screening.fail_score }}</span>
              <span class="col-2">{{ overrideText(screening.override_user_id) }}</span>
              <span class="col-2">
                <button
                  v-if="override"
                  @click="overrideScreening(screening)"
                  :disabled="screening.override_user_id"
                  class="btn btn-primary"
                >Override</button>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["screenings", "override"],

  data() {
    return {
      screeningsData: [],
    };
  },

  methods: {
    overrideText(val) {
      return val ? "Yes" : "No";
    },

    disable(val) {
      return val ? true : false;
    },

    overrideScreening(screening) {
      axios
        .patch(`/screenings/${screening.id}/override`)
        .then(({ data }) => {
          let index = this.screeningsData.findIndex(el => el.id === data.screening.id);
          this.screeningsData[index].override_user_id = data.screening.override_user_id;
          this.screeningsData[index].override_at = data.screening.override_at;

          this.$toast.success(data.message, "Success", { timeout: 3000 });
        })
        .catch(({ response }) => {
          this.$toast.error(response.data.message, "Error", { timeout: 3000 });
        });
    },
  },

  created() {
    for (const screening in this.screenings) {
      let id = this.screenings[screening].id;
      let user_name = this.screenings[screening].user.name;
      let score = this.screenings[screening].score;
      let fail_score = this.screenings[screening].fail_score;
      let override_user_id = this.screenings[screening].override_user_id;
      let override_at = this.screenings[screening].override_at;

      this.screeningsData.push({
        id,
        user_name,
        score,
        fail_score,
        override_user_id,
        override_at,
      });
    }
  },
};
</script>