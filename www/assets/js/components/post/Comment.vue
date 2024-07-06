<template>
  <div class="comment" v-if="comment">
    <div class="comment-header">
      <img :src="comment.user.avatar" alt="User Avatar" class="avatar" />
      <div class="user-info">
        <h4 class="username">{{ comment.user.name }}</h4>
        <div class="comment-content">
          <p>{{ comment.text }}</p>
        </div>
        <p class="comment-time">{{ formatDate(comment.date) }}</p>
      </div>
    </div>
    <div class="replies" v-if="comment.thread.items && comment.thread.items.length">
      <Comment v-for="reply in comment.thread.items" :key="reply.id" :comment="reply" />
    </div>
  </div>
</template>

<script>
export default {
  name: 'Comment',
  props: {
    comment: {
      type: Object,
      required: true
    }
  },
  methods: {
    replyToComment() {
      this.$emit('reply-to-comment', this.comment.id);
    },
    formatDate(date) {
      const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
      return new Date(date * 1000).toLocaleDateString(undefined, options);
    }
  }
}
</script>

<style scoped>
.comment {
  padding: 8px;
  margin-bottom: 8px;
}

.comment-header {
  display: flex;
  align-items: center;
}

.avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  margin-right: 8px;
}

.user-info {
  display: flex;
  flex-direction: column;
  border-bottom: 1px solid #ccc;
}

.username {
  margin: 0;
  font-size: 1em;
}

.comment-time {
  margin: 0;
  color: #888;
}

.comment-content {
  margin: 0;
  flex-shrink: 1;
}

.comment-actions {
  margin-top: 8px;
}

.replies {
  margin-top: 8px;
  padding-left: 16px;
  border-left: 2px solid #ccc;
}
</style>