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
               v-model="billingAddressFormData.lastname"
               placeholder="Last name" />
      </div>
      <div className="error-msg" v-if="errors.lastname">
        <span>{{ errors.lastname }}</span>
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
               v-model="billingAddressFormData.city"
               placeholder="City" />
      </div>
      <div className="error-msg" v-if="errors.city">
        <span>{{ errors.city }}</span>
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
               v-model="billingAddressFormData.region"
               placeholder="State/Province" />
      </div>
      <div className="error-msg" v-if="errors.region">
        <span>{{ errors.region }}</span>
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
                v-model="billingAddressFormData.telephone"
                placeholder="Phone Number" />
      </div>
      <div className="error-msg" v-if="errors.telephone">
        <span>{{ errors.telephone }}</span>
      </div>
    </div>

    <div className="row">
      <button :class="getSubmitClass()" @click="saveAddress">Save Address</button>
    </div>

    </form>
</template>
<script>
    let billingAddressFormData = {};
    export default {

      inject: [
        'cart',
        'addresses',
        'setStepToShow',
        'ajaxInProgress',
        'setAjaxInProgress'
      ],
      data() {
        console.log('BillingAddressForm.vue :: this.addresses()', this.addresses(), this.addresses().length);
        billingAddressFormData = {
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
        if (this.addresses().billing) {
          address = this.addresses().billing;
          billingAddressFormData = this.addresses().billing;
        }

      let errors = {};
      let isFormTouched = false;
      return {
        billingAddressFormData,
        errors,
        isFormTouched
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

        let payload = {
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
        if (this.billingAddressFormData.id != undefined) {
          payload.address.id = this.billingAddressFormData.id;
        }

        this.setAjaxInProgress(true);
        axios.post(saveBillingAddressUrl, payload).then(response => {
          if (response.data.checkout_address) {
            // What to do?
            this.billingAddressFormData.id = response.data.checkout_address.id;
          }
          this.setStepToShow('shipping-method');
          this.arrowClicked = false;
          this.setAjaxInProgress(false);
        });
      },
      getSubmitClass() {
        console.log('BillingAddressForm.vue :: getSubmitClass :: ', Object.keys(this.errors));
        if (!Object.keys(this.errors).length && this.isFormTouched) {
          return 'btn btn-primary';
        }
        return 'btn btn-primary btn-disabled';
      }
    },
    watch: {
      billingAddressFormData: {
        handler(value) {
          this.isFormTouched = true;
          console.log('watch billingAddressFormData', value);

          this.errors = {};
          const validateEmpty = () => {
            const requiredFields = {
              'firstname': 'First name',
              'street': 'street',
              'city': 'city',
              'postcode': 'postal code',
              'telephone': 'phone number',
              'region': 'State',
              'country_id': 'Country'
            };
            let errorMessage = 'Please enter your ';
            for (let f in requiredFields) {
              if (!value[f]) {
                this.errors[f] = errorMessage + requiredFields[f];
              }
            }
          };

          const validateFormats = () => {
            const emailPattern = new RegExp(/@[a-z]+.com/g);
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
