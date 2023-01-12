<template>
    <div>
        <!-- Breadcrumbs-->
        <ol class="breadcrumb mt-3">
            <li class="breadcrumb-item">
                <router-link to="/home">Dashboard</router-link>
            </li>
            <li class="breadcrumb-item active">Supplier / Edit</li>
        </ol>
        <!-- Icon Cards-->
        <div class="row container">
            <div class="card col-lg-12 border-light shadow">
                <div class="card-header text-dark mb-4" style="font-size: 20px; font-weight: 700;">
                    <i class="fas fa-chart-area"></i>
                    Supplier Update
                    <router-link to="/supplier" class="btn btn-dark" id="add_new"> All Supplier</router-link>
                </div>
                <div class="card-body p-0 m-0">
                    <form @submit.prevent="confirm" enctype="multipart/form-data">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label>Full Name</label>
                                    <div class="form-label-group">
                                        <input type="text" v-model="form.name" class="form-control" required>
                                        <small class="text-danger" v-if="errors.name">{{ errors.name[0] }}</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-label-group">
                                        <label>Email Address</label>
                                        <input type="text" v-model="form.email" class="form-control">
                                        <small class="text-danger" v-if="errors.email">{{ errors.email[0] }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-label-group">
                                        <label>Address</label>
                                        <input type="text" v-model="form.address" class="form-control" required>
                                        <small class="text-danger" v-if="errors.address">{{ errors.address[0] }}</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-label-group">
                                        <label >Shop Name</label>
                                        <input type="text" v-model="form.shopname" class="form-control" required>
                                        <small class="text-danger" v-if="errors.shopname">{{ errors.shopname[0] }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="form-label-group">
                                        <label >Phone Number</label>
                                        <input type="text" v-model="form.phone" class="form-control" required="">
                                        <small class="text-danger" v-if="errors.phone">{{ errors.phone[0] }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-label-group">
                                        <input type="file" class="btn btn-info" @change="onFileselected">
                                        <small class="text-danger" v-if="errors.photo">{{ errors.photo[0] }}</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <img :src="'/'+form.photo" style="height:40px; width: 40px;">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Update</button>
                    </form>
                </div>
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
                    name :'',
                    email :'',
                    salary:'',
                    address:'',
                    photo :'',
                    newphoto:'',
                    phone:'',
                    nid : '',
                    joining_date:''
                },
                errors:{},
                oldName:'',
                form2:{ //2
                    activity :'',
                    createdby : Cookies.get('usersname')
                },
            }
        },
        created(){
            let id = this.$route.params.id
            axios.get('/api/supplier/'+id)
                .then(({data}) => (this.form = data,
                this.oldName = this.form.name))
                .catch()
        },
        computed: { //3
					nameIs() {
						return this.form.name;
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
            supplierUpdate(){
                if(this.nameIs == this.oldName) {
							this.form2.activity = `Update info of supplier ${this.nameIs }`
						} else {
							this.form2.activity = `supplier update: changed name from ${this.oldName} to ${this.nameIs}`;//4
						}
                let id = this.$route.params.id
                axios.patch('/api/supplier/'+id,this.form)
                    .then(() => {
                        this.$router.push({ name: 'supplier' })
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
						this.supplierUpdate();
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
