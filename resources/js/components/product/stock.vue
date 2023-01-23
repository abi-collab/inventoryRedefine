<template>
    <div>
        <!-- Breadcrumbs-->
        <ol class="breadcrumb mt-3">
            <li class="breadcrumb-item">
                <router-link to="/home">Dashboard</router-link>
            </li>
            <li class="breadcrumb-item active">Stock</li>
        </ol>
        <!-- Icon Cards-->
        <div class="row card container-fluid shadow mb-3">
            <div class="card-header text-dark mb-4" style="font-size: 20px; font-weight:700;">
                <i class="fas fa-chart-area"></i>
                Stock
            </div>
            <div class="card-body p-0 m-0">
                <div class="card-body p-0 m-0">
                    <div class="table-responsive">
                        <label class="d-inline">Search : </label>
                        <input type="text" v-model="searchTerm" class="form-control d-inline" style="width:200px" placeholder="Search by name"><br> <br>
                        <table class="table table-bordered table-striped table-hover table-warning" width="100%" cellspacing="0">
                            <thead>
                            <tr class="bg-info text-white">
                                <th>Name</th>
                                <!-- <th>Code</th> -->
                                <th>Photo</th>
                                <th>Category</th>
                                <th>Serial No's</th>
                                <th>Status</th>
                                <th>Available</th>
                                <th>Sold</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="product in filtersearch" :key="product.id">
                                <td>{{ product.product_name}}</td>
                                <!-- <td>{{ product.product_code}}</td> -->
                                <td><img :src="product.image" id="em_photo"></td>
                                <td>{{ product.category_name }}</td>
                                <th><router-link :to="{path:`/stock/${product.product_name}/${product.id}`, name:'productSerials', params:{ id: product.id, productName:product.product_name}}"><h4 style="text-align: center;">{{ serialsBelong(product) }}</h4></router-link></th>
                                <td v-if="product.product_quantity >= 1"><span class="badge badge-success">Available</span></td>
                                <td v-else=""><span class="badge badge-danger">Stock Out</span></td>
                                <td>{{ product.product_quantity }}</td>
                                <td>Available</td>
                                <td>
                                    <router-link :to="{name: 'edit-stock', params:{id: product.id} }" class="btn btn-sm btn-info">Add Stock</router-link>
                                    <!-- <router-link :to="'/edit-category/'+category.id" class="btn btn-warning mr-1">Edit</router-link> --> <!--or, -->
                                    <!-- <router-link :to="`/edit-category/${category.id}`" class="btn btn-sm btn-primary text-white">Edit</router-link> -->
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
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
                products:[],
                searchTerm:'',
                serialList:[]
            }
        },
        created(){
            this.allProduct();
            axios.get('/api/productSerials').then((res) => {
                this.serialList = res.data;
            })

        },
        computed:{
            filtersearch(){
                return this.products.filter(product => {
                    // return product.product_name.match(this.searchTerm)
                    return product.product_name.toLowerCase().match(this.searchTerm.toLowerCase())
                })
            }
        },
        methods:{
            allProduct(){
                axios.get('/api/product/')
                    .then(({data}) => (this.products = data))
                    .catch()
            },
            serialsBelong(product) {
                // this.serialList.filter((x) => )
                // console.log('product', product);
                // console.log('serialList',this.serialList);
                let allTrue = [];
               this.serialList.map((z) => {
                    if(z.product_id == product.id) {
                        allTrue.push(z)
                    }
                });

                // const allTrue = (filteredSers.filter(Boolean).length == arr.length);
                return allTrue.length;
            }
        }
    }
</script>


<style>
    #add_new{
        float: right;
    }
    #em_photo{
        height: 40px;
        width: 40px;
    }
</style>
