<template>
  <div class="single-post">
    <div class="post-header">
      <img :src="post.icon" alt="User Avatar" class="avatar" />
      <div class="user-info">
        <h3 class="postname">{{ post.title }}</h3>
      </div>
    </div>
    <div class="post-content">
      <p>{{ post.text }}</p>
      <div class="post-images">
        <img v-for="(image, index) in post.images" :key="index" :src="image" alt="Post Image" class="post-image" />
      </div>
    </div>
    <div class="post-actions">
      <div>Нравится: {{ post.likes.count }}</div>
      <div>Комментарии: {{ post.comments.count }}</div>
    </div>
    <CommentList :comments="comments" :profiles="profiles" @reply-to-comment="handleReplyToComment" />
  </div>
</template>

<script>
import axios from 'axios';
import CommentList from './CommentList.vue';

export default {
  name: 'Post',
  components: {
    CommentList
  },
  props: {
    post: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      comments: [],
      profiles: []
    };
  },
  methods: {
    handleReplyToComment(commentId) {
      console.log(`Reply to comment ${commentId}`);
    },
    fetchComments() {
      axios.get(`http://localhost/api/v1/comments/${this.post.id}`)
          .then(response => {
            this.comments = response.data.items;
            this.profiles = response.data.profiles;
          })
          .catch(error => {
            console.error('Error fetching comments:', error);
          });
    }
  },
  mounted() {
    this.fetchComments();
  }
}
</script>

<style scoped>
.single-post {
border: 1px solid #ccc;
padding: 16px;
margin-bottom: 16px;
border-radius: 8px;
background-color: #F9F9F9;;
  font-family: Roboto,serif;
  font-size: 18px;
  font-weight: 400;
  line-height: 24px;
  letter-spacing: 1px;
  text-align: left;
}

.post-header {
display: flex;
align-items: center;
}

.avatar {
width: 50px;
height: 50px;
border-radius: 50%;
margin-right: 16px;
}

.user-info {
display: flex;
flex-direction: column;
}

.postname {
margin: 0;
font-size: 1.2em;
}

.post-time {
margin: 0;
color: #888;
}

.post-content {
margin-top: 16px;
}

.post-images {
display: flex;
flex-wrap: wrap;
gap: 8px;
margin-top: 16px;
}

.post-image {
max-width: 100%;
height: auto;
border-radius: 8px;
}

.post-actions {
margin-top: 16px;
}

.post-actions button {
margin-right: 8px;
}
</style>