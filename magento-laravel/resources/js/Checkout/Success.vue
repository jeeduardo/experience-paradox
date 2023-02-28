<template>
  <div class="success-message">
    Thank you for your order, {{ fullname }} ! Your order reference # is {{ order.increment_id }}
    <a class="btn-primary" href="/catalog/gear">Continue to Website</a>
  </div>
</template>
<script>
export default {
  props: {
    order: Object,
    fullname: String
  },
  data() {
    return {

    }
  }
}
</script>