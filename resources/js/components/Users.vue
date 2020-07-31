<template>
  <div class="container">
    <div class="row justify-content-center text-center">
      <div class="w-100">
        <div class="card mb-2">
          <h3 class="card-header">Users</h3>
          <div class="card-body">
            <div
              class="row justify-content-center align-items-center mb-2"
              v-for="user in usersData"
              :key="user.id"
            >
              <span class="col-2">{{ user.name }}</span>
              <span class="col-2">{{ user.username }}</span>
              <span class="col-6">
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" v-model="user.manager" />
                  <label class="form-check-label">Manager</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" v-model="user.override" />
                  <label class="form-check-label">Override</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" v-model="user.admin" />
                  <label class="form-check-label">Admin</label>
                </div>
              </span>
              <span class="col-2">
                <button class="btn btn-primary">Update</button>
              </span>
            </div>
            <hr />
            <div class="row justify-content-center align-items-center mb-2">
              <span class="col-6">
                <input class="form-control" type="text" v-model="newUser" />
              </span>
              <span class="col-2">
                <button class="btn btn-primary">Add User</button>
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
      newUser: null
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
      let manager = roles.includes("MANAGER");
      let override = roles.includes("OVERRIDE");

      this.usersData.push({
        id,
        erp_id,
        name,
        username,
        admin,
        manager,
        override
      });
    }
  }
};
</script>
      