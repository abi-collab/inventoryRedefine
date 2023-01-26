<template>
  <div>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb mt-3">  
          <li class="breadcrumb-item">
              <router-link to="/home">Dashboard</router-link>
          </li>
          <li class="breadcrumb-item active">Users / Edit</li>
      </ol>
      <!-- Icon Cards-->
      <div class="row container">
          <div class="card col-lg-12 border-light shadow">    
              <div class="card-header text-dark mb-4" style="font-size: 20px; font-weight: 700;">
                  <i class="fas fa-chart-area"></i>
                  Users Update
                  <router-link to="/Users" class="btn btn-dark" id="add_new"> All Users</router-link>
              </div>
              <div class="card-body p-0 m-0">
                  <form @submit.prevent="confirm" enctype="multipart/form-data">
                      <div class="form-group">
                          <div class="form-row">
                              <div class="col-md-6">
                                  <div class="form-label-group">
                                      <label for="firstName">Name</label>
                                      <input type="text" id="firstName" v-model="form.name" class="form-control" disabled>
                                      <small class="text-danger" v-if="errors.name">{{ errors.name[0] }}</small>
                                  </div>
                              </div>
                              <div class="col-md-6">
                                  <div class="form-label-group">
                                      <label for="username">Username</label>
                                      <input type="text" id="username" v-model="form.username" class="form-control" disabled>
                                      <small class="text-danger" v-if="errors.username">{{ errors.username[0] }}</small>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="form-group">
                          <div class="form-row">
                              <div class="col-md-12">
                                  <div class="form-label-group">
                                      <label>User Role</label>
                                      <!-- <input type="text" v-model="form.user_role" class="form-control"  required=""> -->
                                      <select v-model="form.user_role" class="form-control"  required="" :value="form.user_role">
                                        <option value="2">Standard User</option>
                                        <option value="1">Admin</option>
                                      </select>
                                      <small class="text-danger" v-if="errors.user_role">{{ errors.user_role[0] }}</small>
                                  </div>
                              </div>
                           
                          </div>
                      </div>
                   
                      <div class="form-group">
                          <div class="form-row">
                              <div class="col-md-6">
                                  <div class="form-label-group">                  <!------------------------------>
                                      <input type="file" class="btn btn-info" @change="onFileselected">
                                      <small class="text-danger" v-if="errors.photo">{{ errors.photo[0] }}</small>
                                  </div>
                              </div>
                              <div class="col-md-6">                      <!----------------------------->
                                  <img :src="'/'+form.photo" style="height:40px; width: 40px;">
                              </div>
                          </div>
                      </div>

                      <button type="submit" class="btn btn-success">Update</button>
                  </form>
                <!-- {{ form }} -->
              </div>
              <div class="card-footer small text-muted"></div>
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
                name:'',
                username:'',
                user_role:null
              },
              errors:{},
              oldName:'',
              form2:{ //2
                  activity :'',
                  createdby : Cookies.get('usersname')
              },
          }
      },
      created(){                    //---for showing existing data in form to Edit/update
          let id = this.$route.params.user.id              //--taking id from route/url
          axios.get('/api/users/'+id)              //--targeting show() method
              .then((data) => {
                this.form.user_role = data.data.user_role,
                this.form.username = data.data.username,
                this.form.name = data.data.name,  
              this.oldName = this.form.name;
            
              console.log('data',data);
              console.log('id',id);
            }
              )
              .catch()
      },
      computed: { //3
        nameIs() {
          return this.form.name;
        }
      },
      methods:{
          onFileselected(event){          //click korlei ai 'event' er vitor pic er sob details chole asbe
              let file=event.target.files[0];     //now,File's(name,size,type) available in variable 'file'
              if (file.size > 1048770) {          //made condition: file will less than 1MB(1024*1024=1048576 byte)
                  Notification.image_validation()
              }else{
                  let reader = new FileReader();       //created new instance
                  reader.onload = event => {
                      this.form.newphoto = event.target.result    //storing/taking new_pic's extention in 'newphoto'
                  };
                  reader.readAsDataURL(file);
              }
          },
          UsersUpdate(){    
              if(this.nameIs == this.oldName) {
            this.form2.activity = `Update info of Users ${this.nameIs}`
          } else {
            this.form2.activity = `Users update: changed name from ${this.oldName} to ${this.nameIs}`;//4
          }
                                         //--updating process are here
              let id = this.$route.params.user.id                //--taking id from route/url
              axios.post('/api/users/'+id,this.form)    //--patch will auto call update() mathod
                  .then(() => {
                      this.$router.push({ name: 'Users' })
                      Notification.success();
                      axios.post('/api/activitylog',this.form2)  //5
                      .then((r) => {
                          this.$router.push({ name:'/' });
                      })
                      .catch(error => this.errors = error.response.data.errors)
                      
                  })
                  .catch(error => this.errors = error.response.data.errors)
          },
          confirm() {
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: 'btn btn-success',
          cancelButton: 'btn btn-danger'
        },
        buttonsStyling: true
      })

      swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "Following details can still be update when saved",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Confirm',
        cancelButtonText: 'Cancel',
        reverseButtons: true
      }).then((result) => {
        if (result.isConfirmed) {
          this.UsersUpdate();
          swalWithBootstrapButtons.fire('Successfully Saved')
        } else if (result.dismiss === Swal.DismissReason.cancel) {
          swalWithBootstrapButtons.fire(
            'Cancelled',
          )
        }
      })
    }
      }
  }
</script>


<style>
  #add_new{
      float: right;
  }
</style>
