<template>
    <div>
        <div class="container">
<div class="row justify-content-left">
    <div class="col-lg-7 offset-1">
        <div class="card shadow-lg border-light rounded-lg mt-5">
            <div class="card-header"><h3 class="text-center text-dark font-weight-bold my-4">Register New Account</h3></div>
            <div class="card-body">

                <form @submit.prevent="signup">
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="mb-1" for="inputFirstName">Full Name</label>
                                <input class="form-control py-4" id="inputFirstName" type="text" placeholder="Enter Full Name" v-model="form.name"/>

                                <small class="text-danger" v-if="errors.name" style="color:red">{{ errors.name[0] }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="mb-1" for="inputUsername">Username</label>
                        <input class="form-control py-4" id="inputUsername" type="text" placeholder="Enter Username" v-model="form.username"/>
                    </div>

                    <div class="form-group">
                        <label class="mb-1" for="inputEmailAddress">Email</label>
                        <input class="form-control py-4" id="inputEmailAddress" type="email" aria-describedby="emailHelp" placeholder="Enter Email Address" v-model="form.email"/>

                        <small class="text-danger" v-if="errors.email" style="color:red">{{ errors.email[0] }}</small>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="mb-1" for="inputPassword">Password</label>
                                <input class="form-control py-4" id="inputPassword" type="password" placeholder="Enter Password" v-model="form.password"/>

                                <small class="text-danger" v-if="errors.password" style="color:red">{{ errors.password[0] }}</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="mb-1" for="inputConfirmPassword">Confirm Password</label>
                                <input class="form-control py-4" id="inputConfirmPassword" type="password" placeholder="Confirm Password" v-model="form.password_confirmation"/>
                            </div>
                        </div>
                        <div class="col-md-6" v-if="form.name && form.username && form.email && form.password && form.password_confirmation">
                            <div class="form-group">
                                <label class="mb-1" for="inputConfirmPassword">Permission Code</label>
                                <input class="form-control py-4" id="inputConfirmPassword" type="password" v-model="adminCode"/>
                            </div>
                        </div>
                        <div class="col-md-6" v-if="adminCode == 'admin321'">
                            <label class="mb-1" for="userRole">Role</label>
                                <select class="form-select py-3" style="width: 100%;" id="userRole" v-model="form.user_role">
                                    <option value="2">standard user</option>
                                    <option value="1">admin</option>
                                </select>
                         
                        </div>
                    </div>

                    <div class="form-group mt-4 mb-0">
                        <button type="submit" class="btn btn-dark btn-block" v-if="adminCode == 'admin321'"> Sign up </button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center">
                <div class="">
                    <router-link to="/">Already have an account? Go to login</router-link>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
    </div>
</template>


<script>
import Cookies from 'js-cookie';  
    export default {
        created(){
            if (User.loggedIn()) {
                this.$router.push({name:'home'})   // or, //this.$router.push('/home')
            }
        },
        data(){
            return {
                form:{
                    name:null,
                    username:null,
                    email:null,
                    password:null,
                    password_confirmation:null,
                    user_role:null
                },
                errors:{},
                adminCode:''
            }
        },
        methods:{
            signup(){ 
                // alert('done');   //--testing submit
                axios.post('/api/auth/signup',this.form)
                // .then(response => console.log(response.data))
                .then(response => {
                    Cookies.set('userNow', response.data.user_role, { expires: 7 });
                    console.log('resssss', response);
                    User.responseAfterLogin(response)
                    Toast.fire({
                        icon: 'success',
                        title: 'Signed in Successfully'
                    })
                    this.$router.push('/home')    // or, this.$router.push({name:'home})
                })
                // .catch(error => console.log(error.response.data))
                .catch(error => this.errors = error.response.data.errors)
            }
        }
    }
</script>


<style>

</style>
