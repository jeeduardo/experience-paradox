<template>
  <h1 className="category-header">{{ category.name }}</h1>

  <div className="products">

    <section className="product text-center" v-for="(product, index) in products">
      <Product :product="product" @addToCart="addToCart" />
    </section>

  </div>
</template>
<script>
  // Vue JS file for product display
  import Product from './Category/Product.vue';

  const { vars } = document.getElementById('app').dataset;
  const { category, products } = JSON.parse(vars);

  export default {
    // Inject some objects/functions that "provides" have provided in App.vue
    inject: [
      'cart',
      'setCart',
      'setCartItemsQty',
      'shouldShowMiniCart',
      'setShowMiniCart',
      'setAjaxInProgress'
    ],
    // When rendering a child product, we have to declare them also inside the "components" property
    components: {
      Product
    },
    data() {
      const categoryCustomAttributes = JSON.parse(category.custom_attributes);
      return {
        category,
        products,
        categoryCustomAttributes
      }
    },
    methods: {
      // Initialize cart session, return a quote ID
      async initCart(product) {
        return new Promise(resolve => {

          let url = '/cart/init';
          axios.post(url).then(response => {
            if (response.data) {
              // Adjust now that we don't have the cartId
              let { quote_id, cartMainId } = response.data;
              // Poll for updates every 3 seconds, get cart token if we have it already...
              // If we got a quote ID, we clear the below setInterval function
              var pollCartStopper = setInterval(() => {
                let pollCartUrl = '/cart/' + cartMainId + '/poll_quote';
                axios.get(pollCartUrl)
                  .then(pollResponse => {
                    quote_id = pollResponse.data.quote_id;
                    let cartToken = pollResponse.data.cart_token;
                    if (quote_id != null) {
                      clearInterval(pollCartStopper);
                      resolve(quote_id);
                    }
                  });
              }, 3000);
            }
          }).catch(() => {
            resolve(false);
          });
        });
      },
      addToCart(product) {
        this.setAjaxInProgress(true);
        const axios = window.axios;

        let quote_id = false;
        // Don't initialize cart if we have a cart object already
        if (this.cart().id) {
          quote_id = this.cart().cart_token;
          if (quote_id) {
            let addToCartPayload = {
              cartItem: {
                quote_id,
                sku: product.sku,
                qty: 1
              },
              product: {
                id: product.id,
                name: product.name,
                price: product.price
              }
            };
            this.addToCartAjax(addToCartPayload);
          }
        } else {
          // Call initCart() - response should be the quote_id
          this.initCart(product).then(response => {
            if (response != false) {
              quote_id = response;
              const { sku, id, name, price } = product;
              const qty = 1;
              if (quote_id) {
                let addToCartPayload = {
                  cartItem: { quote_id, sku, qty},
                  product: { id, name, price }
                };
                this.addToCartAjax(addToCartPayload);
              }
            }
          });

        }
      },
      addToCartAjax(data) {
        let addToCartUrl = '/cart/items';
        axios.post(addToCartUrl, data).then(cartAddResponse => {
          // update cart
          this.setCart(cartAddResponse.data.cart);
          this.setCartItemsQty(cartAddResponse.data.cart.items_qty);
          // show minicart
          this.setShowMiniCart(true);
          this.setAjaxInProgress(false);
        });
      }
    }
  }
  </script>