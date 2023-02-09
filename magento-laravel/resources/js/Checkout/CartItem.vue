<template>

  <div className="cart-item-wrapper w-full">
    <div className="cart-item-img image-container">
      <img src="/images/cart.svg" alt="product-name"/>
    </div>
    <div className="cart-info">
      <div className="cart-info-row">
        <strong className="product-name">{{ cartItem.name }}</strong>
        <div className="price-info">
          <Price :price="cartItem.row_total" />
      </div>
      </div>
      <div className="cart-info-row">
        <div className="qty">
          <span>Quantity </span>
          <Quantity :quantity="cartItem.qty" />
        </div>
      </div>
      <div className="cart-info-row">
      </div>
    </div>
  </div>

</template>
<script>
  import Quantity from '../utils/Quantity.vue';
  import Price from '../utils/Price.vue';
  export default {
    components: {
      Quantity,
      Price
    },
    props: {
      cartItem: Object
    }
  }
  </script>