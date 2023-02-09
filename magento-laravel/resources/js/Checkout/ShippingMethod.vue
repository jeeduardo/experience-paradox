<template>
  <button :class="getButtonClass()" @click="selectMethod">
    <span>{{ shippingMethod.carrier_title }}</span>
    <span> ({{ showShippingPrice(shippingMethod.price) }})</span>
  </button>
</template>
<script>
  import NumberFormat from "../utils/NumberFormat.js";

  const { formatPrice } = NumberFormat;
  export default {
    inject: ['cart'],
    props: {
      // Following are properties passed from ShippingMethodStep.vue
      // shippingMethod -  subject shipping method to be rendered
      // selectedMethod - the selected shipping method for the user (for CSS purposes)
      shippingMethod: Object,
      selectedMethod: String,
    },
    data() {
      let selected = false;
      return {
        formatPrice,
        selected
      }
    },
    methods: {
      // Emit selectMethod custom event when the shipping method is selected
      selectMethod(e) {
        this.$emit('selectMethod', this);
      },
      // Function to determine the CSS class
      // of the shipping method button we're rendering (see <button :class... above)
      getButtonClass() {
        if (this.selectedMethod == this.shippingMethod.code) {
          return 'shipping-method-btn active';
        }
        return 'shipping-method-btn'

      },
      showShippingPrice(shippingMethodPrice) {
        if (shippingMethodPrice > 0) {
          return 'costs additional $' + this.formatPrice(shippingMethodPrice);
        }
        return 'as in free!';
      }
    }
  }
</script>