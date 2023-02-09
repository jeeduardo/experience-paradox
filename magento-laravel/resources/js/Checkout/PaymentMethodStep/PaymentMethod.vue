<template>
  <button :class="getButtonClass()" @click="selectPaymentMethod">
    <span>{{ paymentMethod.title }}</span>
    <span> ({{ (grandTotal) }})</span>
  </button>
</template>
<script>
  export default {
    props: ['paymentMethod', 'selectedPaymentMethod', 'grandTotal'],
    methods: {
      // Function to determine the CSS class
      // of the shipping method button we're rendering (see <button :class... above)
      getButtonClass() {
        if (this.selectedPaymentMethod == this.paymentMethod.code) {
          return 'payment-method-btn active';
        }
        return 'payment-method-btn'

      },
      selectPaymentMethod(e) {
        e.preventDefault();
        this.$emit('selectPaymentMethod', this.paymentMethod);
      }
    }
  }
</script>