<template>
	<!-- stop enter pressed event otherwise we get new lines in the picker result... -->
	<div class="template"
		@keydown.enter.prevent.stop="$emit('click')">
		<label>
			{{ template.name }}
		</label>
		<textarea
			:value="template.content"
			:readonly="true"
			tabindex="-1"
			class="content" />
	</div>
</template>

<script>
export default {
	name: 'TemplatePickerEntry',
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

	&:focus,
	&:focus-visible {
		border: solid 4px var(--color-primary);
		outline: none;
		box-shadow: none;
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
