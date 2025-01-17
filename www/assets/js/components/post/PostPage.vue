<template>
  <div class="single-post-page">
    <button @click="goToAnal" class="generate-btn">
      Сгенерить
    </button>
    <Post v-if="post" :post="post" @like-post="handleLikePost" @comment-post="handleCommentPost"/>
    <div class="post-actions">
      <div>Нравится: {{ post?.likes.count }}</div>
      <div>Комментарии: {{ post?.comments.count }}</div>
    </div>
    <CommentList v-if="comments.length" :comments="comments" @reply-to-comment="handleReplyToComment"/>
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
      post: null,
      comments: []
    };
  },
  methods: {
    goToAnal() {
      this.$router.push(`/analytics/${this.$route.params.postId}/${this.$route.params.groupId}`);
    },
    fetchPost() {
      const groupId = this.$route.params.groupId;
      const postId = this.$route.params.postId;
      axios.get(`/api/v1/posts/${groupId}/${postId}`)
          .then(response => {
            const { items, profiles, groups } = response.data;
            const post = items[0];

            this.post = {
              ...post,
              icon: groups[0].photo_50,
              title: groups[0].name,
            };

            this.fetchComments(groupId, postId);
          })
          .catch(error => {
            console.error('Error fetching post:', error);
          });
    },
    fetchComments(groupId, postId) {
      axios.get(`/api/v1/comments/${groupId}/${postId}`)
          .then(response => {
            const { items, profiles, groups } = response.data;
            const profileMap = profiles.reduce((map, profile) => {
              map[profile.id] = profile;
              return map;
            }, {});
            const groupMap = groups.reduce((map, group) => {
              map[group.id] = group;
              return map;
            }, {});

            this.comments = items.map(comment => {
              const author = profileMap[comment.from_id] || groupMap[comment.owner_id];
              return {
                ...comment,
                user: {
                  name: author.first_name ? `${author.first_name} ${author.last_name}` : author.name,
                  avatar: author.photo_50 || 'https://via.placeholder.com/40'
                }
              };
            });
          })
          .catch(error => {
            console.error('Error fetching comments:', error);
          });
    },
    handleLikePost(postId) {
      if (this.post.id === postId) {
        this.post.likes.count += 1;
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
    '$route.params.postId': 'fetchPost'
  },
  mounted() {
    this.fetchPost();
  }
}
</script>

<style scoped>
.single-post-page {
  padding: 16px;
  border-radius: 10px;
  background: #F9F9F9;
}

.single-post-page h1 {
  margin-bottom: 16px;
}

.generate-btn {
  color: #FFF;
  text-align: center;
  font-family: Roboto,sans-serif;
  font-size: 16px;
  font-style: normal;
  font-weight: 700;
  line-height: 20px; /* 125% */
  display: flex;
  width: 310px;
  padding: 12px;
  justify-content: center;
  align-items: center;
  gap: 10px;
  border-radius: 8px;
  background: #5A7BB0;
  border: none;
  margin-bottom: 30px;
}
</style>