<template>
  <div className="step-container">
    <h1>Checkout</h1>
    <h3>Shipping / Billing step here</h3>
  </div>

  <aside className="sidebar">
    <div className="modal-wrap">
      <div className="minicart-container">

        <div className="minicart-content">

          <header className="minicart-header">
            <h3 className="minicart-title">Order Summary</h3>
            <button className="close-button">
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
<script src="./Checkout/CheckoutVm.js" lang="ts"></script>
