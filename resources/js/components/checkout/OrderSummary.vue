<template>
    <div class="ml-6 mr-6 md:ml-0 md:mr-8" v-if="subtotal">
        <div class="rounded overflow-hidden pt-2 pb-2 shadow-lg bg-blue-darker mt-8">
            <div class="px-6 py-4">
                <div class="font-bold text-xl mb-2 text-white rounded p-2 bg-blue-dark border-b border-teal">
                    Order Summary
                </div>
                <ul class="summary-item list-reset">
                    <li class="clearfix border-b border-blue p-2 leading-loose text-white">
                        <span class="text-base font-bold">Subtotal</span>
                        <span class="text-base font-bold float-right">{{ subtotal | currency}}</span>
                    </li>
                    <li class="clearfix border-b border-blue p-2 leading-loose text-white">
                        <span class="text-base font-bold">Shipping</span>
                        <span class="text-base font-bold float-right">{{ shipping | currency }}</span>
                    </li>
                    <li class="clearfix border-b border-blue p-2 leading-loose text-white">
                        <span class="text-base font-bold">Taxes</span>
                        <span class="text-base font-bold float-right">{{ taxes | currency}}</span>
                    </li>
                    <li class="clearfix bg-blue-darkest p-2 leading-loose text-white rounded">
                        <span class="text-base font-bold">Total</span>
                        <span class="text-base font-bold float-right">{{ total | currency }}</span>
                    </li>
                </ul>
            </div>

        </div>
        <div class="flex flex-wrap mt-6 border-t border-gray mb-6 pt-4">
            <button
                    class="bg-blue hover:bg-blue-dark text-white text-xl w-full font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                    type="button" @click="submitButtonClicked" :disabled="submitButtonDisabled">
                Submit Order
            </button>
        </div>
    </div>
</template>

<script>
    import {EventBus} from "../../event-bus";
    import swal from 'sweetalert2';

    export default {
        data: function () {
            return {
                subtotal: null,
                shipping: null,
                taxes: null,
                total: null,
                submitButtonDisabled: false,
            };
        },
        props: [],
        computed: {},
        mounted() {
            this.getCart();

            EventBus.$on('enable-checkout-button', () => {
                this.submitButtonbDisabled = false;
            });

            EventBus.$on('disable-checkout-button', () => {
                this.submitButtonbDisabled = true;
            });

            EventBus.$on('update-taxes', (taxes) => {
                if(this.taxes === null) {
                    this.taxes = 0;
                }

                // Remove old tax value from the total, update the tax value, then update the new total.
                this.total -= this.taxes;
                this.taxes = taxes;
                this.total += this.taxes;
            });
        },
        methods: {
            getCart() {
                this.$http.get('/api/cart-preview').then((response) => {
                    this.subtotal = response.data.payload.total;
                    this.taxes = 0;
                    this.shipping = 0;
                    this.total = this.subtotal + this.taxes + this.shipping;

                    if(response.data.payload.quantity === 0) {
                        EventBus.$emit('checkout-no-items-in-cart');
                    }

                }).catch((error) => {
                    alert('an error has occurred');
                }).
                then(() => {
                    EventBus.$emit('checkout-page-data-loaded');
                });
            },
            submitButtonClicked() {
                EventBus.$emit('disable-checkout-button');
                EventBus.$emit('submit-order-button-clicked');
            },
        }
    }
</script>


