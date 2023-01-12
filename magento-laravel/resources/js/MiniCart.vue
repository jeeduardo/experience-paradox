<template>
  <div id="minicart-container" :class="{'block hidden': !shouldShowMiniCart(),  'block': shouldShowMiniCart()}">
    <h2 className="text-center heading font-bold" v-if="cart().items_qty != undefined">
      My Cart
    </h2>
    <h2 className="text-center heading font-bold" v-if="cart().items_qty == undefined">
      You have no items in your cart.
    </h2>
    <ol className="cart-list">
      <!-- for loop -->
      <li v-for="(cartItem, index) in cart().cart_items" className="cart-item">
        <MiniCartItem :cartItem="cartItem" @removeFromCart="emitDelete" />
      </li>

    </ol>
    <div :class="{'spinner-container hidden': !ajaxInProgress(), 'spinner-container': ajaxInProgress()}">
      <div className="spinner">
        <img src="/images/spinner-200px.gif" style="height: 200px; width: 200px; position: absolute; border: 1px solid #000; top: 30%;" />
      </div>
    </div>

    <div className="cart-totals">
      <div className="cart-subtotal">
        <span className="font-semibold">Subtotal</span>
      </div>
      <div className="cart-subtotal-value font-semibold">
        <span className="currency">$</span>
        <span className="price">{{ formatTotal(cart().subtotal) }}</span>
      </div>
    </div>

    <div className="cart-main-actions" v-if="cart().items_qty && cart().items_qty > 0">
      <div className="primary">
        <button className="btn btn-checkout" @click="checkout">Checkout</button>
      </div>
    </div>


  </div>

</template>
<script>
  import MiniCartItem from './MiniCart/CartItem.vue';
  export default {
    inject: [
      'cart',
      'shouldShowMiniCart',
      'ajaxInProgress',
    ],
    components: {
      MiniCartItem
    },
    props: {
    },
    data() {
      const cartData = this.cart();
      let itemsQty = 0;
      if (cartData.items_qty != undefined) {
        itemsQty = cartData.items_qty;
      }

      return {
        cartData,
        itemsQty
      }
    },
    methods: {
      checkout(e) {
        e.preventDefault();
        // go to checkout
        window.location.href = '/checkout';
      },
      emitDelete(cartItem) {
        this.$emit('removeFromCart', cartItem);
      },
      formatTotal(total) {
        return parseFloat(total).toFixed(2);
      },
    },
    computed: {
      shouldWeShowMiniCart() {
        console.log('App.vue :: MiniCart.vue :: computed shouldWeShowMiniCart()', this.shouldShowMiniCart());
        return this.shouldShowMiniCart();
      }
    }
  }
</script>