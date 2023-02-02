<template>
  <span className="currency">{{ currency }}</span>
  <span className="price">{{ formatPrice(price) }}</span>
</template>
<script>
  import NumberFormat from './NumberFormat.js';
  const { currency, formatPrice } = NumberFormat;
  export default {
    props: {
      price: String
    },
    data() {
      return {
        currency
      }
    },
    methods: {
      formatPrice
    }
  }
</script>