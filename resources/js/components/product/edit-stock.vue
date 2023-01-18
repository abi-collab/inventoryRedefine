<template>
    <div>
        <!-- Breadcrumbs-->
        <ol class="breadcrumb mt-3">
            <li class="breadcrumb-item">
                <router-link to="/home">Dashboard</router-link>
            </li>
            <li class="breadcrumb-item active">Stock / Edit</li>
        </ol>
        <!-- Icon Cards-->
        <div class="row container">
            <div class="card col-lg-12 border-light shadow">
                <div class="card-header text-dark mb-4" style="font-size: 20px; font-weight: 700;">
                    <i class="fas fa-chart-area"></i>
                    Product Stock Update
                </div>
                <form @submit.prevent="confirm" >
                    <div class="card-body p-0 m-0">
                        <div class="form-group">
                            <div class="row">
                                 <div class="col-md-4">
                                <div class="form-label-group">
                                    <label for="phone">Current Stock Available </label>
                                    <input type="text" v-model="form.product_quantity" class="form-control" disabled>
                                    <small class="text-danger" v-if="errors.product_quantity">{{ errors.product_quantity[0] }}</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-label-group">
                                    <label for="phone">Additional Stock-in</label>
                                    <input type="text" v-model="toAdd_quantity" class="form-control" required>
                                    
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-label-group">
                                    <label for="phone">New Stock Quantity</label>
                                    <input type="text" :value="newStock" class="form-control" disabled>
                               
                                </div>
                            </div>
                            </div>
                           
                        </div>
                    </div><br>
                    <div class="row">
                         <div class="col-lg-4">
                         <h6 v-if="toAdd_quantity > 0">Please Enter {{ toAdd_quantity }} item's Serial Numbers</h6>
                        <span v-for="z in serialQuantity" style="display: block;">
                            <input 
                                type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default"
                                style="margin: 2px 0px"
                                v-model="z.serial_number"
                                @keypress.13.prevent
                                required
                            >  
                        </span>
                    </div>
                    <div class="col-lg-4">
                        <h6>Select Supplier</h6>
                        <model-select :options="selectSuppliers" v-model="item" placeholder="select item" aria-required="true" required>
                        </model-select>
                    </div>
                    </div>
                    <button type="submit" class="btn btn-success" v-if="toAdd_quantity">Stock Update</button>
                </form><br>
                
            </div>
        </div>
    </div>
</template>


<script>
import { ModelSelect } from 'vue-search-select'
import Cookies from 'js-cookie'; //1
    export default {
        components: {
        ModelSelect
    },
        mounted(){
            if (!User.loggedIn()) {
                this.$router.push({ name:'/' })
            }
        },
        data(){
            return{
                form:{
                    product_quantity:''  
                },
                form2:{ //2
					activity :'',
					createdby : Cookies.get('usersname')
				},
                errors:{},
                allProducts:[],
                toAdd_quantity:'',
                newStock_quantity:'',
                serialQuantity:[],
                suppliers:[],
                item:''
            }
        },
        created(){
            let id = this.$route.params.id
            axios.get('/api/product/'+id)
                .then(({data}) => (this.form = data))
                .catch()

            axios.get('/api/product')
                .then(({data}) => (console.log( data), this.allProducts = data))
                .catch()
            
            
            axios.get('/api/supplier/')
                .then((data) => {
                   this.suppliers = data.data;
                }) 
                .catch()
         
        },
        computed: { //3
					nameIs() {
                        let id = this.$route.params.id
                      let pro = this.allProducts.filter(item => item.id == id)
						return pro[0]?.product_name;
					},
                    newStock() {
                        let id = this.$route.params.id
                        let newArr = [];
                        this.newStock_quantity = Number(this.form.product_quantity) + Number(this.toAdd_quantity);

                    for(let i = 0; i < this.toAdd_quantity; i++) {
                        newArr.push({
                            serial_number:'', 
                            product_id:id
                        });
                    }

                        this.serialQuantity = newArr;
                        return Number(this.form.product_quantity) + Number(this.toAdd_quantity);
                    },
                    getTimestampInSeconds () {
                    return Math.floor(Date.now() / 1000)
                    },

                    ///////////////// for select option
                    selectSuppliers() {
                        let newArr = [];
                        for(let i = 0; i < this.suppliers.length; i++) {
                            newArr.push({
                                value:this.suppliers[i].id,
                                text:this.suppliers[i].name
                            })
                        }
                         return newArr; 
                    }
				},
        methods:{
            stockUpdate(){
                this.form2.activity = `update product quantity of ${this.nameIs}, added ${this.toAdd_quantity} new stock to ${this.form.product_quantity} existing in-stock.`;//4
                let id = this.$route.params.id
                axios.post('/api/stock/update/'+id,{product_quantity:this.newStock})
                    .then(() => {
                        this.$router.push({ name: 'stock' })
                        Notification.success()
                    })
                    .catch(error => this.errors = error.response.data.errors)

                    axios.post('/api/activitylog',this.form2)  //5
                    .then((r) => {
                        console.log('logssss',r)
                    })
                    .catch(error => this.errors = error.response.data.errors)

                    for(let i= 0; i < this.serialQuantity.length; i++) {
                        this.serialQuantity[i].supplier_id = this.item;
                        axios.post('/api/saveSerialNumbers', this.serialQuantity[i]).then((res) => {
                            console.log(res);
                        })
                    }
                    
            },
            confirm() {
                if(!this.item) {
                    Swal.fire('Please select a supplier')
                } else {
                    const swalWithBootstrapButtons = Swal.mixin({
					customClass: {
						confirmButton: 'btn btn-success',
						cancelButton: 'btn btn-danger'
					},
					buttonsStyling: true
				})

				swalWithBootstrapButtons.fire({
					title: 'Are you sure?',
					text: "This cannot be undone",
					icon: 'warning',
					showCancelButton: true,
					confirmButtonText: 'Confirm',
					cancelButtonText: 'Cancel',
					reverseButtons: true
				}).then((result) => {
					if (result.isConfirmed) {
						this.stockUpdate();
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
    }
</script>


<style>
</style>
