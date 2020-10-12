<template>
  <div class="container">
    <div class="row justify-content-center text-center">
      <div class="w-100">
        <div class="card mb-2">
          <h3 class="card-header">Latest COVID Test Results</h3>
          <div class="card-body">
            <div class="row justify-content-center align-items-center mb-2">
              <span class="col-4 font-weight-bold">Name</span>
              <span class="col-4 font-weight-bold">Test Date</span>
              <span class="col-4 font-weight-bold">Test Result</span>
            </div>
            <div class="row justify-content-center align-items-center mb-2">
              <span
                class="col-12 text-uppercase font-weight-light"
                v-if="!tests.length"
                >No Users Opted in for Testing</span
              >
            </div>
            <div
              class="row justify-content-center align-items-center mb-2"
              v-for="user in tests"
              :key="user.id"
            >
              <span class="col-4">
                {{ user.name }}
              </span>
              <span class="col-4">{{
                user.tests[0] ? user.tests[0].test_date : null
              }}</span>
              <span
                :class="
                  user.tests[0] ? user.tests[0].htmlClass + ' col-4' : 'col-4'
                "
                >{{ user.tests[0] ? user.tests[0].result : null }}</span
              >
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["tests"],
};
</script>