<template>
  <div>
    <div v-if="isVisible" id="app">
      <nav>
        <button @click="goToGroups">Go to Groups</button>
      <button @click="goToPost">Go to Post</button>
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
      isVisible: true,
    };
  },
  methods: {
    goToGroups() {
      this.isVisible = false;
      this.$router.push('/groups');
    },
    goToPost() {
      this.$router.push('/post/-216195050/23');
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