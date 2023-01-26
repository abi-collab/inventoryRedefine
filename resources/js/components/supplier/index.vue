<template>
    <div>
        <!-- Breadcrumbs-->
        <ol class="breadcrumb mt-3">
            <li class="breadcrumb-item">
                <router-link to="/home">Dashboard</router-link>
            </li>
            <li class="breadcrumb-item active">All Supplier</li>
        </ol>
        <!-- Icon Cards-->
        <div class="row card container-fluid shadow">
            <div class="card-header" style="font-size: 20px; font-weight:700;">
                All Supplier
                <router-link to="/store-supplier" class="btn btn-dark" id="add_new"> Add New</router-link>
            </div>
            <div class="card-body p-0 m-0">
                <div class="card-body p-0 m-0">
                    <div class="table-responsive">
                        <label class="d-inline">Search : </label>      <!-----f----->
                        <input type="text" v-model="searchTerm" class="form-control d-inline" style="width:200px;" placeholder="Search by name"><br> <br>
                        <table class="table table-bordered table-striped table-hover table-warning border-light" id="" width="100%" cellspacing="0">

                            <thead>
                            <tr class="bg-info text-white">
                                <th>Name</th>
                                <th>Photo</th>
                                <th>Phone</th>
                                <th>Shop Name</th>
                                <th>Address</th>
                                <th>Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr v-for="supplier in filtersearch" :key="supplier.id">
                                <td>{{ supplier.name }}</td>
                                <td><img :src="supplier.photo" id="em_photo"></td>
                                <td>{{ supplier.phone }}</td>
                                <td>{{ supplier.shopname }}</td>
                                <td>{{ supplier.address }}</td>
                                <td>
                                    <router-link :to="{name: 'edit-supplier', params:{id: supplier.id} }" class="btn btn-sm btn-info">Edit</router-link>
                                    <!-- <router-link :to="'/edit-category/'+category.id" class="btn btn-warning mr-1">Edit</router-link> --> <!--or, -->
                                    <!-- <router-link :to="`/edit-category/${category.id}`" class="btn btn-sm btn-primary text-white">Edit</router-link> -->
                                    <a @click="deleteSupplier(supplier.id)" class="btn btn-sm btn-danger text-white">Delete</a>
                                </td>
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
        created(){
            this.allSupplier();
        },
        data(){
            return{
                suppliers:[],
                searchTerm:'',
            }
        },
        computed:{
            filtersearch(){
                return this.suppliers.filter(supplier => {
                    //return supplier.phone.match(this.searchTerm)
                    return supplier.name.toLowerCase().match(this.searchTerm.toLowerCase())
                    // let searchLowerCase = supplier.name.toLowerCase()
                    // return searchLowerCase.match(this.searchTerm.toLowerCase())
                })
            }
        },
        methods:{
            allSupplier(){
                axios.get('/api/supplier/')
                    .then(({data}) => (this.suppliers = data))
                    .catch()
            },
            deleteSupplier(id){
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
                        axios.delete('/api/supplier/'+id)
                            .then(()=>{
                                this.suppliers = this.suppliers.filter(supplier =>{
                                    return supplier.id !=id
                                })
                            })
                            .catch(()=>{
                                this.$router.push({name: 'supplier'})
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
</style>
