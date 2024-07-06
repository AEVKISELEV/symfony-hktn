<template>
  <header>
    <!-- Add your header content here -->
  </header>
  <div class="group-page-wrapper">
    <section class="group-page group-list">
			<component :is="leftComponent" :postId="selectedPost" @selectGroup="selectGroup"/>
    </section>
    <section class="group-page comment-list">
			<PostList @selectPost="selectPost" :selectedGroup="selectedGroup" />
		</section>
  </div>
</template>

<script>
  import GroupList from './groups/GroupList.vue';
	import PostList from "./PostList.vue";
	import PostPage from "./post/PostPage.vue";

	export default {
		name: 'Main',
		components: {PostList, GroupList, PostPage},
		data() {
			return {
				selectedPost: null,
				selectedGroup: null,
			};
		},
		methods: {
			selectGroup(selectedGroup) {
				this.selectedGroup = selectedGroup;
			},
			selectPost(selectedPost) {
				this.selectedPost = selectedPost;
			}
		},
		computed: {
			leftComponent() {
				return this.selectedPost ? 'PostPage' : 'GroupList';
			},
		},
	};
</script>

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
