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
                <form @submit.prevent="stockUpdate" >
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
                    <button type="submit" class="btn btn-success" v-if="toAdd_quantity">Stock Update</button>
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
                allProducts:[],
                toAdd_quantity:'',
                newStock_quantity:''
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
					},
                    newStock() {
                        this.newStock_quantity = Number(this.form.product_quantity) + Number(this.toAdd_quantity);
                        return Number(this.form.product_quantity) + Number(this.toAdd_quantity);
                    },
                    getTimestampInSeconds () {
                    return Math.floor(Date.now() / 1000)
                    }
				},
        methods:{
            stockUpdate(){
                this.form2.activity = `update product quantity of ${this.nameIs}`;//4
                let id = this.$route.params.id
                let dateNow = new Date();
                // let day = dateNow.getDate();
                // let month = dateNow.getMonth() + 1;
                // let year = dateNow.getFullYear(); 
                // let formatedDate = year + '/' + month + '/' + day;
                // console.log(formatedDate);
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
            },
        }
    }
</script>


<style>
</style>
