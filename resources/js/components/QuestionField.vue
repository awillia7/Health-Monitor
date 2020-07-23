<template>
  <div>
    <div class="form-group" :class="classes">
      <p>{{ question.text }}</p>

      <div class="form-check form-check-inline">
        <input
          class="form-check-input"
          type="radio"
          :name="questionName"
          :id="fieldId(questionName, 'Yes')"
          :value="question.value"
        />
        <label :for="fieldId(questionName, 'Yes')" class="form-check-label">Yes</label>
      </div>
      <div class="form-check form-check-inline">
        <input
          class="form-check-input"
          type="radio"
          :name="questionName"
          :id="fieldId(questionName, 'No')"
          value="0"
          :checked="checked"
        />
        <label :for="fieldId(questionName, 'No')" class="form-check-label">No</label>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["value", "question", "group", "groupAnswer", "hidden"],

  computed: {
    classes() {
      let classes = this.group ? "ml-5 " : " ";
      classes += this.hidden ? " d-none " : " ";
      return classes;
    },
    questionName() {
      return `question_${this.question.id}`;
    },
    checked() {
      return this.hidden;
    }
  },

  methods: {
    fieldId(name, val) {
      return `${name}_${val}`;
    }
  }
};
</script>