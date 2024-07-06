<template>
  <div id="app">
    <nav>
      <button @click="goToAuth">Go to Auth</button>
    </nav>
    <router-view/>
  </div>
</template>

<script>
export default {
  name: 'App',
  methods: {
    goToAuth() {
      this.$router.push('/auth');
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