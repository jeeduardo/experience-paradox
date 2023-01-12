<template>

  <div className="cart-item-wrapper w-full">
    <div className="cart-item-img image-container">
      <img src="/images/cart.svg" alt="product-name"/>
    </div>
    <div className="cart-info">
      <div className="cart-info-row">
        <strong className="product-name">{{ cartItem.id }}</strong>
      </div>
      <div className="cart-info-row">
        <div className="qty">
          <span>Quantity </span>
          <span className="qty-value">1</span>
        </div>
      </div>
      <div className="cart-info-row">
        <div className="price-info">
          <span className="currency">$</span>
          <span className="price">99.99</span>
        </div>
      </div>
    </div>
  </div>

</template>
<script>
  export default {
    props: {
      cartItem: Object
    }
  }
  </script>