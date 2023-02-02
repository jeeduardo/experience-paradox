<template>
  <section id="shipping" className="step">
    <header className="step-header">
      <h3>Shipping</h3>
      <div className="step-toggle" @click="showContent">
        <div className="arrow-1"></div>
        <div className="arrow-2"></div>
      </div>
    </header>

    <div :class="contentClass" role="content" style="position: relative;">
      <ShippingAddressForm @afterSaveShippingAddress="hideShippingStep" @showBillingAddressStep="showBillingAddressStep" />
    </div>
  </section>
</template>
<script>
  // Vue JS file for the shipping address form above
  import ShippingAddressForm from './ShippingStep/ShippingAddressForm.vue';
  export default {
    components: {
      // Have to declare the "ShippingAddressForm" component or else it won't show up above
      ShippingAddressForm
    },
    emits: ['showContent', 'showBillingAddressStep'],
    props: {
      step: String,
    },
    data() {
      // If false, it will not show the step's content
      let arrowClicked = false;
      return {
        arrowClicked
      }
    },
    methods: {
      getContentClass() {
        if (this.step != undefined && this.step == 'shipping') {
          return 'shipping step-content';
        }
        return 'shipping step-content hidden';
      },
      // Show shipping step content
      showContent(e) {
        // We emit a "showContent" event back to Checkout.vue
        this.$emit('showContent', this);
      },
      // Method to hide the shipping step
      // This gets triggered from custom emit event "afterSaveShippingAddress"
      hideShippingStep() {
        this.arrowClicked = false;
      },
      // Emit showBillingAddressStep custom event (coming from ShippingAddressForm)
      showBillingAddressStep(sameAsBilling) {
        this.$emit('showBillingAddressStep', sameAsBilling);
      }
    },
    computed: {
      contentClass() {
        let contentClass = 'shipping step-content hidden';
        if (this.arrowClicked) {
          contentClass = 'shipping step-content';
        }
        return contentClass;
      }
    }
  }
  </script>