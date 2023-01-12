<template>

  <div>
    <div>
      <img :src="getImagePath()" alt="Image here" className="product-img" loading="lazy" />
    </div>
    <div className="product-content w-full">
      <header className="product-name">
        <a href="#" className="font-semibold">{{ product.name }} -- {{ product.sku }}</a>
      </header>
      <div className="product-other-data w-full">
        <ProductPrice :product="product" />
      </div>
      <ProductActions :product="product" @addToCart="emitAddToCart" />
    </div>
  </div>

</template>
<script>
  import ProductPrice from './ProductPrice.vue';
  import ProductActions from './ProductActions.vue';
  export default {
    components: {
      ProductPrice,
      ProductActions
    },
    props: {
      product: Object
    },
    data() {
      // console.log('Product.vue :: price debug?', this.product.name, this.product);
      return {}
    },
    methods: {
      emitAddToCart(product) {
        console.log('Product.vue :: addToCart', product.sku);
        this.$emit('addToCart', product);
      },
      getImagePath() {
        let mediaGalleryEntries = JSON.parse(this.product.media_gallery_entries);
        let baseMediaUrl = 'http://magento-laravel.test/media/';
        return baseMediaUrl + 'catalog/product/' + mediaGalleryEntries[0]['file'];
      }
    }
  }
</script>