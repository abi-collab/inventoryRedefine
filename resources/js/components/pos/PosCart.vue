<template>
  <div class="col-lg-6 shadow">
    <div class="card-header flex bg-light">
      <b>Invoice #: {{ invoiceNum }}</b>
    </div>
    <div class="card-body p-0 m-0">
      <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col" style="text-align:left;">Item</th>
            <th scope="col">Qty</th>
            <th scope="col">Unit</th>
            <th scope="col">Sub-Total</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="card in cards" :key="card.id">
            <th style="text-align:left;">{{ card.pro_name }}</th>
            <td style="display: flex;align-items: center;justify-content: space-between;">
              <button @click.prevent="$emit('decrement', card)" class="btn btn-sm btn-danger" v-if="card.pro_quantity >= 2">-</button>
              <button class="btn btn-sm btn-danger" v-else disabled>-</button>
              <input type="text" readonly style="width: 30px; text-align: center;" :value="card.pro_quantity">
              <button @click.prevent="$emit('increment', card)" class="btn btn-sm btn-success">+</button>
            </td>
            <td>
              <div style="display:flex; justify-content: space-between">&#8369;<p>{{ Number(card.product_price).toLocaleString() || 0 }}</p></div>
            </td>
            <td>
              <div style="display:flex; justify-content: space-between">&#8369;&nbsp;<p>{{ Number(card.sub_total).toLocaleString() || 0 }}</p></div>
            </td>
            <td><a @click="$emit('remove', card)" class="btn btn-sm btn-outline-danger text-danger">x</a></td>
          </tr>
        </tbody>
      </table>
      <hr>
    </div>
    <div class="card-footer">
      <ul class="list-group">
        <li class="list-group-item d-flex justify-content-between align-items-center">
          Total Quantity: <strong>{{ qty }}</strong>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
          Total Due: <strong> &#8369; {{ Number(subtotal).toLocaleString() || 0 }} </strong>
        </li>
      </ul>
      <br>
      <slot name="checkout" />
    </div>
  </div>
</template>

<script>
export default {
  name: 'PosCart',
  props: {
    cards: { type: Array, default: () => [] },
    qty: { type: [Number, String], default: 0 },
    subtotal: { type: [Number, String], default: 0 },
    invoiceNum: { type: [Number, String], default: '' },
  },
  emits: ['increment', 'decrement', 'remove'],
};
</script>
