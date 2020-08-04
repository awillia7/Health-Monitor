<template>
  <div class="container">
    <div class="row justify-content-center text-center">
      <div class="w-100">
        <div class="card mb-2">
          <h3 class="card-header">Today's Uncleared Screenings</h3>
          <div class="card-body">
            <div class="row justify-content-center align-items-center mb-2">
              <span class="col-4 font-weight-bold">Name</span>
              <span class="col-2 font-weight-bold">Score</span>
              <span class="col-2 font-weight-bold">Fail Score</span>
              <span class="col-2 font-weight-bold">Override</span>
              <span class="col-2"></span>
            </div>
            <div
              class="row justify-content-center align-items-center mb-2"
              v-for="screening in screenings"
              :key="screening.id"
            >
              <span class="col-4">
                <a :href="`/screenings/${screening.id}`">{{ screening.user.name }}</a>
              </span>
              <span class="col-2">{{ screening.score }}</span>
              <span class="col-2">{{ screening.fail_score }}</span>
              <span class="col-2">No</span>
              <span class="col-2"></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["screenings"],
};
</script>