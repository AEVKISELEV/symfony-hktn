<template>
	<div>
		<component :is="currentComponent" :groupId="selectedPost"/>
	</div>
</template>

<script>

import Empty from "./posts/Empty.vue";
import List from "./posts/List.vue";

export default {
	name: 'PostList',
	components: {List, Empty},
	props: {
		selectedPost: Number,
	},
	computed: {
		currentComponent() {
			return this.selectedPost ? 'List' : 'Empty';
		}
	},
};
</script>