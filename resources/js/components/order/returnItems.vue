<template>
  <div>
     <!-- Breadcrumbs-->
     <ol class="breadcrumb mt-3">
          <li class="breadcrumb-item">
            <router-link to="/home">Dashboard</router-link>
          </li>
          <li class="breadcrumb-item active">Returns</li>
        </ol>
    <input type="text" v-model="searchTerm" class="form-control d-inline my-4" placeholder="Enter Invoice number or serial number" style="width:100%;">
    <div v-if="searchTerm">
      <div style="text-align: center; padding:10px 0px 20px 0px" v-if="filtersearch.length < 1">
       <b> This item is not yet sold -- or -- This item is already registered as returned item.</b> 
      </div>
      <table class="table table-bordered table-striped table-hover table-warning border-light" id="" width="100%" cellspacing="0" v-else>
          <thead>
              <tr class="bg-info text-white">
                  <th>Invoice&nbsp;#</th>
                  <th>Serial No</th>
                  <th>Item</th>  
                  <td>Action</td>
              </tr>
          </thead>
          <tbody>
              <tr v-for="item in filtersearch" :key="item.id">
                  <td>{{ item.invoiceNumber }}</td>
                  <td>{{ item.serialNo}}</td>
                  <td>{{ item.product_name }}</td>
                  <td style="text-align: center;">
                      <button class="btn btn-secondary" @click="saveReturn(item)">Add to Return</button>
                  </td>
              </tr>
          </tbody>
        </table>
    </div>
    
    <table class="table table-bordered table-striped table-hover table-warning border-light" id="" width="100%" cellspacing="0" >
      <thead>
          <tr class="bg-info text-white">
              <th>Invoice&nbsp;#</th>
              <th>Serial No</th>
              <th>Item</th>
              <th>Remarks</th>
              <th></th>
              <th>Status</th>
          </tr>
      </thead>
      <tbody>
          <tr v-for="item in returnItems" :key="item.id">
              <td>{{ item.invoiceNumber }}</td>
              <td>{{ item.serialNo}}</td>
              <td>{{ item.product_name }}</td>
              <td>{{ item.remarks }}</td>
              <td>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16" data-toggle="modal" data-target="#editRemarks" type="button" @click="remark(item)">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                  </svg>
              </td>
              <!-- <td>
                <button class="btn btn-secondary text-white" data-toggle="modal" data-target="#editRemarks" type="button" @click="remark(item)">edit remarks</button>
              </td> -->
              <td style="display: flex; justify-content: space-between; margin: 0; ">
                <span class="badge badge-primary badge-pill" v-if="item.status === 'pending' "><p style="padding:4px;margin: auto 0;font-size: 14px;"> {{item.status}} </p></span>
                <span class="badge badge-warning badge-pill" v-if="item.status === 'Under Repair' "><p style="padding:4px;margin: auto 0;font-size: 14px;"> {{item.status}} </p></span>
                <span class="badge badge-info badge-pill" v-if="item.status === 'Fixed'" ><p style="padding:4px;margin: auto 0;font-size: 14px;"> {{item.status}} </p></span>
                <span class="badge badge-success badge-pill" v-if="item.status === 'For Release' "><p style="padding:4px;margin: auto 0;font-size: 14px;"> {{item.status}} </p></span>
                <button class="btn btn-secondary text-white btn-sm" data-toggle="modal" data-target="#updateStatus" type="button" @click="getStat(item)">update</button>
              </td>
                <!------------------ update status modal------------------2-------->
                <div class="modal fade center" id="updateStatus" tabindex="-1" role="dialog" aria-hidden="true" >
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title">{{item_name}} : {{ item_serNo }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeModal">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <select class="form-select" style="width: 100%; margin: 30px 0px 70px 0px" v-model="statusText">
                                  <option value="pending">pending</option>
                                  <option value="Under Repair">Under Repair</option>
                                  <option value="Fixed">Fixed</option>
                                  <option value="For Release">For Release</option>
                                </select>
                            </div>
                            <button class="btn btn-success text-white btn-lg" @click="updateStatus()">Update Status</button>
                        </div>
                    </div>
                </div>
                <!------------ update status modal------------------------->
               <!------------------edit remarks modal------------------2-------->
                <div class="modal fade center" id="editRemarks" tabindex="-1" role="dialog" 
                          aria-hidden="true" >
                          <div class="modal-dialog modal-dialog-centered" role="document">
                              <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title">{{item_name}} : {{ item_serNo }}</h5>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeModal">
                                          <span aria-hidden="true">&times;</span>
                                      </button>
                                  </div>
                                  <div class="modal-body">
                                      <textarea name="" id="" cols="30" rows="10" v-model="remarkText" style="width: 100%;"></textarea>
                                  </div>
                                  <button class="btn btn-success text-white btn-lg" @click="saveRemarks()">Save Remarks</button>
                              </div>
                          </div>
                      </div>
                <!------------edit remarks modal------------------------->
          </tr>
      </tbody>
    </table>
  </div>
</template>
<script>
import Cookies from 'js-cookie';
export default {
  data() {
    return {
      soldItems:[],
      searchTerm:'',
      returnItems: [],
      remarkText:'',
      statusText:'',
      item_name:'',
      item_serNo:'',
      returnId:null
    }
  },
  created() {
    this.getReturns();
    this.getSerials();
  },
  computed:{
            filtersearch(){
                return this.soldItems.filter(item => {
                    return item.invoiceNumber.toLowerCase().match(this.searchTerm.toLowerCase()) || item.serialNo.toLowerCase().match(this.searchTerm.toLowerCase())
                })
            },
     
        },
  methods: {
    getStat(i) {
      this.statusText = i.status;
      this.returnId = i.id;
      this.item_name = i.product_name;
      this.item_serNo = i.serialNo;
    },

    remark(i) {
      this.remarkText = i.remarks;
      this.returnId = i.id;
      this.item_name = i.product_name;
      this.item_serNo = i.serialNo;
    },

    saveRemarks() {
      axios.put('api/returns/' + this.returnId, {
        remarks:this.remarkText
      }).then((res) =>{
        location.reload();
      });
    },

    updateStatus() {
      axios.put('api/returns/' + this.returnId, {
        status:this.statusText
      }).then((res) =>{
        location.reload();
      });
    },

    saveReturn(item) {
      console.log(item)
      this.searchTerm = '';
      let d = new Date();
      let year = d.getFullYear();
      let month = d.getMonth() + 1;
      let day = d.getDate();
      let time = d.getTime();

      let returnNoVal = "R" + month + "T" + year + "N" + day + time;

      axios.post('/api/returns',
        {
          return_no:returnNoVal,
          invoiceNumber:item.invoiceNumber,
          serialNo:item.serialNo,
          product_id:item.product_id,
          product_name:item.product_name,
          created_by:Cookies.get('userNow'),
          remarks:'test',
          status:'pending'
        }
      )  
      .then((r) => {
        console.log('return r',r)
      })
      .catch()
      this.getReturns();
      this.getSerials();
    },

    getSerials() {
      axios.get('/api/serials')
      .then(({data}) => {
        // filter available serials to add to returns
        for(let i=0; i < this.returnItems.length; i++) {
          for(let j=0; j < data.length; j++) {
            if(this.returnItems[i].serialNo == data[j].serialNo) {
            data.splice([j], 1)
            }
          }
        }
        // filter available serials to add to returns, end

        this.soldItems = data;
      })
      .catch()
    },

    getReturns() {
      axios.get('/api/returns')
        .then(({data}) => (this.returnItems = data, console.log('return items',data)))
        .catch()
    }
  }
}

</script>