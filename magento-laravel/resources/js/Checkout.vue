<template>
  <div className="step-container">
    <h1 style="display: none;">Checkout</h1>
    <h3 className="estimated-total">Estimated Total <Price :price="cart.grand_total" /></h3>
    <button className="minicart-count" href="#" @click="showTheMinicart"><Quantity :quantity="cart.items_qty" /></button>

    <div className="step-content">
      <CartSummaryStep @showContent="showStepContent" />
      <ShippingStep
        @showContent="showStepContent"
        :step="step"
        @showBillingAddressStep="showBillingAddressStep" />
      <BillingAddressStep @showContent="showStepContent" v-if="!sameAsBilling" />
      <ShippingMethodStep @showContent="showStepContent" />
    </div>
  </div>

  <aside :class="{'sidebar hidden': hideMinicart, 'sidebar': !hideMinicart}">
    <div className="modal-wrap">
      <div className="minicart-container">

        <div className="minicart-content">

          <header className="minicart-header">
            <h3 className="minicart-title">Order Summary</h3>
            <button className="close-button" @click="hideTheMinicart">
              <span className="close-bar" style="transform: rotate(45deg);"></span>
              <span className="close-bar" style="transform: rotate(-45deg); position: relative; top: -4px;"></span>
            </button>
          </header>
          <div className="item-count-wrapper">
            <div className="item-count">
              <span className="count">1</span>
              <span className="space">&nbsp;</span>
              <span className="sentence">item in cart</span>
            </div>
          </div>
          <div className="minicart-items-wrapper">
            <ol className="minicart-items">

              <li v-for="(cartItem, index) in cart.cart_items" className="minicart-item cart-item flex">
                <CartItem :cartItem="cartItem"></CartItem>
              </li>

            </ol>
          </div>

        </div>

      </div>
    </div>
  </aside>

</template>
<script>
import CartItem from './Checkout/CartItem.vue';
import Price from './utils/Price.vue';
import Quantity from './utils/Quantity.vue';
import CartSummaryStep from './Checkout/CartSummaryStep.vue';
import ShippingStep from './Checkout/ShippingStep.vue';
import BillingAddressStep from './Checkout/BillingAddressStep.vue';
import ShippingMethodStep from './Checkout/ShippingMethodStep.vue';

export default {
  provides: {
    hideMinicart: () => this.hideMinicart,
    hideTheMinicart: (e) => { this.hideMinicart = true; }
  },
  components: {
    CartItem,
    Price,
    Quantity,
    CartSummaryStep,
    ShippingStep,
    BillingAddressStep,
    ShippingMethodStep
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

    return {
      hideMinicart,
      step,
      sameAsBilling
    }
  },
  methods: {
    hideTheMinicart(e) {
      this.hideMinicart = true;
    },
    showTheMinicart(e) {
      e.preventDefault();
      this.hideMinicart = false;
    },
    // Method to show content of cart summary/shipping address/billing address/shipping method/payment step
    // Triggered when showContent custom event is emitted by any of the steps above (CartSummaryStep, ShippingStep, etc..)
    showStepContent(param) {
      param.arrowClicked = !param.arrowClicked;
    },
    // Method to show/hide the billing address step
    // Based on given sameAsBilling flag
    // (If "I have the same billing address." is NOT checked, this step will show up)
    showBillingAddressStep(sameAsBilling) {
      this.sameAsBilling = sameAsBilling;
    }
  }
}
</script>