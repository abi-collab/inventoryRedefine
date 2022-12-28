<template>
    <div>
        <!-- Breadcrumbs-->
        <ol class="breadcrumb mt-3 noPrint">
            <li class="breadcrumb-item">
                <router-link to="/home">Dashboard</router-link>
            </li>
            <li class="breadcrumb-item active">Point Of Sale</li>
        </ol>

        <div class="row mb-4 noPrint">
            <!--------------------------Left_Side_"Expense Insert"------------2nd_task----------->
            <div class="card col-lg-6 shadow">
                <div class="card-header flex">
                    <!-- <i class="fas fa-chart-area"></i> -->
                    <b>Invoice #: {{ getRandomId }}</b>
                </div>

                <div class="card-body p-0 m-0">
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <th scope="col" style="text-align:left;">Item</th>
                                <th scope="col">Qty</th>
                                <!-- <th scope="col">Serials</th> -->
                                <th scope="col">Unit</th>
                                <th scope="col">Sub-Total</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody> <!------Expense_Insert_Table(Top_Left)--------->
                            <tr v-for="card in cards" :key="card.id"> <!-------pos_table---------3----->
                                <th style="text-align:left;">{{ card.pro_name }}</th>
                                <td style="display: flex;align-items: center;justify-content: space-between;">
                                    <button @click.prevent="decrement(card)" class="btn btn-sm btn-danger"
                                        v-if="card.pro_quantity >= 2">-</button>
                                    <button class="btn btn-sm btn-danger" v-else="" disabled>-</button>
                                    <!-------------->
                                    <input type="text" readonly="" style="width: 30px; text-align: center;"
                                        :value="card.pro_quantity">
                                    <button @click.prevent="increment(card)" class="btn btn-sm btn-success"
                                        :disabled="(card.pro_quantity > card.product_quantity)">+</button>
                                </td>
                                <!-- <td v-for="i in serials" :key="i"><p v-if="(i.pro_code == card.product_code)">{{i}}</p></td> -->
                                <td>
                                    <div style="display:flex; justify-content: space-between">&#8369;<p>{{
                                            (Number(card.product_price).toLocaleString() || 0)
                                    }}</p>
                                    </div>
                                </td>
                                <td>
                                    <div style="display:flex; justify-content: space-between">
                                        &#8369;&nbsp;<p>{{ (Number(card.sub_total).toLocaleString() || 0) }}</p>
                                    </div>
                                </td>
                                <td><a @click="removeItem(card)" class="btn btn-sm btn-outline-danger text-danger">x</a>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                    <hr>
                </div>
                <div class="card-footer"> <!-----Expense_Insert_Table(Middle_Left)------->
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Total Quantity:
                            <strong>{{ qty }}</strong>
                        </li>
                        <!-- <li class="list-group-item d-flex justify-content-between align-items-center">
                            Sub Total:
                            <strong> &#8369; {{ subtotal.toFixed(2) }} </strong>
                        </li> -->
                        <!-- <li class="list-group-item d-flex justify-content-between align-items-center">
                            Vat:
                            <strong> {{ vats.vat }} % </strong>
                        </li> -->
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Total Due:
                            <!-- <strong> {{ subtotal*vats.vat /100 +subtotal }} Tk</strong> -->
                            <strong> &#8369; {{ (Number(subtotal).toLocaleString() || 0) }} </strong>

                        </li>
                    </ul>
                    <br> <!-----Expense_Insert_Table(Bottom_Left)------>
                    <form @submit.prevent="orderdone"> <!--------------2----------------->
                        <div class="row">
                            <div class="col-lg-6">
                                <label>Customer Name</label>
                                <div class="input-group ">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-secondary text-white" data-toggle="modal" data-target="#exampleModal" id="add_new" type="button"> + </button>
                                    </div>
                                    <select class="form-control" v-model="customer_id" required>
                                        <option :value="customer.id" v-for="customer in customers">{{ customer.name }}</option>
                                    </select>
                                </div>

                               
                               
                                <!-- <label>Pay By</label>
                        <select class="form-control" v-model="payby">
                            <option value="HandCash">Hand Cash</option>
                            <option value="Cheaque">Cheaque</option>
                            <option value="GiftCard">Gift Card</option>
                        </select> -->

                            </div>
                            <div class="col-lg-6">
                                <!-- <label>Due</label>                              -->
                                <!-- <input type="text" class="form-control" required v-model="due"> -->
                                <!-- <input type="text" class="form-control mb-2" required :value="subtotal.toFixed(2)"> -->
                                <!-- :value="((subtotal*vats.vat /100 +subtotal) - pay).toFixed(2)" -->
                                <label>Cash Recieved</label>
                                <input type="text" class="form-control mb-2" required v-model="pay">

                                <div v-if="(pay > subtotal)">
                                    <label>Change</label>
                                    <input type="text" class="form-control mb-2" required :value="sukli(pay,subtotal)"
                                        disabled>
                                </div>


                            </div>

                        </div>

                        <br>
                        <button type="submit" class="btn btn-success mb-4" v-if="cards.length > 0 && pay >= subtotal ">Place Order</button>
                        <!-- <a class="btn btn-sm btn-primary text-white" data-toggle="modal" data-target="#serialnums"
                            id="add_new"> Submit2</a> -->
                    </form>
                </div>
            </div>

            <!------------------ serial number modal------------------2-------->
            <div class="modal fade" id="serialnums" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Provide the serial numbers for the ordered
                                items</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeModal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <serials :serials="serialNumbersForItemQnty" :computedSerials="cardsx" @my-event="orderSave" />
                        </div>
                    </div>
                </div>
            </div>
            <!------------end serial number modal------------------------->
            <!------------------customer add modal------------------2-------->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">New Customer</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeModal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form @submit.prevent="customerInsert" enctype="multipart/form-data">
                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-label-group">
                                                <input type="text" v-model="form.name" class="form-control" required>
                                                <small class="text-danger" v-if="errors.name">{{ errors.name[0]
                                                }}</small>
                                                <label for="firstName">Full Name</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-label-group">
                                                <input type="email" v-model="form.email" class="form-control">
                                                <small class="text-danger" v-if="errors.email">{{ errors.email[0]
                                                }}</small>
                                                <label for="lastName">Email Address</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-label-group">
                                                <input type="text" v-model="form.address" class="form-control" required>
                                                <small class="text-danger" v-if="errors.address">{{ errors.address[0]
                                                }}</small>
                                                <label for="firstName">Address</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-label-group">
                                                <input type="text" v-model="form.phone" class="form-control" required>
                                                <label for="lastName">Phone</label>
                                                <small class="text-danger" v-if="errors.phone">{{ errors.phone[0]
                                                }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-label-group">
                                                <input type="file" class="btn" @change="onFileselected">
                                                <small class="text-danger" v-if="errors.photo">{{ errors.photo[0]
                                                }}</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <img :src="form.photo" style="height:40px; width: 40px;">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!------------end customer modal------------------------->


            <!--------------Right_Side_"Product"----------1st_task----------1----->
            <div class="card col-lg-6 border-light">
                <div class="card-header text-dark mb-4">
                    <i class="fas fa-chart-area"></i>
                    <b>Products</b>
                </div>

                <div class="card-body p-0 m-0">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home"
                                role="tab" aria-controls="pills-home" aria-selected="true">All Product</a>
                        </li>

                        <li class="nav-item" v-for="category in categories" :key="category.id">
                            <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile"
                                role="tab" aria-controls="pills-profile" aria-selected="false"
                                @click="subproduct(category.id)">{{ category.category_name }}</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                            aria-labelledby="pills-home-tab">
                            <!-----Search----->
                            <input type="text" v-model="searchTerm" class="form-control"
                                placeholder="Search here..."><br>
                            <div class="row">
                                <!-- class="col-lg-3 col-md-4 col-sm-6 col-6" -->
                                <div v-for="product in filtersearch" :key="product.id">
                                    <button v-if="product.product_quantity >= 1" class="btn btn-sm productCard"
                                        @click.prevent="AddToCart(product)"> <!--------3------->
                                        <div class="card" style="width: 9rem; height: 210px;">
                                            <img :src="product.image" class="card-img-top mx-auto w-full"
                                                style="height: 100px;">
                                            <div class="card-body p-0 m-0">
                                                <small class="card-title">{{ product.product_name }}</small><br>
                                                <span class="text-dark d-block m-0 p-0 text-lg">&#8369;&nbsp; {{
                                                        (Number(product.selling_price).toLocaleString() || 0)
                                                }}</span>
                                                <div style="display: flex;flex-direction: column;">
                                                    <span class="badge badge-success"
                                                        v-if="product.product_quantity >= 1"> Available ({{
                                                                product.product_quantity
                                                        }}) </span>
                                                    <span class="badge badge-danger" v-else>Stock Out</span>
                                                </div>

                                            </div>
                                        </div>
                                    </button>
                                    <button v-else disabled class="btn btn-sm productCard"
                                        @click.prevent="AddToCart(product)"> <!--------3------->
                                        <div class="card" style="width: 9rem; height: 210px;">
                                            <img :src="product.image" class="card-img-top"
                                                style="height: 100px; width: 100px;">
                                            <div class="card-body p-0 m-0">
                                                <small class="card-title">{{ product.product_name }}</small><br>

                                                <span class="text-dark d-block m-0 p-0 text-lg">&#8369;&nbsp;{{
                                                        (Number(product.selling_price).toLocaleString() || 0)
                                                }}</span>
                                                <div style="display: flex;flex-direction: column;">
                                                    <span class="badge badge-success" style="margin-top: auto;"
                                                        v-if="product.product_quantity >= 1"> Available ({{
                                                                product.product_quantity
                                                        }}) </span>
                                                    <span class="badge badge-danger" style="margin-top: auto;"
                                                        v-else>Stock Out</span>
                                                </div>
                                            </div>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!----------------------------------------------------- search ------------------------------------------------------>
                        <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                            aria-labelledby="pills-profile-tab">
                            <input type="text" v-model="getsearchTerm" class="form-control"
                                placeholder="Search here..."><br>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 col-sm-6 col-6" v-for="getproduct in getfiltersearch"
                                    :key="getproduct.id">
                                    <button v-if="getproduct.product_quantity >= 1" class="btn btn-sm"
                                        @click.prevent="AddToCart(getproduct.id)">
                                        <div class="card" style="width: 9rem; height: 180px;">
                                            <img :src="getproduct.image" class="card-img-top"
                                                style="height: 100px; width: 100px;">
                                            <div class="card-body p-0 m-0">
                                                <small class="card-title">{{ getproduct.product_name }}</small> <br>
                                                <span class="badge badge-success"
                                                    v-if="getproduct.product_quantity >= 1"> Availble ({{
                                                            getproduct.product_quantity
                                                    }}) </span>
                                                <span class="badge badge-danger" v-else>Stock Out</span>
                                                <span class="text-dark d-block m-0 p-0 small">
                                                    &#8369;&nbsp;{{ getproduct.selling_price }}</span>
                                            </div>
                                        </div>
                                    </button>
                                    <button v-else disabled class="btn btn-sm"
                                        @click.prevent="AddToCart(getproduct.id)">
                                        <div class="card" style="width: 9rem; height: 180px;">
                                            <img :src="getproduct.image" class="card-img-top"
                                                style="height: 100px; width: 100px;">
                                            <div class="card-body p-0 m-0">
                                                <small class="card-title">{{ getproduct.product_name }}</small> <br>
                                                <span class="badge badge-success"
                                                    v-if="getproduct.product_quantity >= 1"> Availble ({{
                                                            getproduct.product_quantity
                                                    }}) </span>
                                                <span class="badge badge-danger" v-else>Stock Out</span>
                                                <span
                                                    class="text-dark d-block m-0 p-0 small">&#8369;&nbsp;{{ getproduct.selling_price }}</span>
                                            </div>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Icon Cards-->

        <!-- <div id="printMe" class="container" style=" padding: 100px;">
            <h4>Invoice #:  {{getRandomId}}</h4>
            <div class="row">
                <div class="col" style="display:flex; justify-content: space-between">
                    <div>
                        <h5>KUYA ALLAN COMPUTER CENTER</h5>
                        <p>PARADAHAN II, Tanza , 4100 Cavite</p>
                        <p>09158974437 / 09338219106</p>
                    </div>
                    <img src="/images/kuyaAllanLogo.png" alt="logo" style="height: 215px;">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <h5>Bill To</h5>
                    <select class="form-control mb-2" v-model="customer_id" style="appearance: none;" disabled>
                        <option :value="customer.id" v-for="customer in customers">{{ customer.name }}</option>
                    </select>
                </div>
                <div class="col"></div>
                
            </div>
            <div class="row">
                <table class="table table-sm table-striped">
                        <thead>
                        <tr>
                            <th scope="col" style="text-align:left;">Item</th>
                            <th scope="col">Qty</th>
                            <th scope="col">Unit</th>
                            <th scope="col">Sub-Total</th>
             
                        </tr>
                        </thead>
                        <tbody> 
                        <tr v-for="card in cards" :key="card.id">     
                            <th style="text-align:left;">{{ card.pro_name }}</th>
                            <td>{{card.pro_quantity}}</td>
                            <td>
                                <div style="display:flex; justify-content: flex-end"><p>{{ (Number(card.product_price).toLocaleString() || 0) }}</p></div></td>
                            <td>
                                <div style="display:flex; justify-content: flex-end">
                                    <p>{{ (Number(card.sub_total).toLocaleString() || 0) }}</p>
                                </div>
                            </td>
                        </tr>
                        <tr>    
                            <th></th>
                            <td><b>{{qty}}</b></td>
                            <td></td>
                            <td style="display:flex; justify-content: flex-end"> <b>&#8369;&nbsp;</b><b>{{ (Number(subtotal).toLocaleString() || 0) }}</b></td>
                        </tr>
                        <br><br>
                        <tr>    
                            <th style="display: flex; justify-content:flex-end">Cash</th>
                            <td></td>
                            <td></td>
                            <td style="display:flex; justify-content: flex-end"><b>&#8369;&nbsp;</b><b>{{ (Number(pay).toLocaleString() || 0) }}</b></td>
                        </tr>
                        <tr>    
                            <th style="display: flex; justify-content:flex-end">Change</th>
                            <td></td>
                            <td></td>
                            <td style="display:flex; justify-content: flex-end"> 
                           
                                <span v-if="(pay > subtotal)">
                                     <b>&#8369;&nbsp;</b><b>{{ (Number(pay - subtotal).toLocaleString() || 0) }}</b>
                                </span>
                               
                            </td>
                        </tr>
                        </tbody>
                      
                    </table>

                    <button @click="printNa()">print na</button>
                    <button @click="print()">print</button>
                    
                  
            </div>
        </div> -->
        <!-- <h1>ids</h1>
{{productIds}} -->
    </div>
</template>


<script>
import Cookies from 'js-cookie';
import $ from 'jquery'; 
import serials from './serials.vue'
import html2canvas from 'html2canvas';

export default {
    components: {
        serials
    },
    mounted() {
        if (!User.loggedIn()) {
            this.$router.push({ name: '/' })
        }
    },
    created() {
        this.invoiceNumber();
        this.allProduct();    //---------1--
        this.allCategory();   //---------1--
        this.allCustomer();     //--------2---
        this.cartProduct();        //--------3--
        this.vat();
        Reload.$on('AfterAdd', () => {    //--------3--
            this.cartProduct();
        });
        Reload.$on('customerReload', () => {   //--added by me
            this.allCustomer();
        })
    },
    data() {
        return {
            form2: { serialnum: null },
            form: {
                // details :'',
                // amount:'',
                name: '',
                email: '',
                address: '',
                photo: '',
                phone: '',
            },
            form3:{ //2
					activity :'',
					createdby : Cookies.get('usersname')
				},
            customer_id: '',
            pay: '',
            due: '',
            payby: 'Hand Cash',
            products: [],
            categories: '',
            getproducts: [],
            searchTerm: '',
            getsearchTerm: '',
            customers: '',
            errors: '',
            cards: [],
            vats: '',
            invoiceNum: 0,
            passVal: 0,
            returnx: 0,
            ssImg: '',
            productIds: [],
            serialNumbersForItemQnty: [],
            invoiceRandomNumber:'',
            change:0
        }
    },
    computed: {

        cardsx() {
            let arr = this.cards.map(x => x);
            for (let i = 0; i < arr.length; i++) {
                arr[i].serials = [];
                let num = parseInt(arr[i].pro_quantity);
                for (let j = 0; j < num; j++) {
                    arr[i].serials.push({ serialnum: '' });
                }
            }

            // console.log('arr here!!!',arr)

            // this.serialNumbersForItemQnty = arr;

            let arr2 = [];
            for (let i = 0; i < arr.length; i++) {
                arr2.push({
                    created_at: arr[i].created_at,
                    id: arr[i].id,
                    pro_id: arr[i].pro_id,
                    pro_name: arr[i].pro_name,
                    pro_quantity: arr[i].pro_quantity,
                    product_price: arr[i].product_price,
                    serials: arr[i].serials,
                    updated_at: arr[i].updated_at,

                })

            }
            this.serialNumbersForItemQnty = arr2;
            return arr
        },
        selectedCustomer() {
            let a;
            let pangalan = [];

            if (this.customer_id) {
                a = this.customers?.filter((v) => v.id == this.customer_id);
                pangalan.push(a)
            } else {
                a = null;
            }


            return pangalan[0];
        },
        filtersearch() {                          //----------------1-------
            return this.products.filter(product => {
                //return product.product_name.match(this.searchTerm)
                return product.product_name.toLowerCase().match(this.searchTerm.toLowerCase())
            })
        },
        getfiltersearch() {                       //-----------------1-------
            return this.getproducts.filter(getproduct => {
                //return getproduct.product_name.match(this.getsearchTerm)
                return getproduct.product_name.toLowerCase().match(this.getsearchTerm.toLowerCase())
            })
        },
        qty() {
            let sum = 0;
            for (let i = 0; i < this.cards.length; i++) {
                sum += (parseFloat(this.cards[i].pro_quantity));    //The parseFloat() function parses a string and returns a floating point number. That means a number of string convert into floating number.
            }
            return sum;
        },
        subtotal() {
            let sum = 0;
            for (let i = 0; i < this.cards.length; i++) {
                sum += (parseFloat(this.cards[i].pro_quantity) * parseFloat(this.cards[i].product_price));
            }
            return sum;
        },

        getRandomId() {
            const dateNow = new Date();
            let day = dateNow.getDay();
            let month = dateNow.getMonth();
            let year = dateNow.getFullYear();
            let hour = dateNow.getHours();
            let minutes = dateNow.getMinutes();
            let seconds = dateNow.getSeconds();
            let milli = dateNow.getMilliseconds();
            let all = day + '' + month + '' + year + '' + hour + '' + minutes + '' + seconds + '' + milli;
            this.invoiceRandomNumber = all;
            return all;
        }
    },
    methods: {
        sukli(pay,subtotal) {
            let a = pay - subtotal;
            this.change = a;
            return a;
        },
        async print() {
            await this.$htmlToPaper("printMe");
        },
        printNa() {
            html2canvas(document.querySelector("#printMe")).then((canvas) => {
                this.ssImg = canvas.toDataURL("image/png", 0.9);
                if (this.ssImg) {
                    print();
                }
            })
        },
        returnQty(val) {
            this.passVal = val;
            return val;
        },
        //--start cart methods--   //------------------3----
        AddToCart(card) {
            console.log('add card', card)
            if (this.productIds.includes(card.id)) {
                console.log('meron na!!!!!!!!!!!!!!!!');

                for (let j = 0; j < this.serialNumbersForItemQnty.length; j++) {
                    if (this.serialNumbersForItemQnty[j].pro_id == card.id) {
                        if (this.serialNumbersForItemQnty[j].serials.length >= Number(card.product_quantity)) {
                            Swal.fire({
                                title: 'Oops!',
                                text: "quantity is greater than the available",
                                icon: "warning",
                            });
                        } else {
                            this.serialNumbersForItemQnty[j].serials.push({ serialnum: '' })
                        }

                    } else {
                        console.log('something wrong!!!!!!!!!!!!!')

                    }
                }

            } else {
                this.productIds.push(card.id);
                card.serials = [
                    { serialnum: '1111111' }
                ]

                this.serialNumbersForItemQnty.push(
                    {
                        pro_id: card.id,
                        pro_code: card.product_code,
                        pro_name: card.product_name,
                        pro_quantity: card.product_quantity,
                        product_price: "31000",
                        serials: [
                            {
                                serialnum: ''
                            }
                        ]

                    }
                );
            }

            this.returnx = this.cards.filter((x) => x.pro_id == card.id);

            if (card.product_quantity <= this.returnx[0]?.pro_quantity) {
                Swal.fire({
                    title: 'Oops!',
                    text: "quantity is greater than the available",
                    icon: "warning",
                })
            } else {
                axios.get('/api/addTocart/' + card.id)
                    .then(() => {
                        Reload.$emit('AfterAdd');
                        Notification.cart_success()
                    })
            }
        },
        cartProduct() {                          //selected product show in card
            axios.get('/api/cart/product')
                .then(({ data }) => (this.cards = data))
                .catch()
        },
        removeItem(card) {
            for (let j = 0; j < this.serialNumbersForItemQnty.length; j++) {
                if (this.serialNumbersForItemQnty[j].pro_id == card.pro_id) {
                    this.serialNumbersForItemQnty.splice(j, 1);
                    this.productIds.splice(j, 1);
                }
            }

            axios.get('/api/remove/cart/' + card.id)
                .then(() => {
                    Reload.$emit('AfterAdd');
                    Notification.success()
                })
        },
        increment(card) {

            for (let j = 0; j < this.serialNumbersForItemQnty.length; j++) {
                if (this.serialNumbersForItemQnty[j].pro_id == card.pro_id) {
                    this.returnx = this.products.filter((x) => x.id == card.pro_id);
                    if (card.pro_quantity >= this.returnx[0].product_quantity) {
                        Swal.fire({
                            title: 'Oops!',
                            text: "quantity is greater than the available",
                            icon: "warning",
                        })
                    } else {
                        this.serialNumbersForItemQnty[j].serials.push({ serialnum: '' })
                        axios.get('/api/increment/' + card.id)
                            .then(() => {
                                Reload.$emit('AfterAdd');
                                Notification.success()
                            })
                    }
                }
            }
        },
        decrement(card) {
            for (let j = 0; j < this.serialNumbersForItemQnty.length; j++) {
                if (this.serialNumbersForItemQnty[j].pro_id == card.pro_id) {
                    this.serialNumbersForItemQnty[j].serials.pop();
                }
            }

            axios.get('/api/decrement/' + card.id)
                .then(() => {
                    Reload.$emit('AfterAdd');
                    Notification.success()
                })
        },
        vat() {
            axios.get('/api/vats')
                .then(({ data }) => (this.vats = data))
                .catch()
        },
        orderdone() {
            $('#serialnums').modal('toggle');
        },

        orderSave(serialsRecieved) {
            let total = this.subtotal * this.vats.vat / 100 + this.subtotal;
            let due = (total - this.pay).toFixed(2)         //variable.toFixed(2)=take 2 specified decimal number
            var data = { 
                        invoiceNum: this.getRandomId,
                        qty: this.qty, 
                        subtotal: this.subtotal, 
                        customer_id: this.customer_id, 
                        payby: this.payby, 
                        pay: this.pay, 
                        due: due, 
                        vat: this.vats.vat, 
                        total: total,
                        change: this.change,
                        // cashTendered: this.
                        }       //due:this.due //due_dynamic

            axios.post('/api/orderdone/', data)
                .then((res) => {
                    Notification.success()
                    // this.$router.push({ name: 'home' })
                    console.log('res', res);
                })

            let customer = this.customers.filter((h) => h.id == this.customer_id);
            console.log(customer[0].name);

            console.log('serialsRecieved', serialsRecieved);
            let serialList = [];
            for (let j = 0; j < serialsRecieved.length; j++) {
                for (let x = 0; x < serialsRecieved[j].serials.length; x++) {
                    serialList.push({
                        invoiceNumber: this.getRandomId,
                        customerId: this.customer_id,
                        customerName: customer[0].name,
                        serialNo:serialsRecieved[j].serials[x].serialnum,
                        id:serialsRecieved[j].id,
                        product_id:serialsRecieved[j].pro_id,
                        product_name:serialsRecieved[j].pro_name,
                        order_quantity:serialsRecieved[j].pro_quantity,
                        product_price:serialsRecieved[j].product_price,
                        created_by:Cookies.get('userNow'),
                    })
                }
            }
            console.log(this.getRandomId);
            console.log('serialList', serialList);

            for (let l = 0; l < serialList.length; l++) {
                    axios.post('/api/serials', serialList[l])
                    .then((res) => {
                        // Notification.success()
                        // this.$router.push({ name: 'home' })
                        console.log('res', res);
                    })
                }

                this.form3.activity = `Successful purchase transaction, invoice number ${this.getRandomId}`;//4
                axios.post('/api/activitylog',this.form3)  //5
                .then((r) => {
                    console.log('logssss',r)
                })
                .catch(error => this.errors = error.response.data.errors)

        },
        //---End_cart_methods----

        allProduct() {                                         //------------1---
            axios.get('/api/product')
                .then(({ data }) => (this.products = data))
                .catch()
        },
        allCategory() {                                       //-------------1---
            axios.get('/api/category/')
                .then(({ data }) => (this.categories = data))
                .catch()
        },
        allCustomer() {                              //------2----
            axios.get('/api/Customer/')
                .then(({ data }) => (this.customers = data))
                .catch()
        },
        subproduct(id) {                                      //-------------1---
            axios.get('/api/getting/product/' + id)
                .then(({ data }) => (this.getproducts = data))
                .catch(error => this.errors = error.response.data.errors)
        },
        //---image validationmethod---
        onFileselected(event) {                  //------------2----
            let file = event.target.files[0];
            if (file.size > 1048770) {
                Notification.image_validation()
            } else {
                let reader = new FileReader();
                reader.onload = event => {
                    this.form.photo = event.target.result

                    console.log(event.target.result);
                };
                reader.readAsDataURL(file);
            }
        },
        customerInsert() {        
                       //-------------2---
            axios.post('/api/Customer/', this.form)
                .then(() => {
                    $('#closeModal').click();
                    Reload.$emit('customerReload'); //first global declar in app.js, then goto created() on above
                    Notification.success()
                    // this.customers = this.customers.filter(customer =>{   //--Or-/use Reload--
                    //     return customer.id !=id
                    // })
                })
        },

        invoiceNumber() {
            let getRandomId = (min = 0, max = 500000) => {
                min = Math.ceil(min);
                max = Math.floor(max);
                const num = Math.floor(Math.random() * (max - min + 1)) + min;
                return num.toString().padStart(6, "0")
            };

            this.invoiceNum = getRandomId;

        }
    }
}
</script>


<style scoped>
#add_new {
    float: right;
}

#em_photo {
    height: 100px;
    width: 90px;
}

th,
td {
    text-align: center;
}

.nav-link {
    color: black;
}

.nav-item:active {
    background-color: #eee;
}

.productCard:hover {
    border: solid green 1px;
}

.productCard:active {
    background-color: red;
}

@media print {
    .noPrint {
        display: none;
    }

    #printMe {
        display: block;
    }
}
</style>
