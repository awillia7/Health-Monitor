<template>
  <div class="card-body">
    <div v-if="groupText" class="form-group">
      <p>{{ groupText }}</p>

      <div class="form-check form-check-inline">
        <input
          class="form-check-input"
          type="radio"
          :name="groupName"
          :id="fieldId(groupName, 'Yes')"
          v-model="groupAnswer"
          value="1"
          @input="updateGroup(1)"
        />
        <label :for="fieldId(groupName, 'Yes')" class="form-check-label">Yes</label>
      </div>
      <div class="form-check form-check-inline">
        <input
          class="form-check-input"
          type="radio"
          :name="groupName"
          :id="fieldId(groupName, 'No')"
          v-model="groupAnswer"
          value="0"
          @input="updateGroup(0)"
        />
        <label :for="fieldId(groupName, 'No')" class="form-check-label">No</label>
      </div>
    </div>

    <hr v-if="showGroupQuestions && groupStatus" />

    <question-field
      v-for="(question, index) in questionGroup"
      :key="question.id"
      :question="question"
      :group="groupStatus"
      :groupAnswer="groupAnswer"
      :hidden="!showGroupQuestions"
      v-model="questionValue[index]"
      @input="updateValue"
    ></question-field>

    <ul v-if="!showGroupQuestions">
      <li v-for="question in questionGroup" :key="question.id">{{ question.text }}</li>
    </ul>
  </div>
</template>

<script>
import QuestionField from "./QuestionField.vue";

export default {
  props: ["value", "questionGroup"],

  components: { QuestionField },

  data() {
    return {
      groupAnswer: null,
      questionValue: null
    };
  },

  created() {
    this.questionValue = JSON.parse(JSON.stringify(this.value));
    Object.keys(this.questionValue).forEach(question => {
      this.questionValue[question] = null;
    });
  },

  computed: {
    groupText() {
      return this.questionGroup[0].group_text
        ? this.questionGroup[0].group_text
        : null;
    },
    groupName() {
      return `group_${this.questionGroup[0].group_order}`;
    },
    groupStatus() {
      return this.questionGroup[0].group_text ? true : false;
    },
    showGroupQuestions() {
      if (!this.questionGroup[0].group_text) return true;
      return this.groupAnswer && this.groupAnswer !== "0" ? true : false;
    }
  },

  methods: {
    fieldId(name, val) {
      return `${name}_${val}`;
    },

    updateValue() {
      this.$emit("input", this.questionValue);
    },

    updateGroup(value) {
      let newValue = [];

      for (const key in this.questionValue) {
        newValue[key] = value ? null : 0;
      }

      this.questionValue = newValue;
      this.$emit("input", this.questionValue);
    }
  }
};
</script>