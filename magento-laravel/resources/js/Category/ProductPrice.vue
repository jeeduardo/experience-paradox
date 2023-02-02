<template>
  <span className="price">$ {{ showFormattedPrice() }}</span>
</template>
<script>
  export default {
    props: {
      product: Object
    },
    methods: {
      showFormattedPrice() {
        let formattedPrice = 0;
        if (this.product.price != undefined) {
          formattedPrice = parseFloat(this.product.price).toFixed(2);
        }
        return formattedPrice.toString();
      }
    }
  }
</script>