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
	&:hover {
		border: solid 2px var(--color-border-maxcontrast);
	}
}
</style>
