<template>
  <span className="qty-value">{{ formatInt(quantity) }}</span>
</template>
<script>

import NumberFormat from './NumberFormat.js';

export default {
  props: {
    quantity: String,
  },
  methods: {
    formatInt(number) {
      return NumberFormat.formatInt(number);
    }
  }
}
</script>