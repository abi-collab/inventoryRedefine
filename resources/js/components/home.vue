
<template>
    <div>   <!-----total_<div>----->
<!--------------------Dashboard------------------------>
<!-- <h1 class="mt-4">Dashboard</h1> -->
<ol class="breadcrumb mt-4">
    <li class="breadcrumb-item active">Dashboard</li>
</ol>
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card mb-4">
            <div v-if="!income" class="card-body">
                <div class="spinner-grow text-info" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <div class="card-body" v-else><h4> &#8369; {{ (Number(income).toLocaleString() || 0)}} </h4></div>
            <router-link to="/order" class="card-footer d-flex align-items-center justify-content-between" style="color: black;">
                <a class="stretched-link" href="#">Daily Sales</a>
                <div><i class="fas fa-angle-right"></i></div>
            </router-link>
        </div>
    </div >
    <!-- <div class="col-xl-3 col-md-6">
        <div class="card mb-4">
            <div v-if="!categories" class="card-body">
                <div class="spinner-grow text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <div class="card-body" v-else><h4>{{ categories.length }} </h4></div>
            <router-link to="/category" class="card-footer d-flex align-items-center justify-content-between" style="color: black;">
                <a class="stretched-link" href="#">Stock Category</a>
                <div><i class="fas fa-angle-right"></i></div>
            </router-link>
        </div>
    </div> -->
    <div class="col-xl-3 col-md-6">
        <div class="card mb-4">
            <div v-if="!soldItems" class="card-body">
                <div class="spinner-grow text-success" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <div class="card-body" v-else><h4>{{ soldItems.length }}  </h4></div>
            <router-link to="/sold-items" class="card-footer d-flex align-items-center justify-content-between" style="color: black;">
                <a class="stretched-link" href="#">Items Sold</a>
                <div><i class="fas fa-angle-right"></i></div>
            </router-link>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card mb-4">
            <div v-if="!products" class="card-body">
                <div class="spinner-grow text-warning" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <div class="card-body" v-else><h4>&#8369; {{ (Number(expensez).toLocaleString() || 0)}} </h4></div>
            <router-link to="/expense" class="card-footer d-flex align-items-center justify-content-between" style="color: black;">
                <a class="stretched-link" href="#">Expense</a>
                <div><i class="fas fa-angle-right"></i></div>
            </router-link>
        </div>
    </div>
  
</div>      <!------End_Dashboard------>

<!------Stock_Out_Products------>
<div class="row">
    <div class="col-xl-12">
        <div class="card border-light">
            <div class="card-header text-dark font-weight-bold">
                <i class="fas fa-chart-area mr-1"></i>
                Stock Out Products
            </div>
            <div class="card-body p-0 m-0" style="background-color: #eee;" >
                <div v-if="!products" style="display:flex; justify-content: center; margin: 50px 0;">
                     <div class="spinner-border" role="status" style="margin: 0 auto"></div>
                </div>
               
                <div class="card-body p-0 m-0 " v-else>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover table-warning" id="" width="100%" cellspacing="0">
                            <thead>
                                <tr class="bg-info text-white">
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Photo</th>
                                    <th>Status</th>
                                    <th>Available Product</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="product in products" :key="product.id">
                                    <td>{{ product.product_name}}</td>
                                    <td>{{ product.product_code}}</td>
                                    <td><img :src="product.image" id="em_photo"></td>
                                    <td v-if="product.product_quantity >= 1"><span class="badge badge-success">Availble</span></td>
                                    <td v-else=""><span class="badge badge-danger">Stock Out</span></td>
                                    <td>{{ product.product_quantity }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>  <!------End_Stock_ Out_Products------>
    </div>  <!------End_total_<div>------>
</template>


<script>
import Cookies from 'js-cookie'; 
    export default {
        created(){
            if (!User.loggedIn()) {
                this.$router.push({name : '/'})
                  this.$router.push('/dashboard')
            }
            // window.location.reload();
            axios.get('/api/serials')
                .then(({data}) => (this.soldItems = data))
                .catch()
        },
        mounted(){
            if(!Cookies.get('userId')) {
                // this.$router.push({name : '/logout'})
                this.$router.push('/logout');
    
            }
            if (localStorage.getItem('reloaded')) {
                // The page was just reloaded. Clear the value from local storage
                // so that it will reload the next time this page is visited.
                localStorage.removeItem('reloaded');
            } else {
                // Set a flag so that we know not to reload the page twice.
                localStorage.setItem('reloaded', '1');
                window.location.reload();
            }

            this.TodaySell();
            // this.TodayIncome();
            // this.TodayDue();
            // this.TodayExpense();
            // this.Stockout();

            // this.allCategory();
            // this.allExpense();
            
        },
        data(){
            return{
                todaysell:'',
                income:'',
                expense:'',
                due:'',
                products:'',
                categories:'',
                soldItems:'',
                expenses:[]
            }
        },
        computed: {
            expensez() {
                let allCost = [];
                for(let i=0; i < this.expenses.length; i++) {
                    allCost.push(Number(this.expenses[i].amount));
                }
                return allCost.reduce(function(acc, val) { return acc + val; }, 0);
            }
        },
        methods:{
            TodaySell(){
                axios.get('/api/today/sell')
                    .then(({data}) => {
                        this.todaysell = data;
                        axios.get('/api/today/income')
                            .then(({data}) => {
                                this.income = data;
                                    axios.get('/api/today/due')
                                        .then(({data}) => {
                                            this.due = data;
                                            axios.get('/api/today/expense')
                                                .then(({data}) => {
                                                    this.expense = data;
                                                    axios.get('/api/stockout')
                                                        .then(({data}) => {
                                                            this.products = data;
                                                            axios.get('/api/category/')
                                                                .then(({data}) => {
                                                                    this.categories = data;
                                                                    axios.get('/api/expense/')
                                                                        .then(({data}) => (this.expenses = data))
                                                                })
                                                        })
                                                })
                                        })
                            })
                    .catch()
                    }  
                        )
                    .catch()
            },
            // TodayIncome(){
            //     axios.get('/api/today/income')
            //         .then(({data}) => {
            //             this.income = data;
            //             console.log('income here',data)
            //         })
            //         .catch()
            // },
            // TodayDue(){
            //     axios.get('/api/today/due')
            //         .then(({data}) => (this.due = data))
            // },
            // TodayExpense(){
            //     axios.get('/api/today/expense')
            //         .then(({data}) => (this.expense = data))
            // },
            // Stockout(){
            //     axios.get('/api/stockout')
            //         .then(({data}) => (this.products = data))
            // }, 

            // allCategory(){
            //     axios.get('/api/category/')
            //         .then(({data}) => (this.categories = data))
            //         .catch()
            // },
            // allExpense(){
            //     axios.get('/api/expense/')
            //     .then(({data}) => (this.expenses = data))
            //     .catch()
            // },
        }
    }

</script>


<style scoped>
    #em_photo{
        height: 40px;
        width: 40px;
    }
    a {
        letter-spacing: 1px;
    }
</style>
