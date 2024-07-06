<template>
  <div className="single-post-page">
    <h1>Post Details</h1>
    <Post v-if="post" :post="post" @like-post="handleLikePost" @comment-post="handleCommentPost"/>
  </div>
</template>

<script>
import axios from 'axios';
import Post from './Post.vue';
import CommentList from './CommentList.vue';

export default {
  name: 'PostPage',
  components: {
    Post,
    CommentList
  },
  data() {
    return {
      post: {
        id: 1,
        user: {
          name: 'John Doe',
          avatar: 'https://via.placeholder.com/50'
        },
        content: 'This is a detailed post content with images.',
        images: [
          'https://via.placeholder.com/400x400',
          'https://via.placeholder.com/400x400',
          'https://via.placeholder.com/400x400'
        ],
        likes: 10,
      },
    };
  },
  methods: {
    fetchPost() {
      const postId = this.$route.params.id;
      axios.get(`http://localhost/api/v1/post/${postId}`)
          .then(response => {
            this.post = response.data;
          })
          .catch(error => {
            console.error('Error fetching post:', error);
          });
    },
    handleLikePost(postId) {
      if (this.post.id === postId) {
        this.post.likes += 1;
      }
    },
    handleCommentPost(postId) {
// Логика для обработки комментариев
      console.log(`Comment on post ${postId}`);
    },
    handleReplyToComment(commentId) {
// Логика для обработки ответа на комментарий
      console.log(`Reply to comment ${commentId}`);
    }
  },
  watch: {
    '$route.params.id': 'fetchPost'
  },
  mounted() {
    this.fetchPost();
  }
}
</script>

<style scoped>
.single-post-page {
padding: 16px;
}

.single-post-page h1 {
margin-bottom: 16px;
}
</style>