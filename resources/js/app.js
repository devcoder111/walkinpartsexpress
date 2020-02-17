
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

const Vue = window.Vue = require('vue');

import Vue2Filters from 'vue2-filters';
import Vuex from 'vuex';
// import ProductZoomer from 'vue-product-zoomer';
import 'es6-promise/auto';
import axios from 'axios';
import Router from 'vue-router'

// import Loading from 'vue-loading-overlay';
// // Import stylesheet
// import 'vue-loading-overlay/dist/vue-loading.css';


// Init plugins
Vue.use(Vuex);
Vue.use(Router);
Vue.use(Vue2Filters);

// Vue.use(ProductZoomer);

Vue.prototype.$http = axios;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('CartPreview', require('./components/header/CartPreview.vue').default);
Vue.component('AddToCart', require('./components/product/AddToCart.vue').default);
Vue.component('Cart', require('./components/cart/Cart.vue').default);
Vue.component('CheckoutAddress', require('./components/checkout/CheckoutAddress.vue').default);
Vue.component('Checkout', require('./components/checkout/Checkout.vue').default);
Vue.component('OrderSummary', require('./components/checkout/OrderSummary.vue').default);
Vue.component('UserAccount', require('./components/user/UserAccount.vue').default);

// Vue.component('ProductImages', require('./components/product/ProductImages.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    data: {
        fullPage: true,
    },
    methods: {
        submit() {
            let loader = this.$loading.show({
                // Optional parameters
                container: this.fullPage ? null : this.$refs.formContainer,
                canCancel: true,
                onCancel: this.onCancel,
            });
            // simulate AJAX
            setTimeout(() => {
                loader.hide();
            },5000);
        },
        onCancel() {
            console.log('User cancelled the loader.');
        }
    }
});
