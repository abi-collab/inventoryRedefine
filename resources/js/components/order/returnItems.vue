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
    <table class="table table-bordered table-striped table-hover table-warning border-light" id="" width="100%" cellspacing="0" v-if="searchTerm">
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
              <td><button class="btn btn-secondary text-white" data-toggle="modal" data-target="#editRemarks" type="button" @click="remark(item)">edit remarks</button></td>
              <td>
                <span class="badge badge-primary" v-if="item.status === 'pending' ">{{item.status}}</span>
                <span class="badge badge-warning" v-if="item.status === 'Repair' ">{{item.status}}</span>
                <span class="badge badge-info" v-if="item.status === 'Fixed'" >{{item.status}}</span>
                <span class="badge badge-success" v-if="item.status === 'Release' ">{{item.status}}</span>
              </td>
               <!------------------customer add modal------------------2-------->
                <div class="modal fade center" id="editRemarks" tabindex="-1" role="dialog" 
                          aria-hidden="true" >
                          <div class="modal-dialog" role="document">
                              <div class="modal-content">
                                  <div class="modal-header">
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeModal">
                                          <span aria-hidden="true">&times;</span>
                                      </button>
                                  </div>
                                  <div class="modal-body">
                                      <textarea name="" id="" cols="30" rows="10" v-model="remarkText"></textarea>
                                  </div>
                                  <button @click="saveRemarks()">save</button>
                              </div>
                          </div>
                      </div>
                <!------------end customer modal------------------------->
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
    remark(i) {
      this.remarkText = i.remarks;
      this.returnId = i.id;
    },
    saveRemarks() {
      axios.put('api/returns/' + this.returnId, {
        remarks:this.remarkText
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