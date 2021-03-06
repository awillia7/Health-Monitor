/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueIziToast from 'vue-izitoast';

import 'izitoast/dist/css/iziToast.min.css';

Vue.use(VueIziToast);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('screening-form', require('./components/ScreeningForm.vue').default);
Vue.component('screening-code', require('./components/ScreeningCode.vue').default);
Vue.component('screening', require('./components/Screening.vue').default);
Vue.component('screenings', require('./components/Screenings.vue').default);
Vue.component('update-questions', require('./components/UpdateQuestions.vue').default);
Vue.component('users', require('./components/Users.vue').default);
Vue.component('testing-optin', require('./components/TestingOptin.vue').default);
Vue.component('tests-upload', require('./components/TestsUpload.vue').default);
Vue.component('tests', require('./components/Tests.vue').default);
Vue.component('testing-waivers', require('./components/TestingWaivers.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
