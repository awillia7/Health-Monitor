<template>
  <div class="container">
    <div class="row justify-content-center text-center">
      <div class="w-100">
        <div class="card mb-2">
          <h3 class="card-header">Latest COVID Test Results</h3>
          <div class="card-body">
            <div class="row justify-content-center align-items-center mb-2">
              <span class="col-4 font-weight-bold">Name</span>
              <span :class="colClasses" class="font-weight-bold"
                >Test Date</span
              >
              <span v-if="displayResults" class="col-4 font-weight-bold"
                >Test Result</span
              >
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
              <span :class="colClasses">{{ formatDate(user.test_date) }}</span>
              <span
                v-if="displayResults"
                :class="
                  user.test_result ? user.result_html_class + ' col-4' : 'col-4'
                "
                >{{ user.test_result }}</span
              >
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
  props: ["tests"],

  computed: {
    colClasses() {
      return this.displayResults ? "col-4" : "col-8";
    },
    displayResults() {
      return this.tests.length && this.tests[0].results_permission;
    },
  },

  methods: {
    formatDate($date) {
      return $date ? moment($date).format("MMMM D, YYYY") : null;
    },
  },
};
</script>