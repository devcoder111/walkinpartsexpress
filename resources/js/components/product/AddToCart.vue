<template>
    <div>
        <div class="mt-2 inline-block relative w-64 content-width">
            <span class="leading-loose font-bold mb-2 block inline-block">Quantity</span>
            <input class="content-width cart-btn inline-block" type="number" name="quantity" min="1" max="5" v-model="quantity">
        </div>
        <button
                @click="addToCart()"
                type="button"
                class="conent-btn content-width flex-no-shrink bg-blue-dark hover:bg-blue-darker border-blue-dark hover:border-blue-darker text-sm border-4 uppercase text-white font-bold py-1 mx-0 px-2 h-12 rounded cursor-pointer">
            Add to Cart
        </button>
    </div>
</template>

<script>
    import {EventBus} from "../../event-bus";
    import swal from 'sweetalert2';

    export default {
        data: function () {
            return {
                quantity: "1"
            };
        },
        props: ['productId'],
        computed: {},
        mounted() {

        },
        methods: {
            addToCart() {
                var message = "";
                var title = "";


                if(this.quantity == "" || this.quantity == 0) {
                    message = "error";
                    title = "Please enter a quantity great than zero";
                    this.noficationMsg(message, title);
                    return;
                }

                this.$http.post(`/add-to-cart/product/${this.productId}/quantity/${this.quantity}`)
                    .then((response) => {
                        //TODO come back and add a check against response for success == true and;  throw exception if not, and add a .catch to promise
                        message = 'success';
                        title = "Item added to cart!";

                       if(response.data.success == "false") {
                           message = 'error'; 
                           title = response.data.title; 
                        }
                        
                        this.noficationMsg(message, title);

                    });
            },
            noficationMsg(message, title) {
                EventBus.$emit('update-cart-preview');
                swal.fire({
                    title: title,
                    text: '',
                    type: message,
                    confirmButtonText: 'OK'
                })

            }
        }
    }
</script>
