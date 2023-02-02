<template>
  <form action="" method="POST">


    <div className="row">
      <label htmlFor="firstname" className="hidden">First name</label>
      <div className="form-input-container">
        <input type="text"
               className="form-input"
               name="firstname"
               id="firstname"
               v-model="billingAddressFormData.firstname"
               placeholder="First name" />
      </div>
    </div>

    <div className="row">
      <label htmlFor="lastname" className="hidden">Last name</label>
      <div className="form-input-container">
        <input type="text"
               className="form-input"
               name="lastname"
               id="lastname"
               v-model="billingAddressFormData.lastname"
               placeholder="Last name" />
      </div>
    </div>

    <div className="row">
      <label htmlFor="street" className="hidden">Street</label>
      <div className="form-input-container">
        <input type="text"
               className="form-input"
               name="street"
               id="street"
               v-model="billingAddressFormData.street"
               placeholder="Street" />
      </div>
    </div>

    <div className="row">
      <label htmlFor="city" className="hidden">City</label>
      <div className="form-input-container">
        <input type="text"
               className="form-input"
               name="city"
               id="city"
               v-model="billingAddressFormData.city"
               placeholder="City" />
      </div>
    </div>

    <div className="row">
      <label htmlFor="postcode" className="hidden">Postal Code</label>
      <div className="form-input-container">
        <input type="text"
               className="form-input"
               name="postcode"
               id="postcode"
               v-model="billingAddressFormData.postcode"
               placeholder="Postal Code" />
      </div>
    </div>

    <div className="row">
      <label htmlFor="region" className="hidden">State/Province</label>
      <div className="form-input-container">
        <input type="text"
               className="form-input"
               name="region"
               id="region"
               v-model="billingAddressFormData.region"
               placeholder="State/Province" />
      </div>
    </div>

    <div className="row">
      <label htmlFor="country_id" className="hidden">Country</label>
      <div className="form-input-container">
        <select className="form-input" name="country_id" id="country_id" v-model="billingAddressFormData.country_id">
          <option value="" selected="selected">-Country-</option>
          <option value="CA">Canada</option>
          <option value="PH">Philippines</option>
        </select>
      </div>
    </div>

    <div className="row">
      <label htmlFor="telephone" className="hidden">Phone Number</label>
      <div className="form-input-container" style="margin-bottom: 10px;">
        <input type="text"
               className="form-input"
               name="telephone"
               id="telephone"
               v-model="billingAddressFormData.telephone"
               placeholder="Phone Number" />
      </div>
    </div>

    <div className="row">
      <button className="btn btn-primary" @click="saveAddress">Save Address</button>
    </div>

  </form>
</template>
<script>
  export default {

    inject: [
      'cart',
      'addresses',
      'setStepToShow',
      'ajaxInProgress',
      'setAjaxInProgress'
    ],
    data() {
      let billingAddressFormData = {
        firstname: '',
        lastname: '',
        street: '',
        city: '',
        postcode: '',
        region: '',
        country_id: '',
        telephone: ''
      };

      let address = {};
      if (this.addresses().length > 0) {
        address = this.addresses()[0];
        if (address.same_as_billing) {
          billingAddressFormData = address;
        } else if (this.addresses().length > 1) {
          billingAddressFormData = this.addresses()[1];
        }
      }

      return {
        billingAddressFormData
      };
    },
    methods: {
      saveAddress(e) {
        e.preventDefault();
        const saveBillingAddressUrl = '/checkout/' + this.cart().cart_token + '/billing-address';
        const {
          id,
          firstname,
          lastname,
          city,
          postcode,
          region,
          country_id,
          telephone
        } = this.billingAddressFormData;

        let street = [this.billingAddressFormData.street];
        let cart_id = this.cart().id;

        const payload = {
          cart_id,
          address: {
            firstname,
            lastname,
            street,
            city,
            postcode,
            region,
            country_id,
            telephone
          }
        };

        this.setAjaxInProgress(true);
        axios.post(saveBillingAddressUrl, payload).then(response => {
          if (response.data.checkout_address) {
            // What to do?
          }
          this.setStepToShow('shipping-method');
          this.arrowClicked = false;
          this.setAjaxInProgress(false);
        });
      }
    }
  }
</script>