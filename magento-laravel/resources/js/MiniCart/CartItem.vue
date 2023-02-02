<template>
  <div className="cart-item-wrapper">
    <div className="cart-item-img">
      <a href="#">
        <img src="/images/cart.svg" height="100" width="100" />
      </a>
    </div>
    <div className="cart-info">
      <div className="cart-info-row">
        <div className="product-name">{{ cartItem.name }}</div>
        <div className="price-info">
          <span className="currency">$</span>
          <span className="price">{{ formatPrice(cartItem.price) }}</span>
        </div>
      </div>
      <div className="cart-info-row">
        <div className="qty">
          <span>Quantity </span>
          <span className="qty-value">{{ formatInt(cartItem.qty) }}</span>
        </div>
      </div>
      <CartItemErrorMessage :cartItem="cartItem" v-if="itemCannotCheckout()" />
      <div className="cart-info-row right">
        <div className="actions">
          <a href="#"
             className="action update"
             v-if="!itemCannotCheckout()">
            <img src="/images/edit-cart-item.svg" />
          </a>
          <a href="#"
             className="action delete" @click="emitDelete">
            <img src="/images/delete-cart-item.svg" />
          </a>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  import CartItemErrorMessage from './CartItemErrorMessage.vue';
  import NumberFormat from './../utils/NumberFormat.js';

  const { formatInt, formatPrice } = NumberFormat;
  export default {
    components: {
      CartItemErrorMessage
    },
    props: {
      cartItem: Object
    },
    data() {
      return {
        formatPrice,
        formatInt
      }
    },
    methods: {
      emitDelete(e) {
        e.preventDefault();
        this.$emit('removeFromCart', this.cartItem);
      },
      itemCannotCheckout() {
        return (this.cartItem.has_failed);
      }
    }
  }
</script>