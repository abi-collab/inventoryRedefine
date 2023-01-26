<template>
  <div>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb mt-3">
        <li class="breadcrumb-item">
          <router-link to="/home">Dashboard</router-link>
        </li>
        <li class="breadcrumb-item active">Activity Logs (Audit Trail)</li>
      </ol>
      <!-- Icon Cards-->
     <div class="row card shadow mb-3">
        <div class="card-header text-dark mb-4" style="font-size: 20px; font-weight:700;">
          Audit Trail(Logs)
        </div>
        <div class="card-body p-0 m-0">
          <div class="card-body p-0 m-0">
            <div class="table-responsive">
              <div class="row px-5">
                <div class="col">
                   <label class="d-inline">Search : </label>
                    <input type="text" v-model="searchTerm2" class="form-control d-inline" style="width:200px;" placeholder="Search by activity">
                </div>
                <div class="col">
                  <div style="display:flex; justify-content: end">
                    <vue-daterange-picker double start-date="12/01/2022" end-date="12/01/2023"
                        start-place-holders="12/01/2000" end-place-holders="04/01/2030" @get-dates="getDates" />
                </div>
                </div>
                <div class="col">
                  <button class="btn btn-outline-secondary" type="button" @click="clear()">
                   Reset&nbsp;Date&nbsp;Range
                  </button>
                </div>
              </div>
             <br>
             <br>
             
              <table class="table table-bordered table-striped table-hover table-warning border-lights" id="" width="100%" cellspacing="0">
                <thead>
                  <tr class="bg-info text-white">
                    <th>Activity</th>
                    <th>Date</th>
                    <th>Created&nbsp;By</th>
                   
                  </tr>
                </thead>

                <tbody>
                  <tr v-for="log in filterSearchWithDate" :key="log.id">
                    <td>{{ log.activity }}</td>
                    <td>{{log.created_at}}</td>
                    <td>{{log.createdby}}</td>
                    
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
          axios.get('/api/activitylog')
          .then(({data}) => (this.logs = data))
          .catch()

          axios.get('/api/users')
          .then(({data}) => (this.users = data, console.log('users', data)))
          .catch()

          
      },
      data(){
        return{
          customers:[],
          logs:[],
          users:[],
          searchTerm:'',
          searchTerm2:'',
          userNow: Cookies.get('userNow'),
          startDate:'',
          endDate:'',
        
        }
      },
     computed:{
       filtersearch2(){
        return this.logs.filter(log => {
          return log.activity.toLowerCase().match(this.searchTerm2.toLowerCase())
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
     },
      methods: {
        getDates(i) {
            this.startDate = moment(new Date(i.startDate)).format('M/D/YYYY');
            this.endDate = moment(new Date(i.endDate)).format('M/D/YYYY');
        },
        clear() {
          location.reload();
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
