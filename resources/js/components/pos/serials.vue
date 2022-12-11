<template>
  <div style="border:solid red">
  <h1>serials component here!</h1>
  <br><br>
  <form @submit.prevent="save(serials)">
   <table>
    <thead>
      <th>prod id</th>
        <th>Item</th>
        <th>Qty</th>
        <th>Serials</th>
    </thead>
    <tbody>

        <tr v-for="(x, sIndex) in serials">
          <td>{{x.pro_id}}</td>
            <td>{{x.pro_name}}</td>
            <td>
                {{x.serials.length}}
            </td>
            <td>
                <ol>
                    <li v-for="(z, iIndex) in x.serials">
                        <input 
                            type="number" 
                            v-model="serials[sIndex].serials[iIndex].serialnum"
                        >
                    </li>
                </ol>
            </td>
        </tr>
    </tbody>
</table>
<button type="submit">save</button>
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