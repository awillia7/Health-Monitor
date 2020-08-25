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
            <div class="row justify-content-center align-items-center mb-2">
              <span
                class="col-12 text-uppercase font-weight-light"
                v-if="!screeningsData.length"
              >No Uncleared Screenings Found</span>
            </div>
            <div
              class="row justify-content-center align-items-center mb-2"
              v-for="(screening) in screeningsData"
              :key="screening.id"
            >
              <span class="col-4">
                <a :href="`/screenings/${screening.id}`">{{ screening.user.name }}</a>
              </span>
              <span class="col-2">{{ screening.score }}</span>
              <span class="col-2">{{ screening.fail_score }}</span>
              <span class="col-2">{{ overrideText(screening.id, screening.override_user_id) }}</span>
              <span class="col-2">
                <button
                  v-if="override_role"
                  @click="overrideScreening(screening)"
                  :disabled="screening.override_user_id"
                  class="btn btn-outline-primary mr-2 mb-2"
                >Override</button>
                <button
                  v-if="delete_role"
                  @click="destroy(screening)"
                  class="btn btn-outline-danger mr-2 mb-2"
                >Delete</button>
              </span>
            </div>
            <hr />
            <div
              class="row justify-content-center align-items-center mb-2"
              v-for="newScreening in newScreeningsData"
              :key="newScreening.user.id"
            >
              <span class="col-4">
                <a
                  v-if="newScreening.id"
                  :href="`/screenings/${newScreening.id}`"
                >{{ newScreening.user.name }}</a>
                <span v-else>{{ newScreening.user.name }}</span>
              </span>

              <span v-if="newScreening.id" class="col-2">{{ newScreening.score }}</span>
              <span
                v-else
                class="col-8 text-uppercase font-weight-light"
              >No Screening Found</span>
              <span v-if="newScreening.id" class="col-2">{{ newScreening.fail_score }}</span>
              <span
                v-if="newScreening.id"
                class="col-2"
              >{{ overrideText(newScreening.id, newScreening.override_user_id) }}</span>
              <span v-if="newScreening.id" class="col-2">
                <button
                  v-if="delete_role && newScreening.id"
                  @click="destroy(newScreening)"
                  class="btn btn-outline-danger"
                >Delete</button>
              </span>
            </div>
            <div class="row justify-content-center align-items-center pt-2 mb-2">
              <span class="col-6">
                <input class="form-control" type="text" v-model="screeningSearch" />
              </span>
              <span class="col-2">
                <button @click="search()" class="btn btn-primary">Search User</button>
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
  props: ["screenings", "override_role", "delete_role"],

  data() {
    return {
      screeningsData: [],
      newScreeningsData: [],
      screeningSearch: null,
    };
  },

  created() {
    for (const screening in this.screenings) {
      let id = this.screenings[screening].id;
      let user_name = this.screenings[screening].user.name;
      let user_id = this.screenings[screening].user.id;
      let score = this.screenings[screening].score;
      let fail_score = this.screenings[screening].fail_score;
      let override_user_id = this.screenings[screening].override_user_id;
      let override_at = this.screenings[screening].override_at;

      this.screeningsData.push({
        id,
        score,
        fail_score,
        override_user_id,
        override_at,
        user: {
          id: user_id,
          name: user_name,
        },
      });
    }
  },

  methods: {
    overrideText(id, val) {
      if (!id) return null;
      return val ? "Yes" : "No";
    },

    disable(val) {
      return val ? true : false;
    },

    overrideScreening(screening) {
      axios
        .patch(`/screenings/${screening.id}/override`)
        .then(({ data }) => {
          let index = this.screeningsData.findIndex(
            (el) => el.id === data.screening.id
          );
          this.screeningsData[index].override_user_id =
            data.screening.override_user_id;
          this.screeningsData[index].override_at = data.screening.override_at;

          this.$toast.success(data.message, "Success", { timeout: 3000 });
        })
        .catch(({ response }) => {
          this.$toast.error(response.data.message, "Error", { timeout: 3000 });
        });
    },

    destroy(screening) {
      this.$toast.question(
        "Are you sure you want to delete the screening?",
        "Confirm",
        {
          timeout: 20000,
          close: false,
          overlay: true,
          toastOnce: true,
          id: "question",
          zindex: 999,
          position: "center",
          buttons: [
            [
              "<button><b>YES</b></button>",
              (instance, toast) => {
                this.delete(screening);

                instance.hide({ transitionOut: "fadeOut" }, toast, "button");
              },
              true,
            ],
            [
              "<button>NO</button>",
              (instance, toast) => {
                instance.hide({ transitionOut: "fadeOut" }, toast, "button");
              },
            ],
          ],
        }
      );
    },

    delete(screening) {
      axios.delete(`/screenings/${screening.id}`).then(({ data }) => {
        this.screeningsData = this.screeningsData.filter(
          (screening) => screening.id !== data.id
        );
        this.newScreeningsData = this.newScreeningsData.filter(
          (screening) => screening.id !== data.id
        );
        this.$toast.success(data.message, "Success", { timeout: 2000 });
      });
    },

    search() {
      axios
        .post("/screenings/search", { screeningSearch: this.screeningSearch })
        .then(({ data, status }) => {
          if (status === 200) {
            const oldScreening = this.screeningsData.find(
              (screening) => screening.user.id === data.user.id
            );
            const newScreening = this.newScreeningsData.find(
              (screening) => screening.user.id === data.user.id
            );

            if (!oldScreening && !newScreening) {
              this.newScreeningsData.push(data);
              this.$toast.success("Screening added", "Success", {
                timeout: 3000,
              });
            } else {
              this.$toast.success("Screening already added", "Warning", {
                timeout: 3000,
              });
            }
          } else if (status === 204) {
            this.$toast.success("User not found", "Warning", {
              timeout: 3000,
            });
          }
        })
        .catch(({ response }) => {
          this.$toast.error(response.data.message, "Error", { timeout: 3000 });
        })
        .then(() => {
          this.screeningSearch = null;
        });
    },
  },
};
</script>