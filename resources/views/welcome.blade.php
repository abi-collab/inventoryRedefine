<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="csrf-token" content="{{ csrf_token() }}">   <!--this is for 'error free console'/(vue_devTool)-->
        <title>Kuya Allan Computer Center</title>

        <link rel="stylesheet" href="{{ asset('css/app.css') }}">   <!--must be linked at the Top of All other CSS_files-->
        <link href="{{ asset('backend/css/styles.css') }}" rel="stylesheet" />
        <script src="{{ asset('backend/js/all.min.js') }}" crossorigin="anonymous"></script>
    </head>


    <body class="sb-nav-fixed">

    <div id="app">     <!---VUE_JS er jonno ei id='app' div ta niyechi pora/entire body tar jonno--->

    <!-----------start_Top_Navbar----------->
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark" id="topbar" v-show="$route.path === '/' || $route.path === '/register' || $route.path === '/forget' ? false : true" style="display: none;"> <!-------------------->
            <router-link class="navbar-brand" to="/home"> KYCC POS & Inventory</router-link>
            <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
            <!-- <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
                <div class="input-group">
                    <input class="form-control" type="text" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2" />
                    <div class="input-group-append">
                        <button class="btn btn-secondary" type="button"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form> -->
            <marquee class="text-white d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">Kuya Allan Computer Center Sales and Inventory System</marquee>

            <ul class="navbar-nav ml-auto ml-md-0">
                <li class="nav-item dropdown"> 
                    <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i> 

                </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <!-- <router-link class="dropdown-item" to="/log">Activity Log</router-link>
                        <div class="dropdown-divider"></div> -->
                        <router-link class="dropdown-item" to="/logout">Logout</a>   <!------------Logout------------>
                    </div>
                </li>
            </ul>
        </nav>      <!---End_Top_Navbar--->


<!--------------------Left_Navbar------------------------>
    <div id="leftbar" v-show="$route.path === '/' || $route.path === '/register' || $route.path === '/forget' ? false : true" style="display: none;">       <!----------------------------->
        <div id="layoutSidenav" >
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion" >
                    <div class="sb-sidenav-menu">

                        <div class="nav">                       <!----------------------------->
                            {{-- <div class="sb-sidenav-menu-heading">Core</div> --}}
                            <!-- <marquee class="text-white">#&nbsp&nbsp Laraval-5.8 &nbsp&nbsp#&nbsp&nbsp Vue  &nbsp&nbsp#&nbsp&nbsp Api &nbsp&nbsp#&nbsp&nbsp JWT &nbsp&nbsp#&nbsp&nbsp Inventory_POS</marquee> -->
                          
                            <router-link class="nav-link mt-4" to="/home">
                                <!-- <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div> -->
                               <b>DASHBOARD</b> 
                            </router-link>
                          
                            <router-link class="nav-link" to="/pos">
                                <!-- <div class="sb-nav-link-icon text-white"><i class="fas fa-tachometer-alt"></i></div> -->
                                <b> POS </b>
                            </router-link>
                         
                              <!--------------Orders----------->  
                              
                              <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLayouts7" aria-expanded="false" aria-controls="collapseLayouts" >
                                <!-- <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div> -->
                                Sales
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts7" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <!-- <router-link class="nav-link" to="/order">Today Order</router-link> -->
                                    <router-link class="nav-link" to="/searchorder">Purchased Orders</router-link>
                                    <router-link class="nav-link" to="/sold-items">Sold Items</router-link>
                                    <router-link class="nav-link" to="/return-items">In-Warranty</router-link>
                                </nav>
                            </div>

                    <!--------------Employee----------->
                            <!-- <router-link class="nav-link" to="/employee" v-if="{{$_COOKIE['userNow']}} == 1">
                              
                                Employees 
                            </router-link> -->
                            <!-- {{-- <div class="sb-sidenav-menu-heading">Interface</div> --}}
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts" >
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Employee
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <router-link class="nav-link" to="/store-employee">Add Employee</router-link>
                                    <router-link class="nav-link" to="/employee">All Employee</router-link>
                                </nav>
                            </div> -->
                    <!--------------Supplier----------->
                            <router-link class="nav-link" to="/supplier" v-if="{{$_COOKIE['userNow']}} == 1">
                                Suppliers
                            </router-link>
                            <!-- <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLayouts2" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Suppliers
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts2" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <router-link class="nav-link" to="/store-supplier">Add Supplier</router-link>
                                    <router-link class="nav-link" to="/supplier">All Supplier</router-link>
                                </nav>
                            </div> -->

                    <!--------------Categories----------->
                            <!-- <router-link class="nav-link" to="/category" v-if="{{$_COOKIE['userNow']}} == 1">
                                Categories
                            </router-link> -->
                            <!-- <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLayouts3" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Categories
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts3" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <router-link class="nav-link" to="/store-category">Add Category</router-link>
                                    <router-link class="nav-link" to="/category">All Category</router-link>
                                </nav>
                            </div> -->

                    <!--------------Product----------->
                            <router-link class="nav-link" to="/product" v-if="{{$_COOKIE['userNow']}} == 1">
                                Products
                            </router-link>
                            <!-- <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLayouts4" aria-expanded="false" aria-controls="collapseLayouts" >
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Products
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts4" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <router-link class="nav-link" to="/store-product">Add Product</router-link>
                                    <router-link class="nav-link" to="/product">All Product</router-link>
                                </nav>
                            </div> -->

                    <!--------------Customer----------->
                            <router-link class="nav-link" to="/Customer">
                                Customers
                            </router-link>
                            <!-- <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#LayoutsC" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Customers
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="LayoutsC" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <router-link class="nav-link" to="/store-Customer">Add Customer</router-link>
                                    <router-link class="nav-link" to="/Customer">All Customer</router-link>
                                </nav>
                            </div> -->

                    <!--------------Expense----------->
                            <router-link class="nav-link" to="/expense" v-if="{{$_COOKIE['userNow']}} == 1">
                                Expenses
                            </router-link>
                            <!-- <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLayouts5" aria-expanded="false" aria-controls="collapseLayouts" >
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Expense
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts5" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <router-link class="nav-link" to="/store-expense">Add Expense</router-link>
                                    <router-link class="nav-link" to="/expense">All Expense</router-link>
                                </nav>
                            </div> -->

                    <!--------------Salary----------->
                            <!-- <router-link class="nav-link" to="/salary" v-if="{{$_COOKIE['userNow']}} == 1" >
                                Salary
                            </router-link> -->
                            <!-- <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLayouts6" aria-expanded="false" aria-controls="collapseLayouts"  v-if="{{$_COOKIE['userNow']}} == 1" >
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Salary
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts6" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <router-link class="nav-link" to="/given-salary">Pay Salary</router-link>
                                    <router-link class="nav-link" to="/salary">All Salary</router-link>
                                </nav>
                            </div> -->

                    <!--------------Stock----------->
                            <router-link class="nav-link collapsed" to="/stock" v-if="{{$_COOKIE['userNow']}} == 1">
                                <!-- <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div> -->
                                Stock
                            </router-link>
                            <router-link class="nav-link collapsed" to="/log" v-if="{{$_COOKIE['userNow']}} == 1">Audit Trail</router-link>
                            <router-link class="nav-link collapsed" to="/users" v-if="{{$_COOKIE['userNow']}} == 1">Users</router-link>
                            <!-- <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#archive" aria-expanded="false" aria-controls="collapseLayouts" v-if="{{$_COOKIE['userNow']}} == 1" >
                                Archive
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="archive" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                    <router-link class="nav-link" to="/searchorder">Employee</router-link>
                                    <router-link class="nav-link" to="/sold-items">Suppliers</router-link>
                                    <router-link class="nav-link" to="/return-items">Products</router-link>
                                    <router-link class="nav-link" to="/return-items">Customers</router-link>
                                </nav>
                            </div> -->
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>  <!---End_Left_Navbar-->

<!--------------------Dashboard/Body------------------------>
        <div id="layoutSidenav" style="background-color: #f4f4f8">
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <router-view></router-view>     <!-------------------------------------->
                    </div>
                </main>
            </div>
        </div><!---End__Dashboard--->

</div>  <!---End___VUE_JS er jonno j id='app' div ta niyechilam pora body tar jonno--->


    <!---------JavaScript_external_files-------->
        <script src="{{ asset('js/app.js') }}"></script>   <!--must be linked at the Top of All other JS_files-->
        <script src="{{ asset('backend/assets/demo/jquery-3.5.1.min.js') }}" crossorigin="anonymous"></script>

        <script>    //<!--Topbar & Navbar Show korano[after login//'token' takle]-->
        
            
            let token = localStorage.getItem('token');
            if(token) {
                $("#topbar").css("display","");
                $("#leftbar").css("display","");
            }

            ////////cookie
            //  let cook = document.cookie;
            //  let cook2 = cook.split(';');
            //  let role;
            //     for( let i = 0 ; i < cook2.length; i ++) {
            //         if(cook2[i].includes('userNow')) {
            //             role = cook2[i];
            //         }
            //     }
                // let roleId = role.split("=");
               

              ////////cookie

              function getCookie(name) {
                var dc = document.cookie;
                var prefix = name + "=";
                var begin = dc.indexOf("; " + prefix);
                if (begin == -1) {
                    begin = dc.indexOf(prefix);
                    if (begin != 0) return null;
                }
                else
                {
                    begin += 2;
                    var end = document.cookie.indexOf(";", begin);
                    if (end == -1) {
                    end = dc.length;
                    }
                }
                // because unescape has been deprecated, replaced with decodeURI
                //return unescape(dc.substring(begin + prefix.length, end));
                return decodeURI(dc.substring(begin + prefix.length, end));
            }

              function doSomething() {
                    var myCookie = getCookie("userNow");

                    if (myCookie == null) {
                        // do cookie doesn't exist stuff;
                        console.log('null');
                        document.cookie = "userNow=0";
                    }
                    else {
                        // do cookie exists stuff
                        console.log('meron')
                        // document.cookie = "userNow=1";
                    }
                }

                doSomething();

        </script>

        {{-- <script src="{{ asset('backend/assets/demo/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script> --}}
            <!--//--output e login/setting option gula click kora jacchilo na tai amra ei BS_4 file ta comment korechi--//-->
        <script src="{{ asset('backend/js/scripts.js') }}"></script>

    </body>
</html>
