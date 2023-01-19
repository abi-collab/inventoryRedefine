<template>
  <div>
    <!-- <table class="table table-responsive">
      <thead>
        <th>Serial Number</th>
        <th>Item Name</th>
        <th>Supplier</th>
      </thead>
      <tbody>
        <tr v-for="i in serials">
          <td>{{ i.serial_number }}</td>
          <td>{{ i.product_name }}</td>
          <td>{{ i.name }}</td>
        </tr>
      </tbody>
    </table> -->
   
    <ul v-for="i in serials">
      <li>{{ i }}</li>
    </ul>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        serials:[]
      }
    },
    created() {
      axios.get('/api/productSerials')
          .then(({data}) => (this.serials = data))
          .catch()
    }
  }
</script>