 <template>
    <div>
        <!-- Breadcrumbs-->
        <ol class="breadcrumb mt-3">
          <li class="breadcrumb-item">
            <router-link to="/home">Dashboard</router-link>
          </li>
          <li class="breadcrumb-item active">Expense / Insert</li>
        </ol>
        <!-- Icon Cards-->
       <div class="row container">
         <div class="card col-lg-12 border-light shadow">
          <div class="card-header text-dark mb-4" style="font-size: 20px; font-weight: 700;">
            <i class="fas fa-chart-area"></i>
            Expense Insert
            <router-link to="/expense" class="btn btn-dark" id="add_new"> All Expense</router-link>
          </div>

          <div class="card-body p-0 m-0">
            <form @submit.prevent="confirm">
                <div class="form-group">
	              <div class="form-row">
	               <div class="col-md-12">
	               	<div class="form-group">
				       <label for="Textarea1">Details Of Expense</label>
				       <textarea class="form-control" id="Textarea1" rows="3" v-model="form.details" placeholder="Enter Details"></textarea>
				       <small class="text-danger" v-if="errors.details">{{ errors.details[0] }}</small>
				     </div>
	               </div>
	               <div class="col-lg-12">
	               	<div class="form-label-group">
                      <label for="firstName">Expense Amount</label>
	                  <input type="text" v-model="form.amount" class="form-control" required placeholder="Enter Amount">
	                  <small class="text-danger" v-if="errors.amount">{{ errors.amount[0] }}</small>
	                </div>
	               </div>
	             </div>
	            </div>
              <button type="submit" class="btn btn-success">Submit</button>
            </form>
         </div>
         <div class="card-footer small text-muted"></div>
       </div>
   </div>
   </div>
</template>


<script>
import Cookies from 'js-cookie'; //1
    export default {
    	mounted(){
            if (!User.loggedIn()) {
               this.$router.push({ name:'/' })
            }
        },
        data(){
        	return{
        		form:{
        			details :'',
        			amount:''
        		},
            form2:{ //2
              activity :'',
              createdby : Cookies.get('usersname')
            },
        		errors:{},
        	}
        },
        computed: { //3
					nameIs() {
						return this.form.details;
					}
				},
        methods:{
        	expenseInsert(){
            this.form2.activity = `create expense, details: ${this.nameIs}`;//4
        		axios.post('/api/expense/',this.form)
        		.then(() => {
        			this.$router.push({ name: 'expense' })
        			Notification.success()
        		})
        		.catch(error => this.errors = error.response.data.errors)


            axios.post('/api/activitylog',this.form2)  //5
              .then((r) => {
                  console.log('logssss',r)
              })
              .catch(error => this.errors = error.response.data.errors)
        	},
          confirm() {
            const swalWithBootstrapButtons = Swal.mixin({
              customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
              },
              buttonsStyling: true
            })

            swalWithBootstrapButtons.fire({
              title: 'Are you sure?',
              text: "Entered details can still be update when saved",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonText: 'Confirm',
              cancelButtonText: 'Cancel',
              reverseButtons: true
            }).then((result) => {
              if (result.isConfirmed) {
                this.expenseInsert();
                swalWithBootstrapButtons.fire('Successfully Saved')
              } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire(
                  'Cancelled',
                )
              }
            })
          }
        }
    }
</script>


<style>
#add_new{
	float: right;
}
</style>
