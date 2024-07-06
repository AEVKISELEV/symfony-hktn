<template>
  <header>
    <!-- Add your header content here -->
  </header>
  <div class="group-page-wrapper">
    <section class="group-page group-list"></section>
    <section class="group-page comment-list"></section>
  </div>
</template>

<style scoped>
body {
  margin: 0;
  padding: 0;
  width: 100%;
  height: 100%;
}

.group-page-wrapper {
  background-color: #EDEEF0;
  display: grid;
  grid-template-columns: 3fr 1fr;
  gap: 40px;
  height: 100vh;
  max-width: 3800px;
  width: 1366px;
}

.group-page {
  background-color: white;
  margin-top: 60px;
  border-radius: 20px;
  flex: 1;
}

.group-list {
  margin-left: 60px;
  width: 856px;
}

.comment-list {
  margin-right: 60px;
  width: 350px;
  height: 648px;
}

</style>
