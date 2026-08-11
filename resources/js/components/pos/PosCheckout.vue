<template>
  <form @submit.prevent="$emit('place-order')">
    <div class="row">
      <div class="col-lg-6">
        <label>Customer Name</label>
        <div class="input-group ">
          <div class="input-group-prepend">
            <button class="btn btn-secondary text-white" data-toggle="modal" data-target="#exampleModal" type="button"> + </button>
          </div>
          <select class="form-control" :value="customerId" required @change="$emit('update:customerId', $event.target.value)">
            <option :value="customer.id" v-for="customer in customers" :key="customer.id">{{ customer.name }}</option>
          </select>
        </div>
      </div>
      <div class="col-lg-6">
        <label>Cash Recieved</label>
        <input type="text" class="form-control mb-2" required :value="pay" @input="$emit('update:pay', $event.target.value)">
        <div v-if="Number(pay) > Number(subtotal)">
          <label>Change</label>
          <input type="text" class="form-control mb-2" required :value="change" disabled>
        </div>
      </div>
    </div>
    <br>
    <button type="submit" class="btn btn-success mb-4" v-if="cardsLength > 0 && Number(pay) >= Number(subtotal)">Place Order</button>
  </form>
</template>

<script>
export default {
  name: 'PosCheckout',
  props: {
    customers: { type: Array, default: () => [] },
    customerId: { type: [Number, String], default: '' },
    pay: { type: [Number, String], default: '' },
    subtotal: { type: [Number, String], default: 0 },
    change: { type: [Number, String], default: 0 },
    cardsLength: { type: Number, default: 0 },
  },
  emits: ['place-order', 'update:customerId', 'update:pay'],
};
</script>
