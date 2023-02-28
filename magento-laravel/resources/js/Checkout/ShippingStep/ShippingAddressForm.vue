<template>
  <form action="" method="POST">

    <div className="row">
      <label htmlFor="email" className="required hidden">
        Email
      </label>
      <div className="form-input-container">
        <input type="email"
               className="form-input"
               name="email" v-model="shippingAddressFormData.email"
               placeholder="Email address"/>
      </div>
      <div className="error-msg" v-if="errors.email">
        <span>{{ errors.email }}</span>
      </div>
    </div>

    <div className="row">
      <label htmlFor="firstname" className="hidden">First name</label>
      <div className="form-input-container">
        <input type="text"
               className="form-input"
               name="firstname"
               id="firstname"
               v-model="shippingAddressFormData.firstname"
               placeholder="First name" />
      </div>
      <div className="error-msg" v-if="errors.firstname">
        <span>{{ errors.firstname }}</span>
      </div>
    </div>

    <div className="row">
      <label htmlFor="lastname" className="hidden">Last name</label>
      <div className="form-input-container">
        <input type="text"
               className="form-input"
               name="lastname"
               id="lastname"
               v-model="shippingAddressFormData.lastname"
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
               v-model="shippingAddressFormData.street"
               placeholder="Street" />
      </div>
      <div className="error-msg" v-if="errors.street">
        <span>{{ errors.street }}</span>
      </div>
    </div>

    <div className="row">
      <label htmlFor="city" className="hidden">City</label>
      <div className="form-input-container">
        <input type="text"
               className="form-input"
               name="city"
               id="city"
               v-model="shippingAddressFormData.city"
               placeholder="City" />
        <div className="error-msg" v-if="errors.city">
          <span>{{ errors.city }}</span>
        </div>
      </div>
    </div>

    <div className="row">
      <label htmlFor="postcode" className="hidden">Postal Code</label>
      <div className="form-input-container">
        <input type="text"
               className="form-input"
               name="postcode"
               id="postcode"
               v-model="shippingAddressFormData.postcode"
               placeholder="Postal Code" />
      </div>
      <div className="error-msg" v-if="errors.postcode">
        <span>{{ errors.postcode }}</span>
      </div>
    </div>

    <div className="row">
      <label htmlFor="region" className="hidden">State/Province</label>
      <div className="form-input-container">
        <input type="text"
               className="form-input"
               name="region"
               id="region"
               v-model="shippingAddressFormData.region"
               placeholder="State/Province" />
      </div>
      <div className="error-msg" v-if="errors.region">
        <span>{{ errors.region }}</span>
      </div>
    </div>


    <div className="row">
      <label htmlFor="region_id" className="hidden">State/Province</label>
      <div className="form-input-container">
        <select id="region_id" name="region_id" v-model="shippingAddressFormData.region_id" @change="syncWithRegionTextbox">
          <option value="">-Region-</option>
          <option v-for="(region, index) in regions()" :value="index">{{ region }}</option>
        </select>
      </div>
    </div>
    <div className="row">
      <label htmlFor="country_id" className="hidden">Country</label>
      <div className="form-input-container">
        <select className="form-input" name="country_id" id="country_id" v-model="shippingAddressFormData.country_id">
          <option value="" selected="selected">-Country-</option>
          <option value="CA">Canada</option>
          <option value="PH">Philippines</option>
        </select>
      </div>
      <div className="error-msg" v-if="errors.country_id">
        <span>{{ errors.country_id }}</span>
      </div>
    </div>

    <div className="row">
      <label htmlFor="telephone" className="hidden">Phone Number</label>
      <div className="form-input-container" style="margin-bottom: 10px;">
        <input type="text"
               className="form-input"
               name="telephone"
               id="telephone"
               v-model="shippingAddressFormData.telephone"
               placeholder="Phone Number" />
      </div>
      <div className="error-msg" v-if="errors.telephone">
        <span>{{ errors.telephone }}</span>
      </div>
    </div>

    <div className="row">
      <input type="checkbox" name="same_as_billing" id="same-as-billing" v-model="shippingAddressFormData.same_as_billing" className="hidden" />
      <label htmlFor="same-as-billing"
             className="same-as-billing-label">
        <div className="same-as-billing-box">
          <div :class="getSameAsBillingClass()"></div>
          <div className="same-as-billing-text">I have the same billing address.</div>
        </div>
      </label>
    </div>

    <div className="row">
      <button :class="getSubmitClass()" @click="saveAddress" :disabled="isSubmitDisabled">Save Address</button>
    </div>
  </form>

  <div :class="getSpinnerClass()">
    <div className="spinner">
      <img src="/images/spinner-200px.gif">
    </div>
  </div>
</template>
<script>
  // Vue JS file for shipping address form
  let shippingAddressFormData = {};
  export default {
    inject: [
      'cart',
      'regions',
      'addresses',
      'setShippingAddress',
      'setShippingMethods',
      'setStepToShow',
      'ajaxInProgress',
      'setAjaxInProgress'
    ],
    emits: ['afterSaveShippingAddress', 'showBillingAddressStep'],
    data() {
      console.log('ShippingAddressForm.vue :: this.addresses() ?', this.addresses());
      const cart = this.cart();
      let id = 0;
      let shippingAddress = {};
      if (this.addresses() != undefined && this.addresses().shipping) {
        shippingAddress = this.addresses().shipping;
      }
      const {
        email,
        firstname,
        lastname,
        city,
        region,
        region_id,
        telephone,
        country_id,
        postcode
      } = shippingAddress;
      if (shippingAddress.id) {
        id = shippingAddress.id;
      }
      const street = shippingAddress.street;

      let sameAsBilling = false;
      if (shippingAddress.same_as_billing) {
        sameAsBilling = true;
      }

      let errors = {};
      // flag to check if address form was "touched" already
      let isFormTouched = false;

      shippingAddressFormData = {
        id,
        email,
        firstname,
        lastname,
        street,
        city,
        region,
        region_id,
        postcode,
        country_id,
        telephone,
        same_as_billing: sameAsBilling
      };

      return { shippingAddressFormData, sameAsBilling, errors, isFormTouched };
    },
    methods: {
      syncWithRegionTextbox(e) {
        console.log('syncWithRegionTextbox :: ', e.target, this.shippingAddressFormData.region_id);
        const region_id = this.shippingAddressFormData.region_id;
        this.shippingAddressFormData.region = this.regions()[region_id];
      },
      setSameAsBilling(flag) {
        this.sameAsBilling = flag;
        this.shippingAddressFormData.same_as_billing = this.sameAsBilling;

        // Me: Yes, I had to do this...
        // Vue JS practice head: that's not good practice
        // Me: yeah, but why's checkbox still checked even when i uncheck it upon load???
        // if (!this.sameAsBilling) {
        //   document.getElementById('same-as-billing').checked = '';
        //   this.shippingAddressFormData.same_as_billing = false;
        // }
        this.$emit('showBillingAddressStep', this.sameAsBilling);
      },
      saveAddress(e) {
        e.preventDefault();

        const {
          region,
          region_id,
          country_id,
          postcode,
          city,
          firstname,
          lastname,
          email,
          telephone,
          id,
          same_as_billing
        } = this.shippingAddressFormData;
        let street = [
          this.shippingAddressFormData.street,
        ];

        let cart_id = this.cart().id;

        let shippingMethodsPayload = {
          address: {
            id,
            region,
            region_id,
            country_id,
            street,
            postcode,
            city,
            firstname,
            lastname,
            // customer_id NONE
            email,
            telephone,
            same_as_billing
          },
          cart_id
        };

        const shippingMethodsUrl = '/checkout/' + this.cart().cart_token + '/shipping-address';

        this.setAjaxInProgress(true);
        const axios = window.axios;
        axios.post(shippingMethodsUrl, shippingMethodsPayload).then(response => {
          shippingAddressFormData = response.data.checkout_address;
          this.shippingAddressFormData = shippingAddressFormData;
          this.setShippingAddress(shippingAddressFormData);

          var pollShippingMethodsFn = setInterval(() => {
            let addressId = shippingAddressFormData.id;

            const pollShippingMethodsUrl = '/checkout/address/' + addressId + '/shipping-methods';
            axios.get(pollShippingMethodsUrl).then(pollResponse => {
              if (
                pollResponse.data
                && pollResponse.data.shippingMethods
                && pollResponse.data.shippingMethods.length > 0
              ) {
                this.setShippingMethods(pollResponse.data.shippingMethods);
                this.$emit('afterSaveShippingAddress');

                let stepToShow = 'shipping-method';
                if (!shippingAddressFormData.same_as_billing) {
                  stepToShow = 'billing-address';
                }
                this.setStepToShow(stepToShow);
                clearInterval(pollShippingMethodsFn);
              }
            }).finally((param) => {
              // @todo: clearInterval after N retries...
              // clearInterval(pollShippingMethodsFn);
            });
          }, 3000);
        }).finally(() => {
          this.setAjaxInProgress(false);
        });
      },
      getSpinnerClass() {
        if (this.ajaxInProgress()) {
          return 'spinner-container';
        }
        return 'spinner-container hidden';
      },
      getSameAsBillingClass() {
        this.sameAsBilling = this.shippingAddressFormData.same_as_billing;
        this.setSameAsBilling(this.sameAsBilling);
        if (this.sameAsBilling) {
          return 'same-as-billing-radio active';
        }
        return 'same-as-billing-radio';
      },
      getSubmitClass() {
        if (!Object.keys(this.errors).length && this.isFormTouched) {
          return 'btn btn-primary';
        }
        return 'btn btn-primary btn-disabled';
      }
    },
    computed: {
      isSubmitDisabled() {
        console.log('isSubmitDisabled', !Object.keys(this.errors).length);
        if (!Object.keys(this.errors).length && this.isFormTouched) {
          return false;
        }
        return true
      }
    },
    watch: {
      shippingAddressFormData: {
        handler(value) {
          this.isFormTouched = true;
          this.errors = {};

          const validateEmpty = () => {
            const requiredFields = {
              'email': 'e-mail address',
              'firstname': 'First name',
              'street': 'street',
              'city': 'city',
              'postcode': 'postal code',
              'telephone': 'phone number'
            };
            let errorMessage = 'Please enter your ';
            for (let f in requiredFields) {
              if (!value[f]) {
                this.errors[f] = errorMessage + requiredFields[f];
              }
            }
          };

          const validateFormats = () => {
            const emailPattern = new RegExp(/@[a-z]+.(com|ca)/g);
            if (value.email 
              && value.email.match(emailPattern) == undefined) {
              this.errors['email'] = 'Not a valid email address';
            }

            const postcodePattern = new RegExp(/^[A-Z][0-9][A-Z] [0-9][A-Z][0-9]$/g);
            if (value.postcode 
              && value.postcode.match(postcodePattern) == undefined) {
              this.errors['postcode'] = 'Postal code format is not valid.';
            }

            const telephonePattern = new RegExp(/[0-9]{10}/g);
            if (value.telephone 
              && value.telephone.match(telephonePattern) == undefined) {
              this.errors['telephone'] = 'Not a valid phone number';
            }
          }
          validateEmpty();
          validateFormats();

        },
        deep: true
      }
    }
  }
</script>
