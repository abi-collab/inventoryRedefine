<template>
  <div class="card col-lg-6 border-none pt-3">
    <div class="card-body p-0 m-0">
      <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab">All Product Items</a>
        </li>
        <li class="nav-item" v-for="category in categories" :key="category.id">
          <a class="nav-link" data-toggle="pill" href="#pills-profile" role="tab" @click="$emit('category', category.id)">
            {{ category.category_name }}
          </a>
        </li>
      </ul>
      <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-home" role="tabpanel">
          <input type="text" class="form-control mb-2" placeholder="Search product" :value="searchTerm" @input="$emit('update:searchTerm', $event.target.value)">
          <div class="row">
            <div class="col-md-4 mb-2" v-for="product in filtersearch" :key="product.id">
              <div class="card h-100" @click="$emit('add', product)" style="cursor:pointer">
                <div class="card-body p-2">
                  <strong>{{ product.product_name }}</strong>
                  <div>&#8369; {{ Number(product.selling_price).toLocaleString() }}</div>
                  <small>Stock: {{ product.product_quantity }}</small>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane fade" id="pills-profile" role="tabpanel">
          <input type="text" class="form-control mb-2" placeholder="Search in category" :value="getsearchTerm" @input="$emit('update:getsearchTerm', $event.target.value)">
          <div class="row">
            <div class="col-md-4 mb-2" v-for="product in getfiltersearch" :key="product.id">
              <div class="card h-100" @click="$emit('add', product)" style="cursor:pointer">
                <div class="card-body p-2">
                  <strong>{{ product.product_name }}</strong>
                  <div>&#8369; {{ Number(product.selling_price).toLocaleString() }}</div>
                  <small>Stock: {{ product.product_quantity }}</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'PosCatalog',
  props: {
    categories: { type: Array, default: () => [] },
    filtersearch: { type: Array, default: () => [] },
    getfiltersearch: { type: Array, default: () => [] },
    searchTerm: { type: String, default: '' },
    getsearchTerm: { type: String, default: '' },
  },
  emits: ['add', 'category', 'update:searchTerm', 'update:getsearchTerm'],
};
</script>
