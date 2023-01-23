<template>
  <div>
    <h1>{{ productName }} Serials</h1>
    <table class="table table-bordered table-striped table-hover table-warning border-lights">
      <thead>
        <th>Serial Number</th>
        <th>Item Name</th>
        <th>Supplier</th>
        <th>Status</th>
        <th>Buying Price</th>
        <th>Date Created</th>
        <th>Created By</th>
        <th>Sold Date</th>
      </thead>
      <tbody>
        <tr v-for="i in serials">
          <td>{{ i.serial_number }}</td>
          <td>{{ i.product_name }}</td>
          <td>{{ i.name }}</td>
          <td>{{ i.status }}</td>
          <td>{{ i.buying_price }}</td>
          <td>{{ returnDate(i.created_at) }}</td>
          <td>{{ i.createdby }}</td>
          <td>{{ i.sold_date }}</td>
        </tr>
      </tbody>
    </table>
    <!-- <ul v-for="i in serials">
      <li>{{ i }}</li>
    </ul> -->
  </div>
</template>

<script>
import moment from 'moment';
  export default {
    data() {
      return {
        serials:[],
        productName:''
      }
    },
    created() {
      console.log(this.$route.params)
      this.productName = this.$route.params.productName;
      let id = this.$route.params.id;
      axios.get('/api/productSerials/'+id)
          .then(({data}) => (this.serials = data))
          .catch()
    },
    methods: {
      returnDate(d) {
        return moment(d).format("MMM Do YY");
      }
    }
  }
</script>