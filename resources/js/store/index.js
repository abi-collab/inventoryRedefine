import Vue from 'vue'
import Vuex from 'vuex'

// import serial_numbers from './modules/serial_numbers';
Vue.use(Vuex);

 export const store = new Vuex.Store({
  state: {
    msg:'hello world',
    serial_numbers:'heeeeeeeeeee'
  }
})

// export default new Vuex.Store({
//   state () {
//     return {
//       count: 0,
//       msg:'hello world',
//       serial_numbers:'heeeeeeeeeee'
//     }
//   },
//   mutations: {
//     increment (state) {
//       state.count++
//     }
//   }
//   // actions: {},
//   // modules: {},
// })