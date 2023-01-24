<template>
  <div>
  <form @submit.prevent="save(serials)">
   <table class="table" style="border:solid grey 2px; border-radius: 5px;">
    <thead>
      <!-- <th>Product&nbsp;Code</th> -->
        <th>Item</th>
        <th style="text-align: center;">Order&nbsp;Qnty</th>
        <th>Serial&nbsp;Numbers</th>
    </thead>
    <tbody>
        <tr v-for="(x, sIndex) in serials">
          <!-- <td>{{x.pro_code}}</td> -->
            <td>{{x.pro_name}}</td>
            <td style="text-align: center;">
                {{x.serials.length}}
            </td>
            <td>
              <span v-for="(z, iIndex) in x.serials" style="display: block;">
                  <!-- <input 
                      type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default"
                      style="margin: 2px 0px"
                      v-model="serials[sIndex].serials[iIndex].serialnum"
                      @keypress.13.prevent
                      required
                  > -->
                  <model-select :options="serialList"  v-model="serials[sIndex].serials[iIndex].serialnum" placeholder="select serial"  @keypress.13.prevent
                      required></model-select>
              </span>
            </td>
        </tr>
    </tbody>
</table>
<div style="display:flex; justify-content:flex-end;">
  <button class="btn btn-md btn-primary text-white" style="width: 20%;" type="submit"> Submit</button>
</div>
  </form>
  </div>
</template>
<script>
import { ModelSelect } from 'vue-search-select'
export default {
  components: {
        ModelSelect
    },
  props:{
    serials: Array
  },
  data() {
    return {
      options: [
                { value: '1', text: 'aa' + ' - ' + '1' },
                { value: '2', text: 'ab' + ' - ' + '2' },
                { value: '3', text: 'bc' + ' - ' + '3' },
                { value: '4', text: 'cd' + ' - ' + '4' },
                { value: '5', text: 'de' + ' - ' + '5' }
            ],
            serialList:[]
    }
  },
  created() {
    axios.get('/api/productSerials').then((res) => {
      res.data.forEach(element => {
          if(element.status == 'in-stock') {
              this.serialList.push({
              value:element.serial_number,
              text:element.serial_number
            })
          } 
      });
    })
  },
  methods: {
    save(serials) {
      // console.log('serials',serials);
      this.$emit('my-event',serials);
    }
  },
  // computed: {
  //   availableSerials() { // all serials of serial object join to 1 array
  //     let x = [];
  //     for(let i=0; i < this.serials.length; i++) {
  //       for(let j=0; j < this.serials[i].serials.length; j++) {
  //         x.push(this.serials[i].serials[j])
  //       }
  //     }
  //     let d = [];
  //     for(let k=0; k < x.length; k++) {
  //       d.push(x[k].serialnum);
  //     }
  //     return d;
  //   },

  //   filteredSerialList() { // filtering used/sold serial numbers
  //     let abc = this.serialList;
  //     if(this.availableSerials.includes("")) {
  //       abc = this.serialList;
  //     }
  //     for(let i=0; i < abc.length; i++) {
  //       if(this.availableSerials.includes(abc[i].value)) {
  //         abc.splice([i], 1);
  //       }
  //     }
  //     return abc;
  //   }

  // }
}


</script>