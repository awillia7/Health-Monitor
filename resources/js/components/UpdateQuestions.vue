<template>
  <div class="container">
    <div class="row justify-content-center text-center">
      <div class="card mb-5 w-100">
        <h3 class="card-header">Update Fail Score</h3>
        <div class="card-body form-group row justify-content-center mb-0">
          <label for="failScore" class="col-2 col-form-label font-weight-bold">Fail Score</label>
          <div class="col-4">
            <input class="form-control" type="text" name="failScore" v-model.number="failScoreData" />
          </div>
          <button
            :disabled="disableFailScore"
            @click="updateFailScore()"
            class="col-2 btn btn-outline-primary"
          >Update Fail Score</button>
        </div>
      </div>

      <form class="w-100" action="/questions/update-all" method="post">
        <slot></slot>

        <div class="card mb-2">
          <h3 class="card-header">Update Questions</h3>
        </div>

        <div class="card-body">
          <div v-for="(questionGroup, qgIndex) in questionsData" :key="qgIndex" class="card mb-2">
            <div class="card-header">
              <div class="row justify-content-center font-weight-bold">
                <div class="col-12">
                  <label>Group Text</label>
                </div>
              </div>
              <div class="row mb-2 justify-content-center" v-if="questionsData[qgIndex].group_text">
                <div class="col-12">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Group Text"
                    :name="`group-text_${questionsData[qgIndex].group_id}`"
                    v-model="questionsData[qgIndex].group_text"
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
                  <label>Question Value</label>
                </div>
              </div>
              <div
                v-for="(question, qIndex) in questionGroup.questions"
                :key="qIndex"
                class="row mb-2 justify-content-center"
              >
                <div class="col-10">
                  <input
                    type="text"
                    class="form-control"
                    :name="`text_${questionsData[qgIndex].questions[qIndex].id}`"
                    v-model="questionsData[qgIndex].questions[qIndex].text"
                  />
                </div>

                <div class="col-2">
                  <input
                    type="text"
                    class="form-control"
                    :name="`value_${questionsData[qgIndex].questions[qIndex].id}`"
                    v-model="questionsData[qgIndex].questions[qIndex].value"
                  />
                </div>
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
  props: ["questions", "failScore"],

  data() {
    return {
      questionsData: [],
      failScoreData: null,
    };
  },

  computed: {
    disableFailScore() {
      return !(
        this.failScoreData === parseInt(this.failScoreData, 10) &&
        parseInt(this.failScoreData, 10) > 0
      );
    },
  },

  methods: {
    updateFailScore() {
      axios
        .put("/fail-score", {
          "failScore": this.failScoreData,
        })
        .then(({ data }) => {
          console.log(data);
          this.$toast.success(data.message, "Success", { timeout: 3000 });
        })
        .catch(({ response }) => {
          this.$toast.error(response.data.message, "Error", { timeout: 3000 });
        });
    },
  },

  created() {
    this.failScoreData = this.failScore;

    let qg = _.groupBy(this.questions, (question) => {
      return question.group_order;
    });

    for (const group in qg) {
      let gData = {
        group_id: qg[group][0].group_order,
        group_text: qg[group][0].group_text,
        questions: [],
      };

      for (const question in qg[group]) {
        let qData = {
          id: qg[group][question].id,
          text: qg[group][question].text,
          value: qg[group][question].value,
        };
        gData.questions.push(qData);
      }

      this.questionsData.push(gData);
    }
  },
};
</script>