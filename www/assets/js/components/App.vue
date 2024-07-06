<template>
  <div>
    <div v-if="isVisible" id="app">
      <nav>
        <button @click="goToGroups">Go to Groups</button>
      </nav>
    </div>
    <router-view/>
  </div>
</template>

<script>
export default {
  name: 'App',
  data() {
    return {
      isVisible: true
    };
  },
  methods: {
    goToGroups() {
      this.isVisible = false;
      this.$router.push('/groups');
    }
  }
};
</script>

<style>
nav {
  display: flex;
  gap: 10px;
}
</style>