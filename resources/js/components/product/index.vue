<template>
    <div>
        <!-- Breadcrumbs-->
        <ol class="breadcrumb mt-3">
            <li class="breadcrumb-item">
                <router-link to="/home">Dashboard</router-link>
            </li>
            <li class="breadcrumb-item active">All Product</li>
        </ol>
        <!-- Icon Cards-->
        <div class="row card shadow mb-3">
            <div class="card-header" style="font-size: 20px; font-weight:700;">
                All Product
                <router-link to="/store-product" class="btn btn-dark" id="add_new"> Add New</router-link>
            </div>
            <div class="card-body p-0 m-0">
                <div class="card-body p-0 m-0">
                        <div style="display: flex; justify-content: center; margin: 10px 0px;">
                            <input type="text" v-model="searchTerm" class="form-control d-inline" style="width:50%;" placeholder="Search...">
                        </div>
                      
                        <table class="table table-bordered table-striped table-hover table-warning border-light" id="" width="100%" cellspacing="0">

                            <thead>
                            <tr class="bg-info text-white">
                                <th>Name</th>
                                <!-- <th>Code</th> -->
                                <th>Photo</th>
                                <th>Category</th>
                                <th>Manufacturer/Brand</th>
                                <th>Processor</th>
                                <th>Connectivity</th>
                                <th>RAM</th>
                                <th>Hard Drive</th>
                                <th>OS</th>
                                <th>Buying Price</th>
                                <th>Selling Price</th>
                                <!-- <th>Root</th> -->
                                <th>Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr v-for="product in filtersearch" :key="product.id">
                                <td>{{ product.product_name }}</td>
                                <!-- <td>{{ product.product_code}}</td> -->
                                <td><img :src="product.image" id="em_photo"></td>
                                <td>{{ product.category_name }}</td>
                                <td>{{ product.manufacturer }}</td>
                                <td>{{ product.Processor }}</td>
                                <td>{{ product.connectivity }}</td>
                                <td>{{ product.ram }}</td>
                                <td>{{ product.hard_drive}} {{ product.hard_drive_size }} {{ product.hard_drive_byte }}</td>
                                <td>{{ product.os }} {{ product.os_version }}</td>
                                <td>&#8369; &nbsp; &nbsp; {{ (Number(product.buying_price).toLocaleString() || 0)}}</td>
                                <td>&#8369; &nbsp; &nbsp; {{ (Number(product.selling_price).toLocaleString() || 0)}}</td>
                                <!-- <td style="display:flex; justify-content: space-between;"><p>&#8369;</p>  {{ (Number(product.buying_price).toLocaleString() || 0)}}</td>
                                <td style="display:flex; justify-content: space-between;"><p>&#8369;</p>  {{ (Number(product.selling_price).toLocaleString() || 0)}}</td> -->
                                <!-- <td>{{ product.root }}</td> -->
                                <td>
                                    <router-link :to="{name: 'edit-product', params:{id: product.id} }" class="btn btn-sm btn-info">Edit</router-link>
                                    <!-- <router-link :to="'/edit-category/'+category.id" class="btn btn-warning mr-1">Edit</router-link> -->   <!--or-->
                                    <!-- <router-link :to="`/edit-category/${category.id}`" class="btn btn-sm btn-primary text-white">Edit</router-link> -->
                                    <a @click="deleteProduct(product.id)" class="btn btn-sm btn-danger text-white">Delete</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                   
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
        created(){
            this.allProduct();
        },
        data(){
            return{
                products:[],
                searchTerm:'',
            }
        },
        computed:{
            filtersearch(){
                return this.products.filter(product => {
                    //return product.product_name.match(this.searchTerm)
                    return product.product_name.toLowerCase().match(this.searchTerm.toLowerCase()) || 
                    product.category_name.toLowerCase().match(this.searchTerm.toLowerCase()) ||
                    product.manufacturer.toLowerCase().match(this.searchTerm.toLowerCase()) ||
                    product.Processor.toLowerCase().match(this.searchTerm.toLowerCase()) ||
                    // product.ram.toLowerCase().match(this.searchTerm.toLowerCase()) ||
                    product.hard_drive.toLowerCase().match(this.searchTerm.toLowerCase()) ||
                    // product.hard_drive_size.toLowerCase().match(this.searchTerm.toLowerCase()) ||
                    product.hard_drive_byte.toLowerCase().match(this.searchTerm.toLowerCase()) ||
                    product.os.toLowerCase().match(this.searchTerm.toLowerCase()) ||
                    product.os_version.toLowerCase().match(this.searchTerm.toLowerCase()) 
                    // product.buying_price.toLowerCase().match(this.searchTerm.toLowerCase()) ||
                    // product.selling_price.toLowerCase().match(this.searchTerm.toLowerCase())

                })
            }
        },
        methods:{
            allProduct(){
                axios.get('/api/product/')
                    .then(({data}) => (this.products = data))
                    .catch()
            },
            deleteProduct(id){
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        axios.delete('/api/product/'+id)
                            .then(()=>{
                                this.products = this.products.filter(product =>{
                                    return product.id !=id
                                })
                            })
                            .catch(()=>{
                                this.$router.push({name: 'product'})
                            })
                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                    }
                })
            }
        },
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
    th,td {
        font-size: small;
    }
</style>
