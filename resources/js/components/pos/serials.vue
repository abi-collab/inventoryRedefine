<template>
  <div>
  <form @submit.prevent="save(serials)">
   <table class="table">
    <thead>
      <th>Product&nbsp;Code</th>
        <th>Item</th>
        <th style="text-align: center;">Order&nbsp;Qnty</th>
        <th>Serial&nbsp;Numbers</th>
    </thead>
    <tbody>

        <tr v-for="(x, sIndex) in serials">
          <td>{{x.pro_code}}</td>
            <td>{{x.pro_name}}</td>
            <td style="text-align: center;">
                {{x.serials.length}}
            </td>
            <td>
              <span v-for="(z, iIndex) in x.serials" style="display: block;">
                  <input 
                      type="text" 
                      v-model="serials[sIndex].serials[iIndex].serialnum"
                  >
              </span>
            </td>
        </tr>
    </tbody>
</table>

<button class="btn btn-sm btn-primary text-white" data-toggle="modal" data-target="#serialnums" type="submit"> Save</button>
  </form>
 
    
{{serials}}
  </div>
</template>
<script>
export default {
props:{
  serials: Array
},
// created() {
//   this.sers = serials;
// },
data() {
  return {
    sers:[]
  }
}, methods: {
  save(serials) {
    console.log('serials',serials);
  }
}
}


</script>