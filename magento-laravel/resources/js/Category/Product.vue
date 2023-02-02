<template>
  <div>
    <div>
      <img :src="getImagePath()" alt="Image here" className="product-img" loading="lazy" />
    </div>
    <div className="product-content w-full">
      <header className="product-name">
        <a href="#" className="font-semibold">{{ product.name }}</a>
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
      return {}
    },
    methods: {
      emitAddToCart(product) {
        this.$emit('addToCart', product);
      },
      getImagePath() {
        let mediaGalleryEntries = JSON.parse(this.product.media_gallery_entries);
        let baseMediaUrl = 'http://magento-laravel.test/media/';
        let productImageUrl = 'catalog/product/tom-and-jerry-meme.png';
        if (mediaGalleryEntries != undefined && mediaGalleryEntries.length > 0) {
          productImageUrl = 'catalog/product/' + mediaGalleryEntries[0]['file'];
        }
        return baseMediaUrl + productImageUrl;
      }
    }
  }
</script>