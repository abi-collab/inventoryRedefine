<template>
  <div>

      <ol class="breadcrumb mt-3">
        <li class="breadcrumb-item">
          <router-link to="/home">Dashboard</router-link>
        </li>
        <li class="breadcrumb-item active">Sold Items</li>
      </ol>

     <div class="row card container-fluid shadow mb-3">
        <div class="card-header text-dark mb-4" style="font-size: 20px; font-weight:700;">
          <i class="fas fa-chart-area"></i>
          Sold Items with Serial Numbers
        </div>
        <div class="card-body p-0 m-0">
          <div class="card-body p-0 m-0">
            <div class="table-responsive">
              <label class="d-inline">Search : </label>
             <input type="text" v-model="searchTerm2" class="form-control d-inline" style="width:200px;" placeholder="Search by name"><br><br>
              <table class="table table-bordered table-striped table-hover table-warning border-lights" id="" width="100%" cellspacing="0">
                <thead>
                  <tr class="bg-info text-white">
                    <th>Invoice&nbsp;#</th>
                    <th>Serial&nbsp;Number</th>
                    <th>Item&nbsp;Name</th>
                    <th>Customer&nbsp;Name</th>
                    <th>Created&nbsp;By</th>
                  </tr>
                </thead>

                <tbody>
                  <tr v-for="item in filtersearch2" :key="item.id">
                    <td>{{item.invoiceNumber}}</td>
                    <td>{{item.serialNo}}</td>
                    <td>{{ item.product_name }}</td>
                    <td>{{item.customerName}}</td>
                    <td>{{returnName(item.created_by).name}}</td>
                    
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer small text-muted"></div>
        </div>
     </div>
    
 </div>
</template>
<script>
import Cookies from 'js-cookie';
  export default {
    mounted(){
          if (!User.loggedIn()) {
             this.$router.push({ name:'/' })
          }
      },
      created(){
          axios.get('/api/serials')
          .then(({data}) => (this.soldItems = data, console.log(data)))
          .catch()

          axios.get('/api/users')
          .then(({data}) => (this.users = data, console.log('users', data)))
          .catch()

          
      },
      data(){
        return{
          customers:[],
          soldItems:[],
          users:[],
          searchTerm:'',
          searchTerm2:'',
          userNow: Cookies.get('userNow')
        
        }
      },
     computed:{
       filtersearch2(){
        return this.soldItems.filter(item => {
          //return customer.name.match(this.searchTerm)
          return item.invoiceNumber.toLowerCase().match(this.searchTerm2.toLowerCase()) || item.serialNo.toLowerCase().match(this.searchTerm2.toLowerCase())
         })
       }
     },
      methods: {
        // getuser(id) {
        //   let a = this.users.filter(user => user.id = id);
        //   return a;
        // }
        returnName(id) {
          let a = this.users.filter((p) => p.id == id);
          return a[0];
        }
      }
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
