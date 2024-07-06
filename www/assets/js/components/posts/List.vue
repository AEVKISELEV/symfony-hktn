<template>
	<div id="post-list" class="post-list-block">
		<div v-for:="post in posts" class="post-item">
			<div class="post-item-left">
			</div>
			<div class="post-item-right">
				<div>
					<div class="post-title">{{ post.title }}</div>
				</div>
				<div class="post-item-row">
					<div class="post-item-likes">
						<svg width="16" height="14" viewBox="0 0 16 14" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path fill-rule="evenodd" clip-rule="evenodd" d="M2.3061 1.02537C1.02363 1.70846 0.0238606 2.9938 0.000840967 4.39682C-0.00423117 4.71303 0.0144967 5.02823 0.0340049 5.34444C0.0566344 5.71746 0.136174 5.94688 0.316039 6.48648C0.495904 7.02607 1.01192 7.89157 1.49143 8.51377C1.84102 8.9661 2.23626 9.37697 2.65217 9.77096C3.079 10.175 5.35993 11.932 6.81041 13.0413C7.52903 13.5908 8.52463 13.5857 9.23807 13.0294C10.7268 11.8687 13.1047 10.0034 13.4784 9.64253C13.9178 9.21528 14.3325 8.76705 14.686 8.26766C15.2096 7.5247 15.7821 6.70346 15.9117 5.81058C15.9577 5.49129 15.9827 5.17098 15.9944 4.84914C16.0123 4.3784 15.9917 3.90919 15.8672 3.45124C15.5933 2.44733 14.7566 1.66344 13.855 1.11901C13.352 0.815074 12.8035 0.641104 12.2174 0.577656C11.8491 0.537745 11.4808 0.540815 11.1144 0.598123C10.4059 0.709157 9.77267 0.991091 9.2081 1.42602C8.73132 1.79084 8.34389 2.23447 8.02785 2.74001C8.01436 2.75992 7.98592 2.76046 7.97229 2.74065C7.9311 2.6808 7.89488 2.62367 7.85657 2.56757C7.39891 1.89727 6.82147 1.35592 6.08757 0.98444C5.64473 0.760325 5.17458 0.622172 4.67946 0.571516C4.53862 0.557189 4.39816 0.550537 4.2577 0.550537C4.07198 0.550537 3.88665 0.562306 3.70132 0.586355C3.20855 0.649291 2.7423 0.793072 2.3061 1.02537Z" fill="#EDEEF0"/>
						</svg>

						{{ post.likesAmount }}
					</div>
					<div class="post-item-comments">
						<svg width="16" height="15" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path fill-rule="evenodd" clip-rule="evenodd" d="M1.26537 0.260254H13.8726C14.5461 0.260254 15.092 0.806228 15.092 1.47972V9.90613C15.092 10.5796 14.5461 11.1256 13.8726 11.1256H7.56896L4.07231 14.6218C3.82032 14.8738 3.38948 14.6953 3.38948 14.339V11.1256H1.26537C0.591873 11.1256 0.0458984 10.5796 0.0458984 9.90613V1.47972C0.0458984 0.806228 0.591873 0.260254 1.26537 0.260254Z" fill="#EDEEF0"/>
						</svg>

						{{ post.commentsAmount }}
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import axios from 'axios';

export default {
	name: 'List',
	props: {
		groupId: Number,
	},
	data() {
		return {
			message: '',
			posts: [],
		};
	},
	mounted() {
		axios.get('/api/v1/posts/' + this.groupId)
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
.post-list-block {
	/* Frame 51 */

	/* Auto layout */
	display: flex;
	flex-direction: column;
	align-items: flex-start;
	padding: 0px;
	gap: 20px;

	position: relative;
	width: 310px;
	height: 700px;
	left: 20px;
	top: 20px;
}

.post-item {
	display: flex;
	flex-direction: column;
	align-items: flex-start;
	padding: 0px;
	gap: 20px;
	width: 310px;
	height: 700px;
	left: 20px;
	top: 20px;
}


.post-item.selected-item {

}

.post-item:hover {

}

.post-item-row {
	/* Frame 51 */

	/* Auto layout */
	display: flex;
	flex-direction: row;
	align-items: flex-start;
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