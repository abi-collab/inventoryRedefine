<template>
    <div>
        <!-- Breadcrumbs-->
        <ol class="breadcrumb mt-3">
            <li class="breadcrumb-item">
                <router-link to="/home">Dashboard</router-link>
            </li>
            <li class="breadcrumb-item active">Orders / Search Order</li>
        </ol>
        <!-- Icon Cards-->
        <div class="row container-fluid">
            <div class="card col-lg-12 border-light shadow">
                <div class="card-header text-dark mb-4" style="font-size: 20px; font-weight: 700;">
                    <i class="fas fa-chart-area"></i>
                    Search Orders
                    <router-link to="/order" class="btn btn-dark" id="add_new"> Today Order</router-link>
                </div>

<!------------Search By Date---------------->
<div class="card-body p-0 m-0">
    <div class="row">
        <div class="col-lg-6">
            <form @submit.prevent="searchDate">
                <div class="form-group">
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="form-label-group">
                                <label>Search By Date : </label>
                                <input type="date" v-model="date" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Submit</button>
            </form>
        </div>

<!------------Search By Month---------------->
        <div class="col-lg-6">
            <form @submit.prevent="searchMonth">
                <div class="form-group">
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="form-label-group">
                                <label>Search By Month : </label>
                                <select class="form-control" v-model="month">
                                    <option value="January">January</option>
                                    <option value="February">February</option>
                                    <option value="March">March</option>
                                    <option value="April">April</option>
                                    <option value="May">May</option>
                                    <option value="June">June</option>
                                    <option value="July">July</option>
                                    <option value="August">August</option>
                                    <option value="September">September</option>
                                    <option value="October">October</option>
                                    <option value="November">November</option>
                                    <option value="December">December</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Submit</button>
            </form>
        </div>
    </div>
</div>  <!-----------End_Search_text_box------------------>


<!-----------Search_Result------------------>
    <div class="row container-fluid pt-4">
        <div class="card col-lg-12 border-light shadow mb-3">
            <div class="card-header text-dark mb-4" style="font-size: 20px; font-weight: 700;">
                <i class="fas fa-chart-area"></i>
                Searched Order Details
            </div>
            <div class="card-body p-0 m-0">
                
                <div class="table-responsive">
                    <label class="d-inline">Search : </label>   <!-----f----->
                        <input type="text" v-model="searchTerm" class="form-control d-inline" style="width:200px" placeholder="Search by Invoice"><br> <br>
                    <table class="table table-bordered table-striped table-hover table-warning border-light" id="" width="100%" cellspacing="0">
                        <thead>
                            <tr class="bg-info text-white">
                                <th>Invoice&nbsp;#</th>
                                <th>Name</th>
                                <th>Quantity</th>
                                <!-- <th>Subtotal</th>
                                <th>Vat</th> -->
                                <th>Total Due</th>
                                <!-- <th>Pay</th> -->
                                <!-- <th>Due</th> -->
                                <td>View</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="order in filtersearch" :key="order.id">
                                <td>{{ order.invoiceNum }}</td>
                                <td>{{ order.name }}</td>
                                <td style="text-align: right;">{{ order.qty }}</td>
                                <td style="text-align: right;">&#8369;&nbsp; {{(Number(order.sub_total).toLocaleString() || 0)}}</td>
                                <!-- <td>{{ order.vat }}</td>
                                <td>{{ order.total }}</td> -->
                                <!-- <td style="text-align: right;">&#8369;&nbsp; {{(Number(order.pay).toLocaleString() || 0)}}</td> -->
                                <!-- <td>{{ order.due }}</td> -->
                                <td style="text-align: center;">
                                    <router-link :to="{name: 'view-order', params:{id: order.id} }" class="btn btn-sm btn-info">Details</router-link>
                                    <button type="button" @click="ImgToModal(order.invoiceImg)" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">Invoice</button>
                                    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <img :src="modalImg">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <!-- <tfoot>
                            <tr style="border: solid grey 2px">
                                <td colspan="2" style="text-align: center;"> <b>Totals</b></td>
                                <td style="text-align: right;">{{ quantity }} </td>
                                <td style="text-align: right;">&#8369;&nbsp; {{(Number(subtotal).toLocaleString() || 0)}}</td>
                              
                                <td style="text-align: right;"> </td>
                               
                            </tr>
                        </tfoot> -->
                    </table>
                </div>
            </div>
            <div class="card-footer small text-muted"></div>
        </div>
    </div>
    <!--------End_Search_Result------------>


            </div>
        </div>
    </div>
</template>


<script>
import moment from 'moment'
    export default {
        created() {
            let dateNow = new Date();
            let month = dateNow.getMonth() + 1;
            let monthName = moment(month, "DD-MM-YYYY")
                        .format('MMMM')
    
            this.month = monthName;
            this.searchMonth();
        },
        mounted(){
            if (!User.loggedIn()) {
                this.$router.push({ name:'/' })
            }
        },
        data(){
            return{
                date :'',
                orders:[],
                month:'',
                searchTerm:'',
                modalImg:''
            }
        },
        computed:{
            quantity(){
                let sum=0;
                for(let i=0; i < this.orders.length; i++ ){
                    sum += (parseFloat(this.orders[i].qty));
                }
                return sum;
            },
            subtotal(){
                let sum=0;
                for(let i=0; i < this.orders.length; i++ ){
                    sum += (parseFloat(this.orders[i].sub_total));
                }
                return sum;
            },
            total(){
                let sum=0;
                for(let i=0; i < this.orders.length; i++ ){
                    sum += (parseFloat(this.orders[i].total));
                }
                return sum;
            },
            pay(){
                let sum=0;
                for(let i=0; i < this.orders.length; i++ ){
                    sum += (parseFloat(this.orders[i].pay));
                }
                return sum;
            },
            due(){
                let sum=0;
                for(let i=0; i < this.orders.length; i++ ){
                    sum += (parseFloat(this.orders[i].due));
                }
                return sum;
            },
            filtersearch(){
                return this.orders.filter(order => {
                    //return employee.phone.match(this.searchTerm)
                    return order.invoiceNum.toLowerCase().match(this.searchTerm.toLowerCase())
                    // let searchLowerCase = employee.name.toLowerCase()
                    // return searchLowerCase.match(this.searchTerm.toLowerCase())
                })
            }
        },
        methods:{
            searchDate(){
                var data = {date: this.date}
                axios.post('/api/search/order',data)
                    .then(({data}) => (this.orders = data))
                    .catch()
            },
            searchMonth(){
                var data = {month: this.month}
                axios.post('/api/search/month',data)
                    .then(({data}) => (this.orders = data))
            }, 
            ImgToModal(base64) {
                this.modalImg = base64;
            }
        }
    }
</script>


<style>
    #add_new{
        float: right;
    }
</style>
