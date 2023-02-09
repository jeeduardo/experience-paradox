<template>
  <div :class="getTotalSegmentClass()">
    <div className="total-title">{{ totalSegment.title }}</div>
    <div className="total-segment-price">
      <Price :price="totalSegment.value" />
    </div>
  </div>
</template>
<script>
  import Price from './../utils/Price.vue';

  export default {
    props: {
      totalSegment: Object
    },
    components: {
      Price
    },
    data() {
      console.log('TotalSegment.vue :: totalSegment???', this.totalSegment);
      return {}
    },
    methods: {
      getTotalSegmentClass() {
        if (this.totalSegment.code == 'grand_total') {
          return 'total-segment font-bold';
        }
        return 'total-segment';
      }
    }
  }
</script>