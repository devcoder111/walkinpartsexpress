<template>
    <!-- <div class="mx-auto">
        <div class="justify-content-md-center align-items-center pb-16">
            <div class="md:w-1/2 sm:w-full md:mx-auto pl-3 pr-3">
                <div class="w-full">
                    <div class="text-2xl md:text-3xl lg:text-4xl text-left mt-12 mb-12 font-bold">
                        <span class="border-b-4 border-blue-dark pb-2"></span>
                    </div>

                    <div class="card-body">
                        <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" method="POST" >
                            <div class="mb-4">
                                <label for="firstName" class="block text-grey-darker text-sm font-bold mb-2">* FirstName</label>
                                <input id="firstName" type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline" name="firstName" required autocomplete="current-firstName">
                            </div>
                            <div class="mb-4">
                                <label for="lastName" class="block text-grey-darker text-sm font-bold mb-2">* LastName</label>
                                <input id="lastName" type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline" name="lastName" required autocomplete="current-lastName">
                            </div>
                            <div class="mb-4">
                                <label for="address1" class="block text-grey-darker text-sm font-bold mb-2">* Address1</label>
                                <input id="address1" type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline" name="address1" required autocomplete="current-address1">
                            </div>
                             <div class="mb-4">
                                <label for="address2" class="block text-grey-darker text-sm font-bold mb-2">* Address2</label>
                                <input id="address2" type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline" name="address2" required autocomplete="current-address2">
                            </div>
                           
                             <div class="mb-4">
                                <label for="state" class="block text-grey-darker text-sm font-bold mb-2">* State</label>
                                <div class="relative">
                                    <select v-model="state" class="block appearance-none w-full bg-grey-lighter border border-grey-lighter text-grey-darker py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-grey"
                                            id="grid-state" v-if="states" >
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
                            <div class="mb-4">
                                <label for="zip-code" class="block text-grey-darker text-sm font-bold mb-2">* Zip Code</label>
                                <input id="zip-code" type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline" name="zip-code" required autocomplete="current-zip-code">
                            </div>
                            <div class="flex items-center justify-between">
                                <button type="submit" class="bg-blue hover:bg-blue-dark text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    Save
                                </button>
                                <button type="submit" class="bg-blue hover:bg-blue-dark text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    back
                                </button>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <div class="table-conent w-full md:w-full xl:w-3/4 md:mx-auto px-4">
        <table cellspacing="0" class="table-case w-full text-sm md:text-base">
            <thead>
                <tr class="h-12 uppercase bg-grey-light">
                    <th class="text-left">Submitted</th> 
                    <th class="text-left">ID</th> 
                    <th class="text-left">Payment</th>
                    <th class="text-left">Pay Status</th>
                    <th class="text-left">Quantity</th>
                    <th class="text-left">Total</th>
                    <th class="text-left">View</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="data in orderStatusData" style="text-align:center;">
                    <td class="text-left">{{data.created_at}}</td>
                    <td class="text-left">{{data.id}}</td>
                    <td class="text-left">{{data.payment_gateway.name}}</td>
                    <td class="text-left">{{data.order_status.order_status}}</td>
                    <td class="text-left">{{data.productQuantity}}</td>
                    <td class="text-left">{{data.productTotalPrice}}</td>
                    <td class="text-left">View</td>
                </tr>
            </tbody>                         
        </table>
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
                orderStatusData: null
            };
        },
        props: ['productId'],
        computed: {},
        mounted() {
            this.getStates();
            this.orderStatus();
        },
        methods: {
            orderStatus() {
                this.$http.get('/api/order-status')
                .then((response) => {
                    this.orderStatusData = response.data.order;
                    
                    this.orderStatusData.forEach(iteam => {
                        
                        var tmpQuantity = 0;
                        var tmpTotalPrice = 0;

                        iteam.order_products.forEach(orderProduct => {
                            tmpQuantity += orderProduct.quantity;
                            tmpTotalPrice += orderProduct.quantity * orderProduct.price_paid_per_unit;
                        });
                        iteam.productQuantity = tmpQuantity;
                        iteam.productTotalPrice = tmpTotalPrice;
                    });

                });
            },
            getStates() {
                this.$http.get('/state')
                .then((response) => {
                    this.states = response.data.payload;
                })
            },
        }
    }
</script>
