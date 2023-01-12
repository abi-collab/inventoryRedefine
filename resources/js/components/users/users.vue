<template>
  <div class="border-light">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb mt-3">    
          <li class="breadcrumb-item">
              <router-link to="/home">Dashboard</router-link>
          </li>
          <li class="breadcrumb-item active">All Users</li>
      </ol>
      <!-- Icon Cards-->
      <div class="row card container-fluid shadow mb-3">  
          <div class="card-header" style="font-size: 20px; font-weight:700;">
              <i class="fas fa-chart-area"></i>
              All Users
              <router-link to="/store-user" class="btn btn-dark" id="add_new"> Users</router-link>  
          </div>
          <div class="card-body p-0 m-0">   
              <div class="card-body p-0 m-0">
                  <div class="table-responsive">
                      <label class="d-inline">Search : </label>  
                      <input type="text" v-model="searchTerm" class="form-control d-inline" style="width:200px" placeholder="Search by name"><br> <br>    
                      <table class="table table-bordered table-striped table-hover table-warning border-light" id="" width="100%" cellspacing="0">  

                          <thead>
                          <tr class="bg-info text-white">
                              <th>Name</th>
                              <th>Username</th>
                              <th>Role</th>
                              <th>Action</th>
                          </tr>
                          </thead>

                          <tbody>
                          <tr v-for="user in filtersearch" :key="user.id">    
                              <td>{{ user.name }}</td>  
                              <td>{{ user.username }}</td>
                              <td style="text-align: center;">{{ user.user_role }}</td>
                              <td>
                                  <router-link :to="{name: 'editUsers', params:{user: user} }" class="btn btn-sm btn-info">Edit</router-link>    
                                  <a @click="deleteuser(user.id)" class="btn btn-sm btn-danger text-white">Delete</a>
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

      created(){          //--immediately showing data_table after opening file
          this.alluser();
      },

      data(){
          return{
              users:[],
              searchTerm:'',
          }
      },
      computed:{                      //----search + show-------
          filtersearch(){
              return this.users.filter(user => {

                  return user.name.toLowerCase().match(this.searchTerm.toLowerCase())
              })
          }
      },
      methods:{
          alluser(){
            axios.get('/api/users')
            .then(({data}) => (this.users = data, console.log('users', data)))
            .catch()
          },
          deleteuser(id){
              Swal.fire({
                  title: 'Are you sure?',
                  text: "You won't be able to revert this!",
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, Delete it!'
              }).then((result) => {
                  if (result.value) {
                      axios.delete('/api/user/'+id)               //------------delete-------------
                          .then(()=>{
                              this.users = this.users.filter(user =>{
                                  return user.id !=id
                              })
                          })
                          .catch(()=>{
                              this.$router.push({name: 'user'})
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
