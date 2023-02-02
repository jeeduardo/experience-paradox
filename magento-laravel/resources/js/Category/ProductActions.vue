<template>
  <div className="product-actions w-full">
    <button className="btn btn-primary" @click="emitAddToCart">Add to Cart</button>
  </div>
</template>
<script>
  export default {
    props: {
      product: Object
    },
    methods: {
      emitAddToCart(e) {
        e.preventDefault();
        this.$emit('addToCart', this.product);
      }
    }
  }
</script>