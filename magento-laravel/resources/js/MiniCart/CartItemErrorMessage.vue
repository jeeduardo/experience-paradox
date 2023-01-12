<template>
  <div className="sorry">
    <span>Sorry, cannot checkout this product.</span><br/>
    <span>Reason: {{ errorCause }}</span>
  </div>
</template>
<script>
  export default {
    props: {
      cartItem: Object
    },
    data() {
      const errorCauses = {
        'OOS': 'Product is out of stock :(',
        'DISABLED': 'Product has been discontinued.',
        'ERROR': 'Server error'
      }

      let errorCause = 'Cannot determine. Please contact administrator.';
      if (this.cartItem.failure_code != undefined) {
        errorCause = errorCauses[this.cartItem.failure_code];
      }

      return {
        errorCause
      }
    },
    showErrorCause() {
    },
    method: {
    }
  }
</script>