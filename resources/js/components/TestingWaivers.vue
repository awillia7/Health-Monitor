<template>
  <div class="container">
    <div class="row justify-content-center text-center">
      <div class="w-100">
        <div class="card mb-2">
          <h3 class="card-header">Waived Users</h3>
          <div class="card-body">
            <div class="row justify-content-center align-items-center mb-2">
              <span class="col-3 font-weight-bold">Name</span>
              <span class="col-3 font-weight-bold">Waiver Start Date</span>
              <span class="col-3 font-weight-bold">Waiver End Date</span
              ><span class="col-3"></span>
            </div>
            <div class="row justify-content-center align-items-center mb-2">
              <span
                class="col-12 text-uppercase font-weight-light"
                v-if="!usersData.length && !newUsersData.length"
                >No Users Waived from Testing</span
              >
            </div>
            <div
              class="row justify-content-center align-items-center pb-2"
              v-for="(user, index) in usersData"
              :key="user.id"
            >
              <span class="col-3">{{ user.name }}</span>
              <span class="col-3"
                ><input
                  class="form-control"
                  type="date"
                  @blur="updateEndDate(index, 'usersData')"
                  @change="user.changed = true"
                  v-model="user.test_waiver_start_date"
              /></span>
              <span class="col-3"
                ><input
                  class="form-control"
                  type="date"
                  @change="user.changed = true"
                  v-model="user.test_waiver_end_date"
              /></span>
              <span class="col-3"
                ><button
                  :disabled="
                    !user.changed ||
                    (user.test_waiver_start_date &&
                      !user.test_waiver_end_date) ||
                    (!user.test_waiver_start_date && user.test_waiver_end_date)
                  "
                  @click="update(`/users/${user.id}/test-waiver`, user)"
                  class="btn btn-primary"
                >
                  Update
                </button></span
              >
            </div>
            <hr v-if="newUsersData.length" />
            <div
              class="row justify-content-center align-items-center pb-2"
              v-for="(newUser, index) in newUsersData"
              :key="newUser.id"
            >
              <span class="col-3">{{ newUser.name }}</span>
              <span class="col-3"
                ><input
                  class="form-control"
                  type="date"
                  @change="newUser.changed = true"
                  @blur="updateEndDate(index, 'newUsersData')"
                  v-model="newUser.test_waiver_start_date"
              /></span>
              <span class="col-3">
                <input
                  class="form-control"
                  type="date"
                  @change="newUser.changed = true"
                  v-model="newUser.test_waiver_end_date"
              /></span>
              <span class="col-3"
                ><button
                  :disabled="
                    !newUser.changed ||
                    (newUser.test_waiver_start_date &&
                      !newUser.test_waiver_end_date) ||
                    (!newUser.test_waiver_start_date &&
                      newUser.test_waiver_end_date)
                  "
                  class="btn btn-primary"
                  @click="update(`/users/${newUser.id}/test-waiver`, newUser)"
                >
                  Update
                </button></span
              >
            </div>
            <hr />
            <div
              class="row justify-content-center align-items-center mb-2 mt-4"
            >
              <span class="col-6">
                <input
                  :disabled="disableUserSearch"
                  class="form-control"
                  type="text"
                  v-model="userSearch"
                />
              </span>
              <span class="col-2">
                <button
                  :disabled="disableUserSearch"
                  @click="search()"
                  class="btn btn-primary"
                >
                  Add User
                </button>
              </span>
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
  props: ["users"],

  data() {
    return {
      usersData: [],
      newUsersData: [],
      userSearch: null,
      disableUserSearch: false,
    };
  },

  created() {
    for (const user in this.users) {
      let id = this.users[user].id;
      let name = this.users[user].name;
      let test_waiver_start_date = this.users[user].test_waiver_start_date;
      let test_waiver_end_date = this.users[user].test_waiver_end_date;

      this.usersData.push({
        id,
        name,
        test_waiver_start_date,
        test_waiver_end_date,
        changed: false,
      });
    }
  },

  methods: {
    updateEndDate(index, variable) {
      if (variable === "newUsersData") {
        this.newUsersData[index].test_waiver_end_date = this.newUsersData[index]
          .test_waiver_start_date
          ? moment(
              this.newUsersData[index].test_waiver_start_date,
              "yyyy-MM-DD"
            )
              .add(90, "days")
              .format("yyyy-MM-DD")
          : null;
      } else if (variable === "usersData") {
        this.usersData[index].test_waiver_end_date = this.usersData[index]
          .test_waiver_start_date
          ? moment(this.usersData[index].test_waiver_start_date, "yyyy-MM-DD")
              .add(90, "days")
              .format("yyyy-MM-DD")
          : null;
      }
    },

    update(endpoint, payload) {
      axios
        .put(endpoint, payload)
        .then(({ data }) => {
          const userIndex = this.usersData.findIndex(
            (user) => user.id == data.user.id
          );
          const newUserIndex = this.newUsersData.findIndex(
            (user) => user.id == data.user.id
          );

          if (userIndex !== -1) {
            this.usersData[userIndex].changed = false;
          }
          if (newUserIndex !== -1) {
            this.newUsersData[newUserIndex].changed = false;
          }

          this.$toast.success(data.message, "Success", { timeout: 3000 });
        })
        .catch(({ response }) => {
          this.$toast.error(response.data.message, "Error", { timeout: 3000 });
        });
    },

    search() {
      this.disableUserSearch = true;
      axios
        .post("/users/test-waivers/search", { userSearch: this.userSearch })
        .then(({ data, status }) => {
          if (status === 200) {
            const oldUser = this.usersData.find((user) => user.id === data.id);
            const newUser = this.newUsersData.find(
              (user) => user.id === data.id
            );
            if (!oldUser && !newUser) {
              let id = data.id;
              let name = data.name;
              let test_waiver_start_date = data.test_waiver_start_date;
              let test_waiver_end_date = data.test_waiver_end_date;

              this.newUsersData.push({
                id,
                name,
                test_waiver_start_date,
                test_waiver_end_date,
                changed: false,
              });
              this.$toast.success("New user added", "Success", {
                timeout: 3000,
              });
            } else {
              this.$toast.success("User already added", "Warning", {
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
          this.userSearch = null;
          this.disableUserSearch = false;
        });
    },
  },
};
</script>