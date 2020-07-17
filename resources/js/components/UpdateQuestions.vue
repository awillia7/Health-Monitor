<template>
  <div class="container">
    <div class="row justify-content-center text-center">
      <form class="w-100">
        <div class="card mb-2">
          <h3 class="card-header">Update Questions</h3>
        </div>

        <div v-for="(questionGroup, qgIndex) in questionGroups" :key="qgIndex" class="card mb-2">
          <div class="card-header">
            <div class="row justify-content-center font-weight-bold">
              <div class="col-10">
                <label>Group Text</label>
              </div>
              <div class="col-2">
                <label>Group Order</label>
              </div>
            </div>
            <div class="row mb-2 justify-content-center">
              <div class="col-10">
                <input
                  type="text"
                  class="form-control"
                  placeholder="Group Text"
                  v-model="questionGroups[qgIndex][0].group_text"
                />
              </div>
              <div class="col-2">
                <input
                  type="text"
                  class="form-control"
                  placeholder="Group Order"
                  v-model="questionGroups[qgIndex][0].group_order"
                />
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="row justify-content-center font-weight-bold">
              <div class="col-10">
                <label>Question Text</label>
              </div>
              <div class="col-2">
                <label>Question Order</label>
              </div>
            </div>
            <div
              v-for="(question, qIndex) in questionGroup"
              :key="qIndex"
              class="row mb-2 justify-content-center"
            >
              <input
                type="hidden"
                :name="questionGroups[qgIndex][qIndex].id"
                v-model="questionGroups[qgIndex][qIndex].id"
              />

              <div class="col-10">
                <input
                  type="text"
                  class="form-control"
                  placeholder="Question Text"
                  v-model="questionGroups[qgIndex][qIndex].text"
                />
              </div>

              <div class="col-2">
                <input
                  type="text"
                  class="form-control"
                  placeholder="Question Order"
                  v-model="questionGroups[qgIndex][qIndex].question_order"
                />
              </div>
            </div>
          </div>
        </div>

        <div class="form-group">
          <button type="submit" class="btn btn-lg btn-outline-primary btn-block">Submit</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  props: ["questions"],

  data() {
    return {
      questionGroups: null
    };
  },

  created() {
    this.questionGroups = _.groupBy(this.questions, question => {
      return question.group_order;
    });
  }
};
</script>