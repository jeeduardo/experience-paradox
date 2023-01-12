import CartItem from './CartItem.vue';
export default {
  components: {
    CartItem
  },
  props: {
    cart: Object,
  },
  data() {
    console.log('for Checkout.vue :: ', this.cart);
    return {}
  }
}