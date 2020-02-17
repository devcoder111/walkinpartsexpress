<template>
    <div class="ml-6 flex-row flex" v-if="cartLoaded">
        <a class="no-underline" href="/cart">
            <div class="overflow-hidden">
                <div class="block block-commerce-cart">
                    <div class="content">
                        <div class="block-cart-alter-content">
                            <div class="block-cart-alter-content-top dropdown">
                                <div>Your cart</div>
                                <span class="font-bold text-blue-darker">{{ cart.total | currency }}</span>
                                <span class="user-cart-quantity font-bold">{{ cart.quantity }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</template>

<script>
    import { EventBus } from "../../event-bus";


    export default {
        data: function () {
            return {
                cart: {'total': null, 'quantity': null},
                cartLoaded: false,
            };
        },
        computed: {},
        mounted() {
            this.getCart();

            let that = this;

            EventBus.$on('update-cart-preview', function () {
                that.getCart();
            });
        },
        methods: {
            getCart() {
                this.$http.get('/api/cart-preview').then((response) => {
                    this.cart.total = response.data.payload.total;
                    this.cart.quantity = response.data.payload.quantity;
                    this.cartLoaded = true;
                });
            }
        }
    }
</script>