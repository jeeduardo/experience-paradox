const NumberFormat = {
  currency: '$',
  formatInt: (number) => {
    return (isNaN(number)) ? 0 : parseInt(number)
  },
  formatPrice: (price) => {
    return (isNaN(price)) ? 0 : parseFloat(price).toFixed(2);
  }
};

export default NumberFormat;