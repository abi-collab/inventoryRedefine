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
                  <input 
                      type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default"
                      style="margin: 2px 0px"
                      v-model="serials[sIndex].serials[iIndex].serialnum"
                      required
                  >
              </span>
            </td>
        </tr>
    </tbody>
</table>
<div style="display:flex; justify-content:flex-end;">
  <button class="btn btn-md btn-primary text-white" style="width: 20%;"  type="submit"> Submit</button>
</div>
  </form>
{{serials}}
  </div>
</template>
<script>
export default {
props:{
  serials: Array
},
methods: {
  save(serials) {
    // console.log('serials',serials);
    this.$emit('my-event',serials);
  }
}
}


</script>