<template>
  <div className="comment-list">
    <Comment v-for="comment in comments" :key="comment.id" :comment="comment" @reply-to-comment="handleReplyToComment"/>
  </div>
</template>

<script>
import Comment from './Comment.vue';

export default {
name: 'CommentList',
components: {
Comment
},
props: {
comments: {
type: Array,
required: true
}
},
methods: {
handleReplyToComment(commentId) {
// Логика для обработки ответа на комментарий
console.log(`Reply to comment ${commentId}`);
}
}
}
</script>

<style scoped>
.comment-list {
margin-top: 16px;
}

.comment-list h3 {
margin-bottom: 8px;
}
</style>