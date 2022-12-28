<template>
    <div>
        <!-- Breadcrumbs-->
        <ol class="breadcrumb mt-3">
            <li class="breadcrumb-item">
                <router-link to="/home">Dashboard</router-link>
            </li>
            <li class="breadcrumb-item active">Order / Order Details</li>
        </ol>

        <!-----Order------->
        <div class="row container-fluid">
            <div class="card col-lg-12 border-light shadow mb-3">
                <div class="card-header text-dark mb-4" style="font-size: 20px; font-weight: 600;">
                    <i class="fas fa-chart-area"></i>
                    Order invoice No.&nbsp;:&nbsp;<span style="letter-spacing: 2px;">{{orders.invoiceNum}}</span>
                </div>
                <div class="card-body p-0 m-0">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-6">
                            <ul class="list-group">
                                <li class="list-group-item bg-info text-white"><b>Customer Details</b></li>
                                <li class="list-group-item"> <b>Name:</b> {{ orders.name }}</li>
                                <li class="list-group-item"> <b>Phone:</b>{{ orders.phone }}</li>
                                 <li class="list-group-item"> <b>Address:</b>{{ orders.address }}</li>
                                  <li class="list-group-item"> <b>Date:</b>{{orders.order_date }}</li>
                                
                            </ul>
                        </div>
                        <div class="col-lg-6 col-md-6 col-6">
                            <ul class="list-group">
                                <li class="list-group-item"> <b>Quantity:</b>{{ orders.qty }}</li>
                                <li class="list-group-item"> <b>Total Due:</b>  &#8369;&nbsp; {{(Number(orders.sub_total).toLocaleString() || 0)}}</li>
                                <li class="list-group-item"> <b>Cash Tendered:</b> &#8369;&nbsp; {{(Number(orders.pay).toLocaleString() || 0)}}</li>
                                <li class="list-group-item"> <b>Change:</b> &#8369;&nbsp; {{(Number(orders.change).toLocaleString() || 0)}}</li>
                                <li class="list-group-item"> <b>Pay Through:</b> {{ orders.payby }}</li>
                               
                            </ul>
                        </div>
                       
                    </div>
                </div>
                <div class="card-footer small text-muted"></div>
            </div>
        </div> <br>


        <!--------Order Details--------->
        <div class="row container-fluid">
            <div class="card col-lg-12 border-light shadow mb-3">
                <div class="card-header text-dark mb-4" style="font-size: 20px; font-weight: 700;">
                    <i class="fas fa-chart-area"></i>
                    Order Details
                </div>
                <div class="card-body p-0 m-0">
                    <div class="table-responsive">
                        <!-- <label class="d-inline">Search : </label>
                        <input type="text" v-model="searchTerm" class="form-control d-inline" style="width:200px;" placeholder="Search by name"><br><br> -->
                        <table class="table table-bordered table-striped table-hover table-warning border-light" id="" width="100%" cellspacing="0">

                            <thead>
                                <tr class="bg-info text-white">
                                    <th>Product Name</th>
                                    <th>Code</th>
                                    <th>Image</th>
                                    <th>Qty</th>
                                    <th>Unit Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="detail in details" :key="detail.id">
                                    <td>{{ detail.product_name}}</td>
                                    <td>{{ detail.product_code}}</td>
                                    <td><img :src="'/'+detail.image" id="em_photo"></td>
                                    <td>{{ detail.pro_quantity }}</td>
                                    <td>&#8369;&nbsp; {{(Number(detail.product_price).toLocaleString() || 0)}}</td>
                                    <td>&#8369;&nbsp; {{(Number(detail.sub_total).toLocaleString() || 0)}}</td>
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
        data(){
            return{
                //errors:{},
                orders:{},
                details:{},
                searchTerm:''
            }
        },
        created(){
            let id = this.$route.params.id
        	axios.get('/api/order/details/'+id)
        	.then(({data}) => (this.orders = data))
            .catch()

        	axios.get('/api/order/orderdetails/'+id)
        	.then(({data}) => (this.details = data))
            .catch()
        },
        computed: {
            // filtersearch(){
            //     let a = this.details.filter((item) => {
            //         return item.product_name.toLowerCase().match(this.searchTerm.toLowerCase()) || item.product_code.toLowerCase().match(this.searchTerm.toLowerCase())
            //     })

            //     return a;
            // }
          
        }
       
    }
</script>


<style scoped>
    #em_photo{
        height: 40px;
        width: 40px;
    }

    .list-group-item {
        display: flex;
        justify-content: space-between;
    }
</style>
