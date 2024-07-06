<template>
  <div>
    <div v-if="status === 'ok'">
      <div v-for="(item, index) in data" :key="index" class="content-item">
        <div v-if="item.type === 'general'" class="text-content">
          {{ item.content }}
        </div>
        <div v-if="item.type === 'image'" class="image-content">
          <img :src="item.content" alt="Image Content"/>
        </div>
      </div>
    </div>
    <div v-else>
      <p>Loading...</p>
    </div>
  </div>
</template>

<script setup>
import {ref, onMounted} from 'vue';
import axios from 'axios';
import {useRoute} from 'vue-router';

const route = useRoute();
const status = ref('');
const data = ref([]);

const fetchAnalytics = async () => {
  const postId = route.params.postId;
  const groupId = route.params.groupId;
    axios.post(`/api/v1/analytic/generate`, {
      postId: groupId, groupId: postId,
    });

    setInterval(async () => {
      const response = await axios.get(`/api/v1/analytic/${postId}/${groupId}`);
      status.value = response.data.status;
      data.value = response.data.data;
    }, 500)
};

onMounted(() => {
  fetchAnalytics();
});
</script>

<style scoped>
.content-item {
  margin-bottom: 20px;
}

.text-content {
  font-size: 16px;
  margin-bottom: 10px;
}

.image-content img {
  max-width: 100%;
  height: auto;
  border-radius: 8px;
}
</style>