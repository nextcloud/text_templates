<template>
	<div class="template">
		<label>
			{{ template.name }}
		</label>
		<textarea
			:value="template.content"
			:readonly="true"
			class="content" />
	</div>
</template>

<script>
export default {
	name: 'TemplateEntry',
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
	display: flex;
	flex-direction: column;
	border: solid 2px var(--color-border-maxcontrast);
	border-radius: var(--border-radius-large);
	padding: 12px 20px;
	cursor: pointer;

	&:hover {
		border: solid 2px var(--color-primary);
	}

	label {
		font-weight: bold;
	}

	textarea {
		flex-grow: 1;
		width: 100%;
		resize: none;
		border: 0;
		padding: 0;
	}
}
</style>
