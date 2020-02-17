<template>
    <div>
        <div v-if="images">
            <ProductZoomer
                    :base-images="images"
                    :base-zoomer-options="zoomerOptions"/>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                images: null,
                zoomerOptions: {
                    'zoomFactor': 2, // scale for zoomer
                    'pane': 'container', // three type of pane ['pane', 'container-round', 'container']
                    'hoverDelay': 300, // how long after the zoomer take effect
                    'namespace': 'zoomer', // add a namespace for zoomer component, useful when on page have mutiple zoomer
                    'move_by_click': false, // move image by click thumb image or by mouseover
                    'scroll_items': 5, // thumbs for scroll
                    'choosed_thumb_border_color': "#bbdefb", // choosed thumb border color
                    'move_button_style': 'chevron',// default chevron , can be set to angle-double
                },
            };
        },
        props: ['productId'],
        computed: {},
        mounted() {
            this.$http.get(`/product-image/${this.productId}`)
                .then((response) => {
                    this.images =  response.data;
                });
        },
        methods: {

        }
    }
</script>