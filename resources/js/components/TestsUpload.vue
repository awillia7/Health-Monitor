<template>
  <div class="container">
    <div class="row justify-content-center text-center">
      <div class="w-100">
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
        <div
          v-if="disableUpload"
          class="row text-center mb-2 d-flex justify-content-center"
        >
          <button
            :disabled="disableImport"
            class="btn btn-outline-primary btn-lg"
            @click="store"
          >
            Save Test Results
          </button>
        </div>
        <div v-if="disableUpload" class="row">
          <span class="col-4 text-success"
            >IMPORTED: {{ testsImportCount }}</span
          >
          <span class="col-4 text-danger"
            >ERRORS: {{ testsImportErrorsCount }}</span
          >
          <span class="col-4">TOTAL: {{ testsCount }}</span>
        </div>
        <div v-if="disableUpload && disableImport" class="row">
          <div class="col-12 progress" style="height: 20px">
            <div
              class="progress-bar"
              role="progressbar"
              :style="`width: ${testsImportPercent}`"
              :aria-valuenow="testsImportCount + testsImportErrorsCount"
              aria-valuemin="0"
              :aria-valuemax="testsCount"
            >
              {{ testsImportStatus }}
            </div>
          </div>
        </div>
        <hr v-if="disableUpload" />
        <div class="row" v-for="(row, index) in excelData" :key="index">
          <div class="col-md-1">&nbsp;</div>
          <div class="col-md-2 justify-content-center text-center">
            {{ row.user_id }}
          </div>
          <div class="col-md-3 justify-content-center text-center">
            {{ row.result }}
          </div>
          <div class="col-md-3 justify-content-center text-center">
            {{ row.test_date }}
          </div>
          <div v-if="row.id > 0" class="col-md-2">
            <span class="text-success">IMPORTED</span>
          </div>
          <div v-if="row.id < 0" class="col-md-2">
            <span class="text-danger">ERROR</span>
          </div>
          <div class="col-md-1">&nbsp;</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import XLSX from "XLSX";

export default {
  data() {
    return {
      excelData: [],
      disableImport: false,
    };
  },

  computed: {
    disableUpload() {
      return !(this.excelData === undefined || this.excelData.length == 0);
    },

    testsCount() {
      return this.excelData.length;
    },

    testsImportCount() {
      if (!this.excelData) return 0;
      return this.excelData.filter((test) => {
        return test.id > 0;
      }).length;
    },

    testsImportErrorsCount() {
      if (!this.excelData) return 0;
      return this.excelData.filter((test) => {
        return test.id == -1;
      }).length;
    },

    testsImportPercent() {
      const percent = this.testsCount
        ? Math.floor(
            ((this.testsImportCount + this.testsImportErrorsCount) /
              this.testsCount) *
              100
          )
        : 0;
      return `${percent}%`;
    },

    testsImportStatus() {
      if (!this.testsCount) return null;
      return this.testsImportCount + this.testsImportErrorsCount ===
        this.testsCount
        ? "DONE"
        : this.testsImportPercent;
    },
  },

  methods: {
    store() {
      this.disableImport = true;
      let positiveTests = [];
      let importPromises = [];
      for (let test in this.excelData) {
        let user_id = this.excelData[test].user_id;
        let test_date = new Date(this.excelData[test].test_date)
          .toISOString()
          .slice(0, 10);
        let result = this.excelData[test].result;

        importPromises.push(
          axios
            .post("/tests", {
              user_id,
              test_date,
              result,
            })
            .then(({ data }) => {
              this.excelData[test].id = data.id;
              if (data.result === "POSITIVE") {
                positiveTests.push(data.id);
              }
            })
            .catch(({ response }) => {
              this.excelData[test].id = -1;
            })
        );
      }

      Promise.all(importPromises).then(() => {
        if (positiveTests.length) {
          axios
            .post("/tests/email", {
              tests: positiveTests,
            })
            .then(({ data }) => {})
            .catch(({ response }) => {});
        }
      });
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

          let testObj = [];
          for (let row in rowObj) {
            testObj[row] = {
              user_id: rowObj[row].ID,
              test_date: rowObj[row].Date,
              result: rowObj[row].Result,
              id: null,
            };
          }

          this.excelData = testObj;
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