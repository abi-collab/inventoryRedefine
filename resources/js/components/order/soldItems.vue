<template>
  <div>

      <ol class="breadcrumb mt-3">
        <li class="breadcrumb-item">
          <router-link to="/home">Dashboard</router-link>
        </li>
        <li class="breadcrumb-item active">Sold Items</li>
      </ol>

     <div class="row card shadow mb-3">
        <div class="card-header text-dark mb-4" style="font-size: 20px; font-weight:700;">
          Sold Items with Serial Numbers
        </div>
        <div class="card-body p-0 m-0">
          <div class="card-body p-0 m-0">
            <div class="table-responsive">
              <div style="display: flex; justify-content: space-between; margin: 20px">
                <div>
                  <!-- <label class="d-inline">Search : </label> -->
                  <input type="text" v-model="searchTerm2" class="form-control d-inline" style="width:500px;" placeholder="Search Invoice Number or Serial Number">
                </div>
                <div style="display:flex; justify-content: end">
                    <vue-daterange-picker double start-date="12/01/2022" end-date="12/01/2023"
                        start-place-holders="12/01/2000" end-place-holders="04/01/2030" @get-dates="getDates" />
                </div> 
                <div>
                  <button class="btn btn-outline-secondary" type="button" @click="clear()">
                   Reset&nbsp;Date&nbsp;Range
                  </button>
                  <button class="btn btn-outline-secondary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                    Toggle&nbsp;Summary
                  </button>
                </div>
              </div>
             <br>
             <!------------- collapse ------------------->
            <div class="collapse" id="collapseExample">
                <div style="width: 40%; margin: 0 auto;">  
                  <table class="table responsive-table">
                    <thead>
                      <th v-if="sort.laptop != 0"> <small> <b>Laptop</b> </small> </th>
                      <th v-if="sort.pc != 0"> <small> <b>Pc Packages</b> </small> </th>
                      <th v-if="sort.comps != 0"> <small> <b>Pc Peripherals</b> </small> </th>
                    </thead>
                    <tbody>
                      <tr style="text-align: center;">
                        <td v-if="sort.laptop != 0"> <h5>{{ sort.laptop }}</h5> </td>
                        <td v-if="sort.pc != 0"> <h5>{{ sort.pc }}</h5> </td>
                        <td v-if="sort.comps != 0"> <h5>{{ sort.comps }}</h5> </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
            </div>
              <!-------------- collapse ------------------>
             <br>
              <table class="table table-bordered table-striped table-hover table-warning border-lights" id="" width="100%" cellspacing="0">
                <thead>
                  <tr class="bg-info text-white">
                    <th>Invoice&nbsp;#</th>
                    <th>Serial&nbsp;Number</th>
                    <th>Item&nbsp;Name</th>
                    <!-- <th>Category</th> -->
                    <th>Customer&nbsp;Name</th>
                    <th>Created&nbsp;By</th>
                    <th>Date&nbsp;Sold</th>
                    <th>product id</th>
                  </tr>
                </thead>

                <tbody>
                  <tr v-for="item in filterSearchWithDate" :key="item.id">
                    <td>{{item.invoiceNumber}}</td>
                    <td>{{item.serialNo}}</td>
                    <td>{{ item.product_name }}</td>
                    <!-- <td>{{ item.category_id }}</td> -->
                    <td>{{item.customerName}}</td>
                    <td>{{returnName(item.created_by)}}</td>
                    <td>{{ returnStrtingDate(item.created_at) }}</td>
                    <td>{{ item.product_id }}</td>
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
import moment from 'moment'
import VueDaterangePicker from 'vue-daterange-picker';
import Cookies from 'js-cookie';
  export default {
    components: {
        VueDaterangePicker,
  
    },
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
          userNow: Cookies.get('userNow'),
          startDate:'',
          endDate:'',
          labels: ['January', 'February', 'March'],
          datasets: [{ data: [40, 20, 12] }]
        
        }
      },
      options: {
        responsive: true
      },
     computed:{
       filtersearch2(){
        return this.soldItems.filter(item => {
          return item.invoiceNumber.toLowerCase().match(this.searchTerm2.toLowerCase()) || item.serialNo.toLowerCase().match(this.searchTerm2.toLowerCase())
         })
       },
       filterSearchWithDate() {
            let filtered = this.filtersearch2.filter((x) => {
                if (moment(new Date(x?.created_at)).format('M/D/YYYY') >= moment(new Date(this.startDate)).format('M/D/YYYY') && moment(new Date(x?.created_at)).format('M/D/YYYY') <= moment(new Date(this.endDate)).format('M/D/YYYY')) {
                    return x;
                } else {
                  console.log('walang returnnnnnn');
                }
            });

            if (this.startDate && this.endDate) {
              console.log(true)
                return filtered;xa
            } else {
              console.log(false)
                return this.filtersearch2;
            }
        },
       sort() {
        let laptop = this.filterSearchWithDate.filter((x) => x.category_id == 1);
        let pc = this.filterSearchWithDate.filter((x) => x.category_id == 2);
        let comps = this.filterSearchWithDate.filter((x) => x.category_id == 3);

        return {
          laptop:laptop.length,
          pc:pc.length,
          comps:comps.length
        }
       }
     },
      methods: {
        returnName(id) {
          let a = this.users.filter((p) => p.id == id);
          return a[0]?.name;
        },
        getDates(i) {
            this.startDate = moment(new Date(i.startDate)).format('M/D/YYYY');
            this.endDate = moment(new Date(i.endDate)).format('M/D/YYYY');
        },
        clear() {
          location.reload();
        },
        returnStrtingDate(petsa) {
          return moment(petsa).format("MMM Do YY");
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
th {
  text-align: center;
}
</style>
