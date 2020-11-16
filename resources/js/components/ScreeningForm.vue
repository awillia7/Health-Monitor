<template>
  <div class="container">
    <div class="row justify-content-center">
      <form class="w-100" @submit="submitted = true" action="/screenings/submit" method="post">
        <slot></slot>

        <div
          class="card mb-2"
          v-for="(questionGroup, index) in questionGroups"
          :key="`questionGroup-${index}`"
        >
          <!-- <question-group-field :question-group="questionGroup" /> -->
          <question-group-field v-model="value[index]" :question-group="questionGroup" />
        </div>

        <br />

        <div class="form-group">
          <button
            :disabled="!valid || submitted"
            type="submit"
            class="btn btn-lg btn-outline-primary btn-block"
          >Submit</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import QuestionGroupField from "./QuestionGroupField.vue";

export default {
  props: ["questions"],

  data() {
    return {
      questionGroups: null,
      submitted: false,
      value: null
    };
  },

  created() {
    this.questionGroups = _.groupBy(this.questions, question => {
      return question.group_order;
    });

    this.value = JSON.parse(JSON.stringify(this.questionGroups));
    Object.keys(this.value).forEach(group => {
      Object.keys(this.value[group]).forEach(question => {
        this.value[group][question] = null;
      });
    });
  },

  computed: {
    valid() {
      for (const group in this.value) {
        for (const question in this.value[group]) {
          if (this.value[group][question] === null) return false;
        }
      }
      return true;
    }
  },

  components: { QuestionGroupField }
};
</script>