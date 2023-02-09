<template>
  <div className="step-container">
    <h1 style="display: none;">Checkout</h1>
    <h3 className="estimated-total">Estimated Total <Price :price="cart.grand_total" /></h3>

    <div className="step-content">
      <CartSummaryStep
        :stepId="stepIds['cart-summary']"
        :showFlag="showFlags()['cart-summary']"
        @showContent="showStepContent" />
      <ShippingStep
        :stepId="stepIds['shipping']"
        :showFlag="showFlags()['shipping']"
        @showContent="showStepContent"
        :step="step"
        @showBillingAddressStep="showBillingAddressStep" />
      <BillingAddressStep
        :stepId="stepIds['billing-address']"
        :showFlag="showFlags()['billing-address']"
        @showContent="showStepContent"
        v-if="!sameAsBilling" />
      <ShippingMethodStep
        :stepId="stepIds['shipping-method']"
        :showFlag="showFlags()['shipping-method']"
        @showContent="showStepContent" />
      <PaymentMethodStep
        :stepId="stepIds['payment-method']"
        :showFlag="showFlags()['payment-method']"
        @showContent="showStepContent" @placeOrder="placeOrder" />
    </div>
  </div>

  <!-- @TODO: DELETE THIS -->
  <aside :class="{'sidebar hidden': hideMinicart, 'sidebar': !hideMinicart}">
    <div className="modal-wrap">
    </div>
  </aside>

</template>
<script>
import Price from './utils/Price.vue';
import Quantity from './utils/Quantity.vue';
import CartSummaryStep from './Checkout/CartSummaryStep.vue';
import ShippingStep from './Checkout/ShippingStep.vue';
import BillingAddressStep from './Checkout/BillingAddressStep.vue';
import ShippingMethodStep from './Checkout/ShippingMethodStep.vue';
import PaymentMethodStep from './Checkout/PaymentMethodStep.vue';

export default {
  inject: ['cart', 'stepIds', 'showFlags', 'setShowFlagForStep', 'setStepToShow'],
  components: {
    Price,
    Quantity,
    CartSummaryStep,
    ShippingStep,
    BillingAddressStep,
    ShippingMethodStep,
    PaymentMethodStep
  },
  props: {
    cart: Object,
    addresses: Object,
  },
  data() {
    let hideMinicart = true;
    let step = 'cart-summary';

    // Set a default flag for sameAsBilling
    // To determine if we'll hide billing step initially or not...
    let sameAsBilling = false;
    if (this.addresses.length > 0) {
      const firstAddress = this.addresses[0];
      if (firstAddress.address_type == 'shipping' && firstAddress.same_as_billing) {
        sameAsBilling = true;
      }
    }

    const stepIds = this.stepIds();
    // const showFlags = this.showFlags();

    return {
      stepIds,
      hideMinicart,
      step,
      sameAsBilling
    }
  },
  methods: {
    // Method to show content of cart summary/shipping address/billing address/shipping method/payment step
    // Triggered when showContent custom event is emitted by any of the steps above (CartSummaryStep, ShippingStep, etc..)
    showStepContent(param) {
      console.log('Checkout.vue :: showStepContent', param.stepId, param.showFlag, this.stepIds[param.stepId]);
      // @todo: my technique for automatically showing Shipping address > Billing address > Shipping Address > Payment method
      // let showFlagSteps = Object.keys(this.showFlags);
      // showFlagSteps.forEach((key, index) => {
      //   this.showFlags[key] = false;
      // });

      // this.showFlags[param.stepId] = !param.showFlag;
      this.setShowFlagForStep(param.stepId, !param.showFlag);
      param.arrowClicked = !param.arrowClicked;
    },
    // Method to show/hide the billing address step
    // Based on given sameAsBilling flag
    // (If "I have the same billing address." is NOT checked, this step will show up)
    showBillingAddressStep(sameAsBilling) {
      this.sameAsBilling = sameAsBilling;
    },
    // Place order
    placeOrder(payload) {
      console.log('Checkout.vue :: placeOrder', payload, this.stepIds, this.cart);

      const placeOrderUrl = '/checkout/' + this.cart.cart_token + '/order';

      const axios = window.axios;
      axios.post(placeOrderUrl, payload).then(response => {
        if (response.data) {
          const { status, order_id, increment_id } = response.data;
          // Redirect to success page
          window.location.pathname = '/checkout/success';
        }
      });
    }
  }
}
</script>
