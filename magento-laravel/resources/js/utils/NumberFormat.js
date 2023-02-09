const NumberFormat = {
  currency: '$',
  formatInt: (number) => {
    return (isNaN(number)) ? 0 : parseInt(number)
  },
  formatPrice: (price) => {
    if (isNaN(price)) {
      return 0;
    }

    price = parseFloat(price);
    if (price < 0) {
      price = Math.abs(price);
    }

    return price.toFixed(2);
  }
};

export default NumberFormat;