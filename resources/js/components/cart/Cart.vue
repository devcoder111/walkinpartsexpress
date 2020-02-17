<template>
    <div>
        <loading :active.sync="isLoading"
                 :can-cancel="false"
                 :is-full-page="fullPage"
                    :height="125"
                    :width="125"></loading>
        <div class="text-left" v-if="cart">
            <div v-if="cart.cart_products.length === 0" class="text-center my-12 text-blue-darker text-2xl">
                Your cart is empty!
            </div>
            <div v-else>
                <div class="bg-grey-light text-2xl md:text-3xl lg:text-4xl text-blue-darkest pt-6 pb-8 font-bold pl-8">
                    Your <span class="border-b-4 border-blue-dark pb-2">Cart</span>
                </div>
                <!-- Cart Row -->
                <div class="text-blue-darkest mt-10 pb-6 font-extrabold pl-8">
                    <div v-if="cart.cart_products" class="flex flex-row pt-4 font-normal mb-6">
                        <div class="w-full md:w-3/5">&nbsp;</div>
                        <div class="w-full md:w-1/5">Price</div>
                        <div class="w-full md:w-1/5">Quantity</div>
                    </div>
                    <div v-for="cp in cart.cart_products" class="flex flex-wrap mb-6">
                        <div class="w-full md:w-3/5 text-lg text-blue-darker font-bold h-auto">
                            <div class="flex flex-wrap">
                                <div class="w-1/5">
                                    <div class="px-6">
                                        <img class="border border-black h-auto w-auto" v-if="awsS3BasePath"
                                             :src="getImageThumbnail(cp.product)">
                                    </div>
                                </div>
                                <div class="w-4/5">
                                    <div>
                                        <a class="no-underline text-blue-dark hover:text-blue-darker"
                                           :href="`/product/${cp.product.id}`">{{ cp.product.description }}</a>
                                    </div>
                                    <div class="mt-4">
                                        <a class="text-xs no-underline text-blue-dark hover:text-blue-darker cursor-pointer"
                                           @click="deleteCartProduct(cp.id)">Delete</a>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="w-full md:w-1/5">
                            {{ cp.product.price | currency }}
                        </div>
                        <div class="w-full md:w-1/5">
                            {{ cp.quantity }}
                        </div>
                    </div>
                </div>
                <div class="w-full flex flex-wrap justify-end">
                    <div class="p-10  font-bold text-lg" v-if="cart">Subtotal: &nbsp;<span class="text-red-dark">{{ subtotal | currency }}</span>
                    </div>
                    <div class="p-10">
                        <button @click="checkoutClick" type="button"
                                class="flex-no-shrink bg-blue-dark hover:bg-blue-darker border-blue-dark hover:border-blue-darker text-sm border-4 uppercase text-white font-bold py-1 mx-0 px-2 h-12 rounded">
                            Continue to Checkout
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {EventBus} from "../../event-bus";
    import swal from 'sweetalert2';
    // Import component
    import Loading from 'vue-loading-overlay';
    // Import stylesheet
    import 'vue-loading-overlay/dist/vue-loading.css';

    export default {
        data: function () {
            return {
                awsS3BasePath: null,
                cart: null,
                isLoading: false,
                fullPage: true,
            };
        },
        components: {
            Loading
        },
        props: ['loggedIn'],
        computed: {
            subtotal() {
                let total = 0;

                if (this.cart.cart_products.length > 0) {
                    this.cart.cart_products.map((cp, key) => {
                        total += cp.product.price * cp.quantity;
                    });
                }

                return total;
            }
        },
        mounted() {
            this.$http.get('/get-aws-s3-base-path')
                .then((response) => {
                    this.awsS3BasePath = response.data.AWS_S3_BASEPATH;

                    this.getCartData();
                }).finally(() => {
                    //this.isLoading = false;
                });


        },
        methods: {
            getImageThumbnail(product) {
                if (product.images.length > 0) {
                    return `${this.awsS3BasePath}/${product.images[0].image_thumbnail.file_path}`;
                }

                return `${this.awsS3BasePath}/images/no-product-image.jpg`;
            },
            deleteCartProduct(cartProductId) {
                this.isLoading = true;

                this.$http.delete(`/cart-product/${cartProductId}`)
                    .then((response) => {
                        if (response.data.success === true) {
                            swal.fire({
                                title: 'Item deleted from cart!',
                                text: '',
                                type: 'success',
                                confirmButtonText: 'OK'
                            });

                            this.getCartData();
                            EventBus.$emit('update-cart-preview');
                        } else {
                            throw new Error(response.data && response.data.payload ? response.data.payload.error : '');
                        }
                    })
                    .catch((error) => {
                        swal.fire({
                            title: 'An error has occurred.',
                            text: error !== "Error" ? error : '',
                            type: 'error',
                        });

                        this.getCartData();
                        EventBus.$emit('update-cart-preview');
                    })
                    .finally(() => {
                        this.isLoading = false;
                    });
            },

            getCartData() {
                this.isLoading = true;

                this.$http.get('/get-cart-data')
                    .then((response) => {
                        this.cart = response.data.payload;
                    })
                    .catch((error) => {
                        swal.fire({
                            title: 'An error has occurred.',
                            text: error != "Error" ? error : '',
                            type: 'error',
                        });

                        EventBus.$emit('update-cart-preview');
                    })
                    .finally(() => {
                        this.isLoading = false;
                    });

            },
            checkoutClick() {
                if(this.loggedIn === 1) {
                    return window.location.href = '/checkout';
                }

                return window.location.href = '/pre-checkout';
            }
        }
    }
</script>
