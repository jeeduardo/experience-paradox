<template>
  <section :id="stepId" className="step">
    <header className="step-header">
      <h3>Cart Summary</h3>
      <div className="step-toggle" @click="showContent">
        <div className="arrow-1"></div>
        <div className="arrow-2"></div>
      </div>
    </header>

    <div :class="contentClass" role="content">
      <div className="minicart-container">
        <div className="minicart-content">
          <div className="item-count-wrapper">
            <div className="item-count">
              <span className="count">{{ formatInt(cart().items_qty) }}</span>
              <span className="space">&nbsp;</span>
              <span className="sentence">{{ itemsInCartText() }}</span>
            </div>
          </div>
          <div className="minicart-items-wrapper">
            <ol className="minicart-items">

              <li v-for="(cartItem, index) in cart().cart_items" className="minicart-item cart-item flex">
                <CartItem :cartItem="cartItem"></CartItem>
              </li>

              <li v-for="(totalSegment, index) in cart().total_segments" className="total-segment-list">
                <TotalSegment :totalSegment="totalSegment" />
              </li>
            </ol>
          </div>

          <div>
            <button className="proceed-btn" @click="goToNextStep">Proceed</button>
          </div>

        </div>

      </div>

      <div :class="getSpinnerClass()">
        <div className="spinner">
          <img src="/images/spinner-200px.gif">
        </div>
      </div>
    </div>

  </section>
</template>
<script>
  import CartItem from './CartItem.vue';
  import TotalSegment from './TotalSegment.vue';
  import NumberFormat from './../utils/NumberFormat.js';

  const { formatInt } = NumberFormat;
  export default {
    inject: [
      'cart',
      'stepToShow',
      'setStepToShow'
    ],
    props: ['stepId', 'showFlag'],
    components: {
      CartItem,
      TotalSegment
    },
    data() {
      return {
        formatInt
      }
    },
    methods: {
      showContent(e) {
        this.$emit('showContent', this);
      },
      itemsInCartText() {
        if (this.cart().items_qty > 1) {
          return 'items in cart';
        }
        return 'item in cart';
      },
      goToNextStep(e) {
        e.preventDefault();
        // show shipping address step
        this.setStepToShow('shipping');
      },
      getSpinnerClass() {
        return 'spinner-container hidden';
      }
    },
    computed: {
      contentClass() {
        let contentClass = 'cart-summary step-content hidden';
        if (this.showFlag) {
          contentClass = 'cart-summary step-content';
        }
        return contentClass;
      }
    }
  }
  </script>