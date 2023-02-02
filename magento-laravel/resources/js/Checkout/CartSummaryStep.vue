<template>
  <section id="cart-summary" className="step">
    <header className="step-header">
      <h3>Cart Summary</h3>
      <div className="step-toggle" @click="showContent">
        <div className="arrow-1"></div>
        <div className="arrow-2"></div>
      </div>
    </header>

    <div :class="contentClass" role="content">
      <strong>Cart summary content under construction</strong>
    </div>
  </section>
</template>
<script>
  export default {
    data() {
      let arrowClicked = false;
      return {
        arrowClicked
      }
    },
    methods: {
      showContent(e) {
        this.$emit('showContent', this);
      }
    },
    computed: {
      contentClass() {
        let contentClass = 'cart-summary step-content hidden';
        if (this.arrowClicked) {
          contentClass = 'cart-summary step-content';
        }
        return contentClass;
      }
    }
  }
  </script>