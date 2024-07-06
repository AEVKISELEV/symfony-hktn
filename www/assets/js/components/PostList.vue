<template>
	<div>
		<component :is="currentComponent" :groupId="selectedGroup" @selectPost="selectPost" />
	</div>
</template>

<script>

import Empty from "./posts/Empty.vue";
import List from "./posts/List.vue";

export default {
	name: 'PostList',
	components: {List, Empty},
	props: {
		selectedGroup: Number,
	},
	methods: {
		selectPost(id) {

      console.log('PostList');
      console.log(id);
			this.$emit('selectPost', id);
		}
	},
	computed: {
		currentComponent() {
			return this.selectedGroup ? 'List' : 'Empty';
		}
	},
};
</script>