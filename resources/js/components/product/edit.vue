<template>
    <div>
        <!-- Breadcrumbs-->
        <ol class="breadcrumb mt-3">
            <li class="breadcrumb-item">
                <router-link to="/home">Dashboard</router-link>
            </li>
            <li class="breadcrumb-item active">Product / Edit</li>
        </ol>
        <!-- Icon Cards-->
        <div class="row container">
            <div class="card col-lg-12 border-light shadow mb-3">
                <div class="card-header text-dark mb-4" style="font-size: 20px; font-weight: 700;">
                    <i class="fas fa-chart-area"></i>
                    Product Update
                    <router-link to="/product" class="btn btn-dark" id="add_new"> All Product</router-link>
                </div>
                <div class="card-body p-0 m-0">
                    <form @submit.prevent="confirm" enctype="multipart/form-data">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-label-group">
                                        <label for="firstName">Product Name</label>
                                        <input type="text" v-model="form.product_name" class="form-control" required>
                                        <small class="text-danger" v-if="errors.product_name">{{ errors.product_name[0] }}</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-label-group">
                                        <label for="lastName">Product Code</label>
                                        <input type="text" v-model="form.product_code" class="form-control">
                                        <small class="text-danger" v-if="errors.product_code">{{ errors.product_code[0] }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Select1">Category</label>
                                        <select class="form-control" id="Select1" v-model="form.category_id">
                                            <option :value="category.id" v-for="category in categories">{{ category.category_name }}</option>
                                        </select>
                                        <small class="text-danger" v-if="errors.category_id">{{ errors.category_id[0] }}</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Select2">Supplier</label>
                                        <select class="form-control" id="Select2" v-model="form.supplier_id">
                                            <option :value="supplier.id" v-for="supplier in suppliers">{{ supplier.name }}</option>
                                        </select>
                                        <small class="text-danger" v-if="errors.supplier_id">{{ errors.supplier_id[0] }}</small>
                                    </div>
                                </div>
                            </div>
                        </div><br>
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-4">
                                    <div class="form-label-group">
                                        <label for="firstName">Root</label>
                                        <input type="text" v-model="form.root" class="form-control" required>
                                        <small class="text-danger" v-if="errors.root">{{ errors.root[0] }}</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-label-group">
                                        <label for="nid">Buying Price</label>
                                        <input type="text" v-model="form.buying_price" class="form-control" required>
                                        <small class="text-danger" v-if="errors.buying_price">{{ errors.buying_price[0] }}</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-label-group">
                                        <label for="phone">Selling Price </label>
                                        <input type="text" v-model="form.selling_price" class="form-control" required>
                                        <small class="text-danger" v-if="errors.selling_price">{{ errors.selling_price[0] }}</small>
                                    </div>
                                </div>
                            </div>
                        </div><br>
                        <!-- <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-label-group">
                                        <label for="nid">Buying Date</label>
                                        <input type="date" v-model="form.buying_date" class="form-control" required>
                                        <small class="text-danger" v-if="errors.buying_date">{{ errors.buying_date[0] }}</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-label-group">
                                        <label for="phone">Quantity</label>
                                        <input type="text" v-model="form.product_quantity" class="form-control" required>
                                        <small class="text-danger" v-if="errors.product_quantity">{{ errors.product_quantity[0] }}</small>
                                    </div>
                                </div>
                            </div>
                        </div><br> -->
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-label-group">
                                        <input type="file" class="btn btn-info" @change="onFileselected">
                                        <small class="text-danger" v-if="errors.image">{{ errors.image[0] }}</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <img :src="'/'+form.image" style="height:40px; width: 40px;">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Update</button>
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
                    product_name :'',
                    product_code :'',
                    category_id:'',
                    supplier_id:'',
                    root :'',
                    buying_price:'',
                    selling_price : '',
                    buying_date:'',
                    image:'',
                    newphoto:'',
                    product_quantity:''
                },
                errors:{},
                categories:{},
                suppliers:{},
                oldName:'',
                form2:{ //2
                    activity :'',
                    createdby : Cookies.get('usersname')
                },
            }
        },
        created(){
            let id = this.$route.params.id
            axios.get('/api/product/'+id)
                .then(({data}) => (this.form = data,
                this.oldName = this.form.product_name))
                .catch()
            //--category collected--
            axios.get('/api/category')
                .then(({data}) => (this.categories = data))
            //--supplier colledted--
            axios.get('/api/supplier/')
                .then(({data}) => (this.suppliers = data))
        },
        computed: { //3
					nameIs() {
						return this.form.product_name;
					}
				},
        methods:{
            onFileselected(event){
                let file=event.target.files[0];
                if (file.size > 1048770) {
                    Notification.image_validation()
                }else{
                    let reader = new FileReader();
                    reader.onload = event => {
                        this.form.newphoto = event.target.result
                    };
                    reader.readAsDataURL(file);
                }
            },
            productUpdate(){
                if(this.nameIs == this.oldName) {
							this.form2.activity = `Update info of product ${this.nameIs }`
						} else {
							this.form2.activity = `product update: changed name from ${this.oldName} to ${this.nameIs}`;//4
						}
                let id = this.$route.params.id
                axios.patch('/api/product/'+id,this.form)
                    .then(() => {
                        this.$router.push({ name: 'product' })
                        Notification.success();
                        axios.post('/api/activitylog',this.form2)  //5
                        .then((r) => {
                            console.log('logssss',r)
                        })
                        .catch(error => this.errors = error.response.data.errors)
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
					text: "Following details can still be update when saved",
					icon: 'warning',
					showCancelButton: true,
					confirmButtonText: 'Confirm',
					cancelButtonText: 'Cancel',
					reverseButtons: true
				}).then((result) => {
					if (result.isConfirmed) {
						this.productUpdate();
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
