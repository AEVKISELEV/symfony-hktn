<template>
	<div>
		<component :is="currentComponent" :groupId="selectedGroup"/>
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
	computed: {
		currentComponent() {
			return this.selectedGroup ? 'List' : 'Empty';
		}
	},
};
</script>