<template>
	<!-- stop enter pressed event otherwise we get new lines in the picker result... -->
	<div class="template"
		@keydown.enter.prevent.stop="$emit('click')">
		<label class="name">
			{{ template.name }}
		</label>
		<pre class="content" :title="template.content">{{ template.content }}</pre>
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
	border: solid 2px var(--color-border);
	border-radius: var(--border-radius-large);
	padding: 12px 20px;
	cursor: pointer;

	> * {
		cursor: pointer;
	}

	&:hover {
		border: solid 2px var(--color-primary);
	}

	&:focus,
	&:focus-visible {
		border: solid 4px var(--color-primary);
		outline: none;
		box-shadow: none;
	}

	.name {
		font-weight: bold;
	}

	.content {
		white-space: break-spaces;
		overflow: hidden;
	}
}
</style>
