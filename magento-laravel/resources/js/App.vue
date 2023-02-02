<template>

  <header class="header">
    <div class="header-items">
      <input type="checkbox" id="burger-toggle" name="burger-toggle">
        <label id="burger-bread" for="burger-toggle" class="for-menu">
          <div class="burger"></div>
          <div class="burger burger-2"></div>
          <div class="burger"></div>
        </label>

        <div class="brand header-item">
          <a href="/catalog/1">
            <img src='../images/logo.svg' className="logo" />
          </a>
        </div>

        <div class="right-links header-item">
          <div class="minicart">
            <a href="#" class="cart-link" @click="toggleShowMiniCart">
              <span class="cart-total-qty">{{ formatInt(cart.items_qty) }}</span>
            </a>
          </div>

          <div :class="{'spinner-container hidden': !ajaxInProgress, 'spinner-container': ajaxInProgress}">
            <div className="spinner">
              <img src="/images/spinner-200px.gif" />
            </div>
          </div>
        </div>

        <Menus :categoryMenus="categoryMenus" />

        <div class="cart-content">
          <div id="mini-cart-app">
            <MiniCart @removeFromCart="removeFromCart" />
          </div>
        </div>
    </div>
  </header>

  <div id="messages" className="messages">
    <div className="alert alert-warning" role="alert" v-for="(message, index) in errorMessages">
      <i className="fa fa-check"></i>
      {{ message }}
    </div>
  </div>

  <div id="catalog-container" v-if="getPathname() != '/checkout'">
    <CategoryView />
  </div>

  <div id="checkout-container" v-if="isCheckout()">
    <Checkout :cart="cart" :addresses="addresses" />
  </div>

</template>
<script>
  // For menus' display / mechanism
  import Menus from './Menus.vue';
  // For mini-cart display / mechanism
  import MiniCart from './MiniCart.vue';
  // Vue JS file for the product catalog
  import CategoryView from './CategoryView.vue';
  // Vue JS for the checkout page
  import Checkout from './Checkout.vue';

  // Fetch the [data-vars] value from the div with id app (div#app)
  const { vars } = document.getElementById('app').dataset;
  // Parse the variables to JSON then get necessary objects (menus, cart, etc..)
  const { categoryMenus, cart, errorMessages, addresses, shippingMethods } = JSON.parse(vars);

  export default {
    components: {
      Menus,
      MiniCart,
      CategoryView,
      Checkout
    },
    provide() {
      // The following will be made accessible to child components (of the children of App.vue, and so forth..)
      // (e.g. the ShippingAddressForm.vue will be able to access the "cart" object
      // without depending on parent component ShippingStep.vue. That Vue JS file
      // will be able to access the "cart" object through "inject")
      // Done to avoid property drilling
      return {
        // function to return whether mini-cart should be shown or hidden
        shouldShowMiniCart: () => this.showMiniCart,
        // function to change "showMiniCart" flag
        setShowMiniCart: (flag) => { this.showMiniCart = flag },
        // function to return which step to show in checkout
        stepToShow: () => this.stepToShow,
        // function to set which step should be shown in checkout
        setStepToShow: (stepToShow) => { this.stepToShow = stepToShow },
        // active cart session object
        cart: () => this.cart,
        // setter function to update cart session object
        setCart: (cart) => { this.cart = cart },
        // set cart items quantity (shown on top right side)
        setCartItemsQty: (itemsQty) => { this.itemsQty = itemsQty },
        // function to return address/es linked to active cart session used
        addresses: () => this.addresses,
        // function to return the shipping method/s available for given
        shippingMethods: () => this.shippingMethods,
        // function to update available shipping method/s (if shipping address was changed...)
        setShippingMethods: (shippingMethods) => { this.shippingMethods = shippingMethods },
        // function to return available payment method/s , under construction...
        paymentMethods: () => this.paymentMethods,
        // function to update available payment methods
        setPaymentMethods: (paymentMethods) => { this.paymentMethods = paymentMethods },
        // function to return whether an AJAX operation is in progress or not
        // if there's one, the AJAX loader gif will appear on the affected part
        // could've been easier if i just did jQuery('#some-loading-gif-div').show()
        // or document.getElementById('some-loading-gif-div').display = 'block'; no?
        ajaxInProgress: () => this.ajaxInProgress,
        // function to update the ajaxInProgress flag (when an AJAX operation is about to start or end)
        setAjaxInProgress: (flag) => { this.ajaxInProgress = flag; },
      }
    },
    data() {
      let showBurgerMenu = false;
      let ajaxInProgress = false;
      let showMiniCart = false;
      const axios = window.axios;
      let stepToShow = '';
      // const categoryMenus = categoryMenus;

      let itemsQty = 0;
      if (cart.items_qty != undefined) {
        itemsQty = cart.items_qty;
      }
      let paymentMethods = [];
      return {
        errorMessages,
        ajaxInProgress,
        stepToShow,
        cart,
        addresses,
        shippingMethods,
        paymentMethods,
        itemsQty,
        showMiniCart,
        categoryMenus
      }
    },
    methods: {
      getPathname() {
        return window.location.pathname;
      },
      isCheckout() {
        return window.location.pathname == '/checkout';
      },
      formatInt(number) {
        let outputNumber = parseInt(number);
        return (isNaN(outputNumber)) ? 0 : outputNumber;
      },
      shouldShowMiniCart() {
        return (this.showMiniCart);
      },
      // function to handle mini-cart onclick event
      toggleShowMiniCart(e) {
        e.preventDefault();
        let shouldShowMiniCart = !this.showMiniCart;
        this.showMiniCart = shouldShowMiniCart;
        // VueJS way did not work
        document.getElementById('burger-toggle').checked = false;
      },
      // function to remove item from cart
      removeFromCart(cartItem) {
        this.ajaxInProgress = true;
        let url = '/cartItems/' + cartItem.id;
        if (cartItem.has_failed == 0) {
          // confirm with user
          let confirmDeletion = confirm('Are you sure you want to remove this product?');

          if (confirmDeletion) {
            axios.delete(url).then(response => {
              // Under construction
            });
          }
        } else {
          axios.delete(url).then(response => {
            // Under construction
          });
        }

        // Get an updated version of the active cart
        let getUpdatedCart = function() {
          let cartId = this.cart.id;
          let url = '/carts/' + cartId;
          axios.get(url).then(response => {
            this.cart = response.data.cart;
            this.ajaxInProgress = false;
          });
        };
        setTimeout(getUpdatedCart.bind(this), 5000);

      }
    }
  }
</script>