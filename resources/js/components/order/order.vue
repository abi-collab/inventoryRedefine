 <template>
    <div>
        <!-- Breadcrumbs-->
        <ol class="breadcrumb mt-3">
          <li class="breadcrumb-item">
            <router-link to="/home">Dashboard</router-link>
          </li>
          <li class="breadcrumb-item active">Today Order</li>
        </ol>
        <!-- Icon Cards-->
       <div class="row card container-fluid shadow mb-3">
          <div class="card-header" style="font-size: 20px; font-weight:700;">
            <i class="fas fa-chart-area"></i>
            Today's Order-Table
            <router-link to="/searchorder" class="btn btn-dark" id="add_new"> All Purchased Orders</router-link>
          </div>
          
          <div class="card-body p-0 m-0">
            <div class="card-body p-0 m-0">
              <div class="table-responsive">
                <label class="d-inline">Search : </label>
               <input type="text" v-model="searchTerm" class="form-control d-inline" style="width:200px;" placeholder="Search by Name"><br><br>
                <table class="table table-bordered table-striped table-hover table-warning border-light" id="" width="100%" cellspacing="0">

                  <thead>
                    <tr class="bg-info text-white">
                      <th>Name</th>
                      <th>Invoice&nbsp;Number</th>
                      <th>Purchase&nbsp;Date</th>
                      <th>Total Due</th>
                      <!-- <th>Pay</th> -->
                      <!-- <th>Due</th> -->
                      <th>Payby</th>
                      <th>Action</th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr v-for="order in filtersearch" :key="order.id">
                      <td>{{ order.name }}</td>
                      <td>{{ order.invoiceNum }}</td>
                      <td>{{ order.order_date }}</td>
                      <td>&#8369;&nbsp; {{(Number(order.sub_total).toLocaleString() || 0)}}</td>
                      <!-- <td>{{ order.pay }}</td> -->
                      <!-- <td>{{ order.due }}</td> -->
                      <td>{{ order.payby }}</td>
                      <td>
                        <router-link :to="{name: 'view-order', params:{id: order.id} }" class="btn btn-sm btn-info">View</router-link>
                        <!-- <router-link :to="'/pay-salary/'+employee.id" class="btn btn-sm btn-info">Pay Salary</router-link> --> <!--or, -->
                        <!-- <router-link :to="`/pay-salary/${employee.id}`" class="btn btn-sm btn-info">Pay Salary</router-link> -->
                      </td>
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
    export default {
    	mounted(){
            if (!User.loggedIn()) {
               this.$router.push({ name:'/' })
            }
        },
        created(){
            this.allOrder();
        },
        data(){
          return{
            orders:[],
            searchTerm:'',
          }
        },
        computed:{
            filtersearch(){
                return this.orders.filter(order => {
                    //return order.name.match(this.searchTerm)
                    return order.name.toLowerCase().match(this.searchTerm.toLowerCase())
                })
            }
        },
        methods:{
          allOrder(){
            axios.get('/api/orders/')
            .then(({data}) => (this.orders = data))
            .catch()
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
