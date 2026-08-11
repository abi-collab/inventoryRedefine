<template>
  <div class="modal fade" id="serialnums" role="dialog" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Provide the serial numbers for the ordered items</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="$emit('close')">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <serials :serials="serialNumbers" :computedSerials="computedSerials" @my-event="$emit('submit', $event)" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import serials from './serials.vue';

export default {
  name: 'PosSerials',
  components: { serials },
  props: {
    serialNumbers: { type: Array, default: () => [] },
    computedSerials: { type: Array, default: () => [] },
  },
  emits: ['submit', 'close'],
};
</script>
