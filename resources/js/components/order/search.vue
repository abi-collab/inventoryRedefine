<template>
    <div>
        <!-- Breadcrumbs-->
        <ol class="breadcrumb mt-3 noPrint">
            <li class="breadcrumb-item">
                <router-link to="/home">Dashboard</router-link>
            </li>
            <li class="breadcrumb-item active">Orders / Search Order</li>
        </ol>
        <!-- Icon Cards-->
        <div class="row noPrint" style="margin-bottom: 500px;">
            <div class="card col-lg-12 border-light shadow">
                <div class="card-header text-dark mb-4" style="font-size: 20px; font-weight: 700;">
                    All Purchased Orders
                    (Search Orders)
                    <router-link to="/order" class="btn btn-dark" id="add_new"> Today Order</router-link>
                </div>

                <!------------Search By Date---------------->
                <div class="card-body p-0 m-0 noPrint">
                    <div class="row">
                        <div class="col" style="display: flex; justify-content:end;">
                            <!-- <label class="d-inline">Search : </label>  -->
                            <input type="text" v-model="searchTerm" class="form-control d-inline" style="width:300px"
                                placeholder="Enter Invoice Number ">
                        </div>
                        <div class="col" style="display:flex; justify-content: end">
                            <vue-daterange-picker double start-date="12/01/2022" end-date="12/01/2023"
                                start-place-holders="12/01/2000" end-place-holders="04/01/2030" @get-dates="getDates" />
                        </div>
                        <div class="col">
                            <div  style="display: flex;">
                            <button type="button" class="btn btn-outline-success" style="margin-right: 10px;" @click="reload()">reset</button>
                            <button type="button" class="btn btn-outline-success" @click="htmlTableToExcel()">Export Excel</button>
                            </div>
                           
                            <!-- <download-excel 
                                :data="excelData"
                                :name="xlName() + '.xls'"
                                >
                                <button type="button" class="btn btn-outline-success">Export Excel</button>
                            </download-excel> -->
                        </div>
                    </div>
                    <br>
                    <br>
                    <h5> &#8369;&nbsp; {{(Number(allCost).toLocaleString() || 0)}}</h5>
                    <div class="table table-responsive">
                        <table class="table table-bordered table-striped table-hover table-warning border-light" id="tblToExcl"
                            width="100%" cellspacing="0">
                            <thead class="noPrint">
                                <tr class="bg-info text-white">
                                    <th>Invoice&nbsp;#</th>
                                    <th>Name</th>
                                    <th>Quantity</th>
                                    <th>Total Due</th>
                                    <th>View</th>
                                </tr>
                            </thead>    
                            <tbody>
                                <tr v-for="order in filterSearchWithDate.reverse()" :key="order.id">
                                    <td class="noPrint">{{ order.invoiceNum }}</td>
                                    <td class="noPrint">{{ order.name }}</td>
                                    <td class="noPrint" style="text-align: right;">{{ order.qty }}</td>
                                    <td class="noPrint" style="text-align: right;">&#8369;&nbsp;
                                        {{(Number(order.sub_total).toLocaleString() || 0)}}</td>
                                    <td style="text-align: center;">
                                        <router-link :to="{ name: 'view-order', params: { id: order.id } }"
                                            class="btn btn-sm btn-info noPrint">Details</router-link>
                                        <button type="button" @click="ImgToModal(order.invoiceImg)"
                                            class="btn btn-outline-secondary noPrint btn-sm" data-toggle="modal"
                                            data-target=".bd-example-modal-lg">Reprint Invoice</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- <div class="row">
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
    </div> -->
                </div> <!-----------End_Search_text_box------------------>
            </div>
        </div>
        <img id="printMe" :src="modalImg" v-show="showNow">
    </div>
</template>

<script>
// import $ from 'jquery'; 
import XLSX from 'xlsx';
import html2canvas from 'html2canvas';
import moment from 'moment'
import VueDaterangePicker from 'vue-daterange-picker';

export default {
    components: {
        VueDaterangePicker
    },
    created() {
        let dateNow = new Date();
        let month = dateNow.getMonth() + 1;
        let monthName = moment(month, "MM-DD-YYYY")
            .format('MMMM')

        this.month = monthName;
        this.searchMonth();

    },
    mounted() {
        if (!User.loggedIn()) {
            this.$router.push({ name: '/' })
        }
    },
    data() {
        return {
            date: '',
            orders: [],
            month: '',
            searchTerm: '',
            modalImg: '',
            showNow: true,
            waterMark: 'Select a Range',
            datesss: {},
            startDate: '',
            endDate: '',
            excelData:[]
        }
    },
    computed: {
        quantity() {
            let sum = 0;
            for (let i = 0; i < this.orders.length; i++) {
                sum += (parseFloat(this.orders[i].qty));
            }
            return sum;
        },
        subtotal() {
            let sum = 0;
            for (let i = 0; i < this.orders.length; i++) {
                sum += (parseFloat(this.orders[i].sub_total));
            }
            return sum;
        },
        total() {
            let sum = 0;
            for (let i = 0; i < this.orders.length; i++) {
                sum += (parseFloat(this.orders[i].total));
            }
            return sum;
        },
        pay() {
            let sum = 0;
            for (let i = 0; i < this.orders.length; i++) {
                sum += (parseFloat(this.orders[i].pay));
            }
            return sum;
        },
        due() {
            let sum = 0;
            for (let i = 0; i < this.orders.length; i++) {
                sum += (parseFloat(this.orders[i].due));
            }
            return sum;
        },
        filtersearch() {
            return this.orders.filter(order => {
                return order.invoiceNum.toLowerCase().match(this.searchTerm.toLowerCase())
            })
        },
        filterSearchWithDate() {
            let filtered = this.filtersearch.filter((x) => {
                if (new Date(x.order_date) >= new Date(this.startDate) && new Date(x.order_date) <= new Date(this.endDate)) {
                    return x;
                }
            });

            if (this.startDate && this.endDate) {
                return filtered;
            } else {
                return this.filtersearch;
            }
        },
        allCost() {
            let cost = 0;

            for(let i = 0; i < this.filterSearchWithDate.length; i++) {
                cost += Number(this.filterSearchWithDate[i].sub_total);
            }
            return cost;
        }
        
    },
    methods: {
        getDates(i) {
            this.startDate = i.startDate;
            this.endDate = i.endDate;

        },
        searchDate() {
            var data = { date: this.date }
            axios.post('/api/search/order', data)
                .then(({ data }) => (this.orders = data))
                .catch()
        },
        searchMonth() {
            var data = { month: this.month }
            axios.post('/api/search/month', data)
                .then(({ data }) => (this.orders = data))
        },
        ImgToModal(base64) {
            this.modalImg = base64;
            this.printNa();
        },
        async print() {
            await this.$htmlToPaper("printMe");
        },
        printNa() {
            this.showNow = true;
            html2canvas(document.querySelector("#printMe")).then((canvas) => {
                print();
                this.showNow = false;
            })
        },
        reload() {
            location.reload();
        },
        htmlTableToExcel(type){
            var data = document.getElementById('tblToExcl');
            var excelFile = XLSX.utils.table_to_book(data, {sheet: "sheet1"});
            XLSX.write(excelFile, { bookType: type, bookSST: true, type: 'base64' });
            XLSX.writeFile(excelFile, 'ExportedFile:HTMLTableToExcel' + type);
        },
        xlName() {
            return new Date().getTime() / 1000;
        },
        dataForExcel() {
            let x = this.filterSearchWithDate;
            for(let c=0; c < x.length; c++) {
                x[c].invoiceImg = '';
            }
            this.excelData = x;
            return x;
        }
    }
}
</script>


<style>
#add_new {
    float: right;
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
