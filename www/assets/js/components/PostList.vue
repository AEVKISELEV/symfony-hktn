<template>
	<div v-for:="post in posts" id="post-list">
			<div class="post-item">
				<div class="post-item-left">
					<div class="post-title">{{ post.title }}</div>
				</div>
				<div class="post-item-right">
					<div class="post-item-likes">{{ post.likesAmount }}</div>
					<div class="post-item-comments">{{ post.commentsAmount }}</div>
				</div>
			</div>
	</div>
</template>

<script>
import axios from 'axios';

export default {
	name: 'PostList',
	data() {
		return {
			message: '',
			posts: [],
			date: ''
		};
	},
	mounted() {
		axios.get('/api/v1/posts/1')
				.then(response => {
					this.posts = response.data;
				})
				.catch(error => {
					console.error('There was an error!', error);
				});
	}
};
</script>

<style scoped>
.posts-block {
	width: 350px;
	height: 708px;
	top: 60px;
	left: 956px;
	gap: 0px;
	border-radius: 20px;
}

.post-item {
	height: 40px;
}


.post-item.selected-item {

}

.post-item:hover {

}

.post-item-title {
	font-family: Roboto;
	font-size: 16px;
	font-weight: 400;
	line-height: 18.75px;
	letter-spacing: 0.20000000298023224px;
	text-align: left;
}

.post-item-views {
	font-family: Roboto;
	font-size: 14px;
	font-weight: 400;
	line-height: 16.41px;
	text-align: left;

}

.post-item-likes {

}

.post-item-comments {

}

</style>