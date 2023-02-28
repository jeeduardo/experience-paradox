<template>
  <section :id="stepId" className="step">
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
    stepId: String,
    showFlag: Boolean
  },
  components: {
    BillingAddressForm
  },
  methods: {
    showContent(e) {
      this.$emit('showContent', this);
    },
    getContentClass() {
      let contentClass = 'billing-address step-content hidden';

      if (this.showFlag) {
        contentClass = 'billing-address step-content';
      }
      return contentClass;
    }
  },
  computed: {
  }
}
</script>
