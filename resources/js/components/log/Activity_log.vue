<template>
  <div>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb mt-3">
        <li class="breadcrumb-item">
          <a href="#">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">Activity Logs</li>
      </ol>
      <!-- Icon Cards-->
     <div class="row card container-fluid shadow mb-3">
        <div class="card-header text-dark" style="font-size: 20px; font-weight:700;">
          <i class="fas fa-chart-area"></i>
          Activity Logs

        </div>
        <div class="card-body">
          <div class="card-body">
            <div class="table-responsive">
              <label class="d-inline">Search : </label>
             <input type="text" v-model="searchTerm" class="form-control d-inline" style="width:200px;" placeholder="Search by name"><br><br>
              <table class="table table-bordered table-striped table-hover table-warning border-secondarys" id="" width="100%" cellspacing="0">
                <thead>
                  <tr class="bg-info text-white">
                    <th>Activity</th>
                    <th>Date</th>
                    <th>User</th>
                   
                  </tr>
                </thead>

                <tbody>
                  <tr v-for="customer in filtersearch" :key="customer.id">
                    <td>{{ customer.name }}</td>
                    <td><img :src="customer.photo" id="em_photo"></td>
                    <td>{{ customer.phone }}</td>
                    
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
        </div>
     </div>
 </div>
</template>


<script>
  export default {
    mounted(){
          if (!User.loggedIn()) {
             this.$router.push({ name:'/' })
          }
      },
      created(){
          this.allCustomer();
      },
      data(){
        return{
          customers:[],
          searchTerm:'',
        }
      },
     computed:{
       filtersearch(){
        return this.customers.filter(customer => {
          //return customer.name.match(this.searchTerm)
          return customer.name.toLowerCase().match(this.searchTerm.toLowerCase())
         })
       }
     },
      methods:{
        allCustomer(){
          axios.get('/api/Customer/')
          .then(({data}) => (this.customers = data))
          .catch()
        },
        deleteCustomer(id){
          Swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.value) {
            axios.delete('/api/Customer/'+id)
            .then(()=>{
               this.customers = this.customers.filter(customer =>{
                  return customer.id !=id
               })
            })
            .catch(()=>{
               this.$router.push({name: 'Customer'})
            })
            Swal.fire(
              'Deleted!',
              'Your file has been deleted.',
              'success'
            )
          }
         })
        }
      },
  }
</script>


<style>
#add_new{
float: right;
}
#em_photo{
height: 40px;
width: 40px;
}
</style>
