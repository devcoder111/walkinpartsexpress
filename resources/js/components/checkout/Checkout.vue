<template>

        <div class="w-full text-center my-12 text-blue-darker text-2xl" v-if="checkoutPageDataLoaded && !checkoutPageItemsInCart" style="margin-top: 450px;">
            Your cart is empty!
        </div>
        <div class="w-full md:w-2/3 lg:w-3/5 px-8" v-else-if="checkoutPageDataLoaded && checkoutPageItemsInCart">
            <form class="w-full max-w-md mt-5">
                <div>
                    <div class="text-2xl md:text-3xl lg:text-2xl font-bold mb-6">
                        <span class="pb-2">Shipping Address</span>
                    </div>
                    <checkout-address :type="2"></checkout-address>
                </div>
                <div>
                    <div class="text-2xl md:text-3xl lg:text-2xl font-bold mt-6 border-t border-gray mb-6 pt-4">
                        <span class="pb-2">Billing Address</span>
                    </div>
                    <div class="my-6">
                        <input class="form-check-input" type="checkbox" id="billing-same-as-shipping"
                               v-model="billingSameAsShippingChecked" @click="billingSameAsShippingToggle()">
                        <label class="form-check-label ml-2 text-md pt-2" for="billing-same-as-shipping">
                            Billing address is same as shipping address
                        </label>
                    </div>
                    <checkout-address :type="1" v-if="!billingSameAsShippingChecked"></checkout-address>
                </div>
                <!-- PAYMENT INFORMATION -->
                <div class="flex flex-wrap mt-6 border-t border-gray mb-6 pt-4">
                    <div class="text-2xl md:text-3xl lg:text-2xl font-bold">
                        <span class="pb-2">Payment Information</span>
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full px-3">
                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                               for="cname">
                            Name on Card
                        </label>
                        <input v-model="ccName"
                               class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-grey"
                               id="cname" type="text" placeholder="Name on Card">

                    </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-2">
                    <div class="w-full md:w-3/5 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                               for="cnumber">
                            Credit card number
                        </label>
                        <input v-model="ccNumber"
                               class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-grey"
                               id="cnumber" type="text" placeholder="Credit card number">
                    </div>
                    <div class="w-full md:w-1/5 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                               for="expiration">
                            Expiration
                        </label>
                        <input v-model="ccExpiration"
                               class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-grey"
                               name="expiration" id="expiration" maxlength="5" type="text" placeholder="MM/YY">
                    </div>
                    <div class="w-full md:w-1/5 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="cvv">
                            CVV
                        </label>
                        <input v-model="ccCvv"
                               class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-grey"
                               name="cvv" id="cvv" type="text" maxlength="4" placeholder="123">
                    </div>
                </div>
            </form>
        </div>
</template>

<script>
    import {EventBus} from "../../event-bus";
    import swal from 'sweetalert2';

    export default {
        data: function () {
            return {
                shippingAddress: null,
                billingAddress: null,
                ccName: null,
                ccNumber: null,
                ccExpiration: null,
                ccCvv: null,
                billingSameAsShippingChecked: false,
                checkoutPageDataLoaded: false,
                checkoutPageItemsInCart: false,
            };
        },
        watch: {
            shippingAddress(address) {
                if (address !== null && this.billingAddress != null) {
                    EventBus.$emit('address-data-collected');
                }
            },
            ccExpiration(expiration) {
                        const result = expiration.replace(/\D/g, "")
                            .replace(/\B(?=(\d{2})+(?!\d))/g, "/");

                        this.ccExpiration = result;
            },
        },
        props: [],
        computed: {},
        mounted() {
            this.checkoutPageItemsInCart = true;

            EventBus.$on('shipping-address-data', (addressData) => {
                this.shippingAddress = addressData;
            });
            EventBus.$on('billing-address-data', (addressData) => {
                this.billingAddress = addressData;
            });
            EventBus.$on('address-data-collected', () => {
                this.submitOrder();
            });
            EventBus.$on('checkout-page-data-loaded', () => {
                this.checkoutPageDataLoaded = true;
            });
            EventBus.$on('checkout-no-items-in-cart', () => {
                this.checkoutPageItemsInCart = false;
            });

            this.getCart();
        },
        methods: {
            billingSameAsShippingToggle() {
                this.billingSameAsShippingChecked = !this.billingSameAsShippingChecked;
            },
            submitOrder() {
                if (this.billingSameAsShippingChecked) {
                    this.billingAddress = this.shippingAddress;
                }

                if (!this.luhnCheck(this.ccNumber)) {
                    swal.fire({
                        title: 'Invalid credit card number entered!',
                        text: '',
                        type: 'error',
                    });

                    EventBus.$emit('enable-checkout-button');

                    return;
                }

                this.$http.post('order',
                    {
                        'shippingAddress': this.shippingAddress,
                        'billingAddress': this.billingAddress,
                        'ccName': this.ccName,
                        'ccExpiration': this.ccExpiration,
                        'ccNumber': this.ccNumber,
                        'ccCvv': this.ccCvv,
                    }
                )
                    .catch((error) => {
                        EventBus.$emit('enable-checkout-button');
                    })
                    .then((response) => {
                        EventBus.$emit('update-cart-preview');
                        window.location.href='/checkout-success';
                    })
                    .finally((response) => {

                    });

            },
            luhnCheck(num) {
                let arr = (num + '')
                    .split('')
                    .reverse()
                    .map(x => parseInt(x));
                let lastDigit = arr.splice(0, 1)[0];
                let sum = arr.reduce((acc, val, i) => (i % 2 !== 0 ? acc + val : acc + ((val * 2) % 9) || 9), 0);
                sum += lastDigit;
                return sum % 10 === 0;
            },
            getCart() {
                this.$http.get('/api/cart-preview').then((response) => {
                    if(response.data.payload.quantity === 0) {
                        EventBus.$emit('checkout-no-items-in-cart');
                    }
                }).catch((error) => {
                    alert('an error has occurred');
                }).
                finally(() => {
                    EventBus.$emit('checkout-page-data-loaded');
                });
            },
        }
    }
</script>
