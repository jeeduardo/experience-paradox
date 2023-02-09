<template>
  <section :id="stepId" class="step">
      <header className="step-header">
          <h3>Payment Method</h3>
          <div className="step-toggle" @click="showContent">
            <div className="arrow-1"></div>
            <div className="arrow-2"></div>
          </div>
      </header>

      <div :class="contentClass" role="content" style="position: relative;">
        <ol>
          <li v-for="(paymentMethod, index) in paymentMethods()">
            <PaymentMethod
            :paymentMethod="paymentMethod"
            :selectedPaymentMethod="selectedPaymentMethod"
            :grandTotal="cart().grand_total"
            @selectPaymentMethod="selectPaymentMethod" />
          </li>

          <li>
            <button id="proceed-btn"
                    className="proceed-btn"
                    @click="placeOrder">Proceed</button>
          </li>
        </ol>

        <div :class="getSpinnerClass()">
          <div className="spinner">
            <img src="/images/spinner-200px.gif">
          </div>
        </div>

      </div>

  </section>
</template>
<script>
  import PaymentMethod from './PaymentMethodStep/PaymentMethod.vue';
  export default {
    inject: ['cart', 'setCart', 'addresses', 'paymentMethods'],
    props: ['stepId', 'showFlag'],
    components: {
      PaymentMethod
    },
    data() {
      return {
        selectedPaymentMethod: '',
      }
    },
    methods: {
      showContent(e) {
        this.$emit('showContent', this);
      },
      selectPaymentMethod(paymentMethod) {
        this.selectedPaymentMethod = paymentMethod.code;
      },
      getSpinnerClass() {
        return 'hidden';
      },
      placeOrder(e) {
        e.preventDefault();
        const { shipping, billing } = this.addresses();
        const { region, region_id, country_id, postcode, city, telephone, firstname, lastname } = billing;
        const street = [billing.street];
        let payload = {
          cartId: this.cart().id,
          order: {
            email: shipping.email,
            paymentMethod: {
              method: this.selectedPaymentMethod
            },
            billing_address: {
              email: shipping.email,
              region,
              region_id,
              country_id,
              // street?,
              street,
              postcode,
              city,
              telephone,
              firstname,
              lastname
            }
          }
        };
        console.log('placeOrder :: payload?', payload);
        this.$emit('placeOrder', payload);
      },
      redirectToSuccess(response) {
        // @todo: redirect to "success" page
      }
    },
    computed: {
      contentClass() {
        let contentClass = 'payment-method step-content hidden';

        if (this.showFlag) {
          contentClass = 'payment-method step-content';
        }
        return contentClass;

      }
    }
  }
</script>