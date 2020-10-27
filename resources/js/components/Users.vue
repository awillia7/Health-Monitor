<template>
  <div class="container">
    <div class="row justify-content-center text-center">
      <div class="w-100">
        <div class="card mb-2">
          <h3 class="card-header">Users</h3>
          <div class="card-body">
            <div
              class="row justify-content-center align-items-center pb-2 border border-top-0 border-right-0 border-left-0"
              v-for="user in usersData"
              :key="user.id"
            >
              <span class="col-2">{{ user.name }}</span>
              <span class="col-2">{{ user.username }}</span>
              <span class="col-6">
                <legend>Screenings</legend>
                <div class="form-check form-check-inline">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    v-model="user.screenings_view"
                  />
                  <label class="form-check-label">View</label>
                </div>
                <div class="form-check form-check-inline">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    v-model="user.screenings_override"
                  />
                  <label class="form-check-label">Override</label>
                </div>
                <div class="form-check form-check-inline">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    v-model="user.screenings_delete"
                  />
                  <label class="form-check-label">Delete</label>
                </div>
                <legend>Tests</legend>
                <div class="form-check form-check-inline">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    v-model="user.tests_view"
                  />
                  <label class="form-check-label">View</label>
                </div>
                <div class="form-check form-check-inline">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    v-model="user.tests_results"
                  />
                  <label class="form-check-label">Results</label>
                </div>
                <div class="form-check form-check-inline">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    v-model="user.tests_import"
                  />
                  <label class="form-check-label">Import</label>
                </div>
                <legend>Administration</legend>
                <div class="form-check form-check-inline">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    v-model="user.admin"
                  />
                  <label class="form-check-label">Admin</label>
                </div>
              </span>
              <span class="col-2">
                <button
                  @click="update(`/users/${user.id}`, user)"
                  class="btn btn-primary"
                >
                  Update
                </button>
              </span>
            </div>
            <div
              class="row justify-content-center align-items-center pb-2 border border-top-0 border-right-0 border-left-0"
              v-for="newUser in newUsersData"
              :key="newUser.id"
            >
              <span class="col-2">{{ newUser.name }}</span>
              <span class="col-2">{{ newUser.username }}</span>
              <span class="col-6">
                <legend>Screenings</legend>
                <div class="form-check form-check-inline">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    v-model="newUser.screenings_view"
                  />
                  <label class="form-check-label">View</label>
                </div>
                <div class="form-check form-check-inline">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    v-model="newUser.screenings_override"
                  />
                  <label class="form-check-label">Override</label>
                </div>
                <div class="form-check form-check-inline">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    v-model="newUser.screenings_delete"
                  />
                  <label class="form-check-label">Delete</label>
                </div>
                <legend>Tests</legend>
                <div class="form-check form-check-inline">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    v-model="newUser.tests_view"
                  />
                  <label class="form-check-label">View</label>
                </div>
                <div class="form-check form-check-inline">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    v-model="newUser.tests_results"
                  />
                  <label class="form-check-label">Results</label>
                </div>
                <div class="form-check form-check-inline">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    v-model="newUser.tests_import"
                  />
                  <label class="form-check-label">Import</label>
                </div>
                <legend>Administration</legend>
                <div class="form-check form-check-inline">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    v-model="newUser.admin"
                  />
                  <label class="form-check-label">Admin</label>
                </div>
              </span>
              <span class="col-2">
                <button
                  @click="update(`/users/${newUser.id}`, newUser)"
                  class="btn btn-primary"
                >
                  Update
                </button>
              </span>
            </div>
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
      let erp_id = this.users[user].erp_id;
      let name = this.users[user].name;
      let username = this.users[user].username;
      let roles = this.users[user].roles;
      let admin = roles.includes("ADMIN");
      let screenings_view = roles.includes("SCREENINGS_VIEW");
      let screenings_override = roles.includes("SCREENINGS_OVERRIDE");
      let screenings_delete = roles.includes("SCREENINGS_DELETE");
      let tests_view = roles.includes("TESTS_VIEW");
      let tests_results = roles.includes("TESTS_RESULTS");
      let tests_import = roles.includes("TESTS_IMPORT");

      this.usersData.push({
        id,
        erp_id,
        name,
        username,
        admin,
        screenings_view,
        screenings_override,
        screenings_delete,
        tests_view,
        tests_results,
        tests_import,
      });
    }
  },

  methods: {
    update(endpoint, payload) {
      axios
        .put(endpoint, payload)
        .then(({ data }) => {
          this.$toast.success(data.message, "Success", { timeout: 3000 });
        })
        .catch(({ response }) => {
          this.$toast.error(response.data.message, "Error", { timeout: 3000 });
        });
    },

    search() {
      this.disableUserSearch = true;
      axios
        .post("/users/search", { userSearch: this.userSearch })
        .then(({ data, status }) => {
          if (status === 200) {
            const oldUser = this.usersData.find((user) => user.id === data.id);
            const newUser = this.newUsersData.find(
              (user) => user.id === data.id
            );

            if (!oldUser && !newUser) {
              this.newUsersData.push(data);
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
      