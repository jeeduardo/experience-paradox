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
          <img src='../images/logo.svg' />
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

  <div id="catalog-container" v-if="getPathname() != '/checkout'">
    <CategoryView />
  </div>

  <div id="checkout-container" v-if="isCheckout()">
    <Checkout :cart="cart" />
  </div>

</template>
<script>
  import Menus from './Menus.vue';
  import MiniCart from './MiniCart.vue';
  import CategoryView from './CategoryView.vue';
  import Checkout from './Checkout.vue';

  const { vars } = document.getElementById('app').dataset;
  const { categoryMenus, cart } = JSON.parse(vars);

  export default {
    components: {
      Menus,
      MiniCart,
      CategoryView,
      Checkout
    },
    provide() {
      console.log('App.vue :: provide()');
      // let showMiniCart = this.showMiniCart;
      return {
        shouldShowMiniCart: () => this.showMiniCart,
        setShowMiniCart: (flag) => { this.showMiniCart = flag },
        cart: () => this.cart,
        setCart: (cart) => { this.cart = cart },
        setCartItemsQty: (itemsQty) => { this.itemsQty = itemsQty },
        ajaxInProgress: () => this.ajaxInProgress,
        setAjaxInProgress: (flag) => { this.ajaxInProgress = flag; }
      }
    },
    data() {
      let ajaxInProgress = false;
      let showMiniCart = false;
      const axios = window.axios;
      // const categoryMenus = categoryMenus;

      console.log('App.vue :: data() :: cart from backend', cart);

      let itemsQty = 0;
      if (cart.items_qty != undefined) {
        itemsQty = cart.items_qty;
      }
      return {
        ajaxInProgress,
        cart,
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
        // @todo: do a "preg_match" later instead
        return window.location.pathname == '/checkout';
      },
      formatInt(number) {
        let outputNumber = parseInt(number);
        return (isNaN(outputNumber)) ? 0 : outputNumber;
      },
      shouldShowMiniCart() {
        return (this.showMiniCart);
      },
      toggleShowMiniCart(e) {
        console.log('App.vue :: ajaxInProgress', this.ajaxInProgress);
        e.preventDefault();
        console.log('toggleShowMiniCart');
        let shouldShowMiniCart = !this.showMiniCart;
        this.showMiniCart = shouldShowMiniCart;
        document.getElementById('burger-toggle').checked = false;
      },
      removeFromCart(cartItem) {
        this.ajaxInProgress = true;
        console.log('App.vue :: removeFromCart :: cartItem', cartItem);
        let url = '/cartItems/' + cartItem.id;
        if (cartItem.has_failed == 0) {
          // confirm with user
          let confirmDeletion = confirm('Are you sure you want to remove this product?');

          if (confirmDeletion) {
            axios.delete(url).then(response => {
              console.log('DELETE ' + url + 'response', response);
            });
          }
        } else {
          axios.delete(url).then(response => {
            console.log('DELETE ' + url + 'response', response);
          });
        }

        let getUpdatedCart = function() {
          let cartId = this.cart.id;
          let url = '/carts/' + cartId;
          axios.get(url).then(response => {
            console.log('getUpdatedCart :: ', response.data.cart);
            this.cart = response.data.cart;
            this.ajaxInProgress = false;
          });
        };

        setTimeout(getUpdatedCart.bind(this), 5000);
      }
    }
  }
</script>