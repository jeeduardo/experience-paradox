<template>
  <section id="billing-address" className="step">
    <header className="step-header">
      <h3>Billing</h3>
      <div className="step-toggle" @click="showContent">
        <div className="arrow-1"></div>
        <div className="arrow-2"></div>
      </div>
    </header>

    <div :class="getContentClass()" role="content">
      <BillingAddressForm />
    </div>
  </section>
</template>
<script>
  import BillingAddressForm from './BillingAddressStep/BillingAddressForm.vue';
export default {
  inject: [
    'stepToShow',
    'setStepToShow',
    'cart',
    'addresses',
    'paymentMethods',
    'ajaxInProgress',
    'setAjaxInProgress'
  ],
  props: {
    step: String,
  },
  components: {
    BillingAddressForm
  },
  data() {
    let arrowClicked = false;
    let stepId = 'billing-address';
    return {
      arrowClicked,
      stepId
    }
  },
  methods: {
    showContent(e) {
      this.$emit('showContent', this);
    },
    getContentClass() {
      let contentClass = 'billing-address step-content hidden';
      if (this.stepToShow() == this.stepId) {
        this.arrowClicked = true;
        this.setStepToShow('');
      }

      if (this.arrowClicked) {
        contentClass = 'billing-address step-content';
      }
      return contentClass;
    }
  },
  computed: {
  }
}
</script>