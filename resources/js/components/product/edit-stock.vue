<template>
    <div>
        <!-- Breadcrumbs-->
        <ol class="breadcrumb mt-3">
            <li class="breadcrumb-item">
                <a href="#">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Stock / Edit</li>
        </ol>
        <!-- Icon Cards-->
        <div class="row container">
            <div class="card col-lg-12 border-secondary shadow">
                <div class="card-header text-dark" style="font-size: 20px; font-weight: 700;">
                    <i class="fas fa-chart-area"></i>
                    Product Stock Update
                </div>
                <form @submit.prevent="stockUpdate" >
                    <div class="card-body">
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="form-label-group">
                                    <label for="phone">Quantity  </label>
                                    <input type="text" v-model="form.product_quantity" class="form-control" required>
                                    <small class="text-danger" v-if="errors.product_quantity">{{ errors.product_quantity[0] }}</small>
                                </div>
                            </div>
                        </div>
                    </div><br>
                    <button type="submit" class="btn btn-success">Stock Update</button>
                </form><br>
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
                    product_quantity:''
                },
                form2:{ //2
					activity :'',
					createdby : Cookies.get('usersname')
				},
                errors:{},
                allProducts:[]
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
        },
        computed: { //3
					nameIs() {
                        let id = this.$route.params.id
                      let pro = this.allProducts.filter(item => item.id == id)
						return pro[0]?.product_name;
					}
				},
        methods:{
            stockUpdate(){
                this.form2.activity = `update product quantity of ${this.nameIs}`;//4
                let id = this.$route.params.id
                axios.post('/api/stock/update/'+id,this.form)
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
            },
        }
    }
</script>


<style>
</style>
