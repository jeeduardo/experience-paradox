<template>
  <section :id="stepId" className="step">
    <header className="step-header">
      <h3>Shipping Method</h3>
      <div className="step-toggle" @click="showContent">
        <div className="arrow-1"></div>
        <div className="arrow-2"></div>
      </div>
     </header>

    <div :class="contentClass" role="content" style="position: relative;">
      <ul>
        <li>Shipping methods here</li>
        <li v-for="(shippingMethod, index) in shippingMethods()"
            :shippingmethod="shippingMethod.code">
          <ShippingMethod
            :shippingMethod="shippingMethod"
            :selectedMethod="selectedMethod"
            @selectMethod="selectMethod" />
        </li>
        <li>
          <button className="proceed-btn" @click="goToNextStep">Proceed</button>
        </li>
      </ul>

      <div :class="getSpinnerClass()">
        <div className="spinner">
          <img src="/images/spinner-200px.gif">
        </div>
      </div>
    </div>

  </section>
</template>
<script>
  // Vue JS file for a shipping method to be rendered
  import ShippingMethod from './ShippingMethod.vue';
  export default {
    // Use the data/functions coming from App.vue's "provide" option
    inject: [
      'stepToShow',
      'setStepToShow',
      'cart',
      'addresses',
      'shippingMethods',
      'setPaymentMethods',
      'ajaxInProgress',
      'setAjaxInProgress'
    ],
    components: {
      ShippingMethod
    },
    data() {
      // If false, shipping method step content is hidden
      let arrowClicked = false;
      // For selected shipping method
      let selectedMethod = '';
      // For <section> id attribute, and
      // for automatically showing up shipping method's
      // After shipping/billing addresses are filled up
      let stepId = 'shipping-method';
      return {
        stepId,
        arrowClicked,
        selectedMethod
      }
    },
    methods: {
      showContent(e) {
        this.$emit('showContent', this);
      },
      selectMethod(methodComponent) {
        // Update selected method and fetch available payment methods...
        this.selectedMethod = methodComponent.shippingMethod.code;
        this.getPaymentMethods(methodComponent.shippingMethod);
      },
      getSpinnerClass() {
        if (this.ajaxInProgress()) {
          return 'spinner-container';
        }
        return 'spinner-container hidden';
      },
      goToNextStep(e) {
        e.preventDefault();
      },
      getPaymentMethods(shippingMethod) {
        this.setAjaxInProgress(true);

        const saveShippingMethodUrl = '/checkout/shipping-method';
        const address_id = this.addresses()[0].id;
        const shipping_method = shippingMethod.method;
        const shipping_carrier_code = shippingMethod.carrier;
        const shipping_description = shippingMethod.method_title;
        let payload = {
          address_id,
          shipping_method,
          shipping_description,
          shipping_carrier_code
        };
        axios.post(saveShippingMethodUrl, payload).then(response => {
          const { payment_methods, totals } = response.data;
          this.setPaymentMethods(payment_methods);
        }).finally(() => { this.setAjaxInProgress(false) });
      }
    },
    computed: {
      contentClass() {
        let contentClass = 'shipping-method step-content hidden';
        if (this.stepToShow() == this.stepId) {
          this.arrowClicked = true;
          this.setStepToShow('');
        }

        if (this.arrowClicked) {
          contentClass = 'shipping-method step-content';
        }
        return contentClass;

      }

    }
  }
</script>