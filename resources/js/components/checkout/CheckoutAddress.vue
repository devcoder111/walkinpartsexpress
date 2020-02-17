<template>
    <div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                           for="grid-first-name">
                        First Name
                    </label>
                    <input v-model="firstName"
                            class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
                            name="billing_first_name" id="grid-first-name" type="text" placeholder="First Name">

                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                           for="grid-last-name">
                        Last Name
                    </label>
                    <input v-model="lastName"
                            class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-grey"
                            name="billing_last_name" id="grid-last-name" type="text" placeholder="Last Name">
                </div>
            </div>
            <!-- TODO: come back and add email address in if user is not authenticated and checking out as guest; also require phone number -->
            <!--            <div class="flex flex-wrap -mx-3 mb-6">-->
            <!--                <div class="w-full px-3">-->
            <!--                    <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"-->
            <!--                           for="email-id">-->
            <!--                        Email Address-->
            <!--                    </label>-->
            <!--                    <input-->
            <!--                            class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-grey"-->
            <!--                            id="email-id" type="email" placeholder="Email Address">-->
            <!--                </div>-->
            <!--            </div>-->
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3">
                    <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                           for="address-1">
                        Address 1
                    </label>
                    <input v-model="address1"
                            class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-grey"
                            id="address-1" type="text" placeholder="Address 1">

                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3">
                    <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                           for="address-2">
                        Address 2
                    </label>
                    <input v-model="address2"
                            class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-grey"
                            id="address-2" type="text" placeholder="Address 2">

                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-2">
                <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                           for="grid-city">
                        City
                    </label>
                    <input v-model="city"
                            class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-grey"
                            id="grid-city" type="text" placeholder="City Name">
                </div>
                <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                           for="grid-state">
                        State
                    </label>
                    <div class="relative">
                        <select v-model="state" class="block appearance-none w-full bg-grey-lighter border border-grey-lighter text-grey-darker py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-grey"
                                id="grid-state" v-if="states" @blur="updateTaxes()">
                            <option :value="undefined" disabled selected> - State -</option>
                            <option v-for="state in states" :value="state.id">{{ state.name }}</option>
                        </select>
                        <div
                                class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-grey-darker">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 20 20">
                                <path
                                        d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                           for="grid-zip">
                        Zip
                    </label>
                    <input v-model="zip"
                            class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-grey"
                            id="grid-zip" type="text" placeholder="90210" @blur="updateTaxes()">
                </div>
            </div>


    </div>
</template>


<script>
    import {EventBus} from "../../event-bus";
    import swal from 'sweetalert2';

    export default {
        data: function () {
            return {
                states: null,
                firstName: null,
                lastName: null,
                address1: null,
                address2: null,
                city: null,
                state: undefined,
                zip: null,
            };
        },
        props: ['type'],
        computed: {},
        mounted() {
            this.getStates();

            EventBus.$on('submit-order-button-clicked', () => {
               EventBus.$emit((this.type === 1 ? "billing" : "shipping")+'-address-data', this.getAddressData());
            });

        },
        methods: {
            updateTaxes() {
                // Only update taxes if if it type ID of 2, or shipping address
                if(this.type === 2) {
                    if(this.state && this.isValidZipCode(this.zip)) {
                        this.$http.get(`/taxes/${this.state}/${this.zip}`)
                            .then((response) => {
                                EventBus.$emit('update-taxes', response.data.payload.taxes);
                            })
                            .catch((error) => {
                                alert(error);
                            });
                    }
                }
            },
            isValidZipCode(sZip) {
                return /^\d{5}(-\d{4})?$/.test(sZip);
            },
            getStates() {
                this.$http.get('/state')
                    .then((response) => {
                        this.states = response.data.payload;
                    })
            },
            getAddressData() {
                return {
                    'firstName': this.firstName,
                    'lastName': this.lastName,
                    'address1': this.address1,
                    'address2': this.address2,
                    'city': this.city,
                    'state': this.state,
                    'zip': this.zip,
                };
            }
        }
    }
</script>
