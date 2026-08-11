<template>
  <div>
    <div v-if="isAuthPage" class="kycc-auth">
      <div class="kycc-auth-panel">
        <p class="kycc-brand" style="margin-bottom: 0.25rem">KYCC</p>
        <router-view />
      </div>
    </div>

    <div v-else class="kycc-shell">
      <aside class="kycc-nav">
        <p class="kycc-brand">KYCC Inventory</p>
        <router-link to="/home">Dashboard</router-link>
        <router-link to="/pos">POS</router-link>
        <router-link to="/searchorder">Orders</router-link>
        <router-link to="/sold-items">Sold items</router-link>
        <router-link to="/return-items">Returns</router-link>
        <router-link to="/product">Products</router-link>
        <router-link to="/stock">Stock</router-link>
        <router-link to="/Customer">Customers</router-link>
        <router-link to="/supplier">Suppliers</router-link>
        <router-link to="/expense">Expenses</router-link>
        <router-link to="/log">Audit trail</router-link>
        <router-link to="/users">Users</router-link>
        <router-link to="/logout">Logout</router-link>
      </aside>

      <section class="kycc-main">
        <header class="kycc-top">
          <div>
            <strong>{{ userName || 'Staff' }}</strong>
            <div class="kycc-sync">{{ syncLabel }}</div>
          </div>
          <button class="kycc-btn" type="button" @click="syncNow" :disabled="syncing">
            {{ syncing ? 'Syncing…' : 'Sync now' }}
          </button>
        </header>
        <div class="kycc-card">
          <router-view />
        </div>
      </section>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AppShell',
  data() {
    return {
      syncing: false,
      lastSyncedAt: null,
      syncError: false,
    };
  },
  computed: {
    isAuthPage() {
      const p = this.$route.path;
      return p === '/' || p === '/register' || p === '/forget';
    },
    userName() {
      return localStorage.getItem('user');
    },
    syncLabel() {
      if (this.syncError) return 'Cloud sync: last run had errors';
      if (!this.lastSyncedAt) return 'Cloud sync: not yet run (offline OK)';
      return `Last cloud sync: ${this.lastSyncedAt}`;
    },
  },
  watch: {
    '$route.path'(path) {
      if (!this.isAuthPage) this.loadSyncStatus();
    },
  },
  mounted() {
    if (!this.isAuthPage) this.loadSyncStatus();
  },
  methods: {
    async loadSyncStatus() {
      try {
        const { data } = await window.axios.get('/api/sync/status');
        this.lastSyncedAt = data.last_synced_at;
        this.syncError = data.has_errors;
      } catch {
        // ignore while logged out / offline API
      }
    },
    async syncNow() {
      this.syncing = true;
      try {
        await window.axios.post('/api/sync/now');
        await this.loadSyncStatus();
        window.Toast?.fire({ icon: 'success', title: 'Sync finished' });
      } catch {
        window.Toast?.fire({ icon: 'error', title: 'Sync failed (offline or misconfigured)' });
        this.syncError = true;
      } finally {
        this.syncing = false;
      }
    },
  },
};
</script>
