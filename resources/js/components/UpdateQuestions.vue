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
              <div class="col-2">
                <label>Group Order</label>
              </div>

              <div class="col-10">
                <label>Group Text</label>
              </div>
            </div>
            <div class="row mb-2 justify-content-center">
              <div class="col-2">
                <input
                  type="text"
                  class="form-control"
                  placeholder="Group Order"
                  v-model="questionGroups[qgIndex][0].group_order"
                />
              </div>

              <div class="col-10">
                <input
                  type="text"
                  class="form-control"
                  placeholder="Group Text"
                  v-model="questionGroups[qgIndex][0].group_text"
                />
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="row justify-content-center font-weight-bold">
              <div class="col-1">
                <label>Question Order</label>
              </div>

              <div class="col-10">
                <label>Question Text</label>
              </div>

              <div class="col-1">
                <label>Question Value</label>
              </div>
            </div>
            <div
              v-for="(question, qIndex) in questionGroup"
              :key="qIndex"
              class="row mb-2 justify-content-center"
            >
              <input type="hidden" v-model="questionGroups[qgIndex][qIndex].id" />

              <div class="col-1">
                <input
                  type="text"
                  class="form-control"
                  placeholder="Question Order"
                  v-model="questionGroups[qgIndex][qIndex].question_order"
                />
              </div>

              <div class="col-10">
                <input
                  type="text"
                  class="form-control"
                  placeholder="Question Text"
                  v-model="questionGroups[qgIndex][qIndex].text"
                />
              </div>

              <div class="col-1">
                <input
                  type="text"
                  class="form-control"
                  v-model="questionGroups[qgIndex][qIndex].value"
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
    let qg = _.groupBy(this.questions, question => {
      return question.group_order;
    });

    let value = new Array();
    for (const group in qg) {
      // let g = {
      value[group] = {
        group_text: qg[group][0].group_text,
        group_order: qg[group][0].group_order,
        questions: []
      };
      console.log(value);
      // for (const question in qg[group]) {
      //   const question_id = qg[group][question].id;
      //   const question_text = qg[group][question].text;
      //   const question_order = qg[group][question].question_order;
      //   const question_value = qg[group][question].text;
      //   this.questionGroups[group].answers[question_order] = {
      //     id: question_id,
      //     text: question_text,
      //     question_order: question_order,
      //     value: question_value
      //   };
      // }
    }
  }
};
</script>