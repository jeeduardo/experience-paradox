<template>
  <h1 className="category-header">{{ category.name }}</h1>

  <div className="products">

    <section className="product text-center" v-for="(product, index) in products">
      <Product :product="product" @addToCart="addToCart" />
    </section>

  </div>
</template>
<script>
  import Product from './Category/Product.vue';
  const { vars } = document.getElementById('app').dataset;

  const { category, products } = JSON.parse(vars);

  export default {
    inject: [
      'cart',
      'setCart',
      'setCartItemsQty',
      'shouldShowMiniCart',
      'setShowMiniCart',
      'setAjaxInProgress'
    ],
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
      addToCart(product) {
        // @todo: kunware wala pang cookie palagi
        this.setAjaxInProgress(true);
        const axios = window.axios;
        let url = '/cart/init';
        axios.post(url).then(response => {
          if (response.data) {
            const { quote_id, cartId } = response.data;
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
            let url = '/cart/' + cartId + '/items';
            axios.post(url, addToCartPayload).then(cartAddResponse => {
              // update cart
              this.setCart(cartAddResponse.data.cart);
              this.setCartItemsQty(cartAddResponse.data.cart.items_qty);
              // show minicart
              this.setShowMiniCart(true);
              this.setAjaxInProgress(false);
            });
          }
        });
      }
    }
  }
  </script>