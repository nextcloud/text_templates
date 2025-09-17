<template>
	<div id="text-templates_prefs" class="section">
		<h2>
			<TextTemplatesIcon class="icon" />
			{{ t('text_templates', 'Text templates') }}
		</h2>
		<TemplateManagement :admin="true" />
	</div>
</template>

<script>
import TextTemplatesIcon from './icons/TextTemplatesIcon.vue'

import TemplateManagement from './TemplateManagement.vue'

export default {
	name: 'AdminSettings',

	components: {
		TextTemplatesIcon,
		TemplateManagement,
	},

	props: [],

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
#text-templates_prefs {
	h2 .icon {
		margin-right: 8px;
	}
}
</style>
