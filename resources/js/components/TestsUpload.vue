<template>
  <div class="container">
    <div class="row">
      <div class="col-md-1">&nbsp;</div>
      <div class="col-md-10 justify-content-center text-center">
        <div class="form-group files">
          <label>Upload Test Results </label>
          <input
            type="file"
            :disabled="disableUpload"
            class="form-control"
            @change="excelExport"
            accept="application/vnd.openxmlformats-officedocument.spreadsheet.spreadsheetml.sheet"
          />
        </div>
      </div>
      <div class="col-md-1">&nbsp;</div>
    </div>
    <div class="row" v-for="(row, index) in excelData" :key="index">
      <div class="col-md-1">&nbsp;</div>
      <div class="col-md-2 justify-content-center text-center">
        {{ row.id }}
      </div>
      <div class="col-md-4 justify-content-center text-center">
        {{ row.result }}
      </div>
      <div class="col-md-4 justify-content-center text-center">
        {{ row.date }}
      </div>
      <div class="col-md-1">&nbsp;</div>
    </div>
    <div v-if="disableUpload" class="row text-center">
      <button class="btn btn-primary" @click="store">Save Test Results</button>
    </div>
  </div>
</template>

<script>
import XLSX from "XLSX";

export default {
  data() {
    return {
      excelData: [],
    };
  },

  computed: {
    disableUpload() {
      return !(this.excelData === undefined || this.excelData.length == 0);
    },
  },

  methods: {
    store() {
      for (let test in this.excelData) {
        axios
          .post("/tests", {
            user_id: this.excelData[test].id,
            test_date: this.excelData[test].date,
            result: this.excelData[test].result,
          })
          .then(({ data }) => {
            console.log(data);
          })
          .catch(({ response }) => {
            this.$toast.error(response.data.message, "Error", {
              timeout: 3000,
            });
          });
      }
    },

    excelExport(event) {
      let input = event.target;
      let reader = new FileReader();
      reader.onload = () => {
        let fileData = reader.result;
        let wb = XLSX.read(fileData, { type: "binary" });
        wb.SheetNames.forEach((sheetName) => {
          let rowObj = XLSX.utils.sheet_to_json(wb.Sheets[sheetName], {
            raw: false,
          });
          this.excelData = rowObj;
          //   this.excelData = JSON.stringify(rowObj);
        });
      };
      reader.readAsBinaryString(input.files[0]);
    },
  },
};
</script>

<style scoped>
.files input {
  outline: 2px dashed #92b0b3;
  outline-offset: -10px;
  -webkit-transition: outline-offset 0.15s ease-in-out,
    background-color 0.15s linear;
  transition: outline-offset 0.15s ease-in-out, background-color 0.15s linear;
  padding: 100px 0px 85px 35%;
  text-align: center !important;
  margin: 0;
  width: 100% !important;
}
.files input:focus {
  outline: 2px dashed #92b0b3;
  outline-offset: -10px;
  -webkit-transition: outline-offset 0.15s ease-in-out,
    background-color 0.15s linear;
  transition: outline-offset 0.15s ease-in-out, background-color 0.15s linear;
  border: 1px solid #92b0b3;
}
.files {
  position: relative;
}
.files:after {
  pointer-events: none;
  position: absolute;
  top: 60px;
  left: 0;
  width: 50px;
  right: 0;
  height: 56px;
  content: "";
  background-image: url("../../images/upload.png");
  display: block;
  margin: 0 auto;
  background-size: 100%;
  background-repeat: no-repeat;
}
.color input {
  background-color: #f1f1f1;
}
.files:before {
  position: absolute;
  bottom: 10px;
  left: 0;
  pointer-events: none;
  width: 100%;
  right: 0;
  height: 35px;
  content: " or drag it here. ";
  display: block;
  margin: 0 auto;
  color: #2ea591;
  font-weight: 600;
  text-transform: capitalize;
  text-align: center;
}
</style>