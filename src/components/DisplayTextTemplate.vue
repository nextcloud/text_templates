<template>
	<div class="template">
		<h3 class="name">
			{{ template.name }}
		</h3>
		<pre class="content">{{ template.content }}</pre>
	</div>
</template>

<script>
export default {
	name: 'DisplayTextTemplate',
	components: {
	},
	props: {
		template: {
			type: Object,
			required: true,
		},
	},
	data() {
		return {
		}
	},
	computed: {
	},
	watch: {
	},
	mounted() {
	},
	methods: {
	},
}
</script>

<style scoped lang="scss">
.template {
	width: 340px;
	height: 285px;
	display: flex;
	flex-direction: column;
	border: 2px solid var(--color-border);
	border-radius: var(--border-radius-large);
	padding: 12px 20px;

	.name {
		font-weight: bold;
		margin: 0;
	}

	.content {
		white-space: break-spaces;
		margin: 4px 0 0 0;
		overflow-y: scroll;
		scrollbar-width: auto;
	}
}
</style>
