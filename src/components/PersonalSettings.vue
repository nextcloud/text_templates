<template>
	<div id="text-templates_prefs" class="section">
		<h2>
			<TextTemplatesIcon class="icon" />
			{{ t('text_templates', 'Text templates') }}
		</h2>
		<div id="text-templates-content">
			nothing yet
		</div>
	</div>
</template>

<script>
import TextTemplatesIcon from './icons/TextTemplatesIcon.vue'

import { loadState } from '@nextcloud/initial-state'
import { generateUrl } from '@nextcloud/router'
import axios from '@nextcloud/axios'
import { showSuccess, showError } from '@nextcloud/dialogs'

export default {
	name: 'PersonalSettings',

	components: {
		TextTemplatesIcon,
	},

	props: [],

	data() {
		return {
			state: loadState('text_templates', 'user-config'),
			loading: false,
		}
	},

	computed: {
	},

	watch: {
	},

	mounted() {
	},

	methods: {
		editTemplate(values) {
			const req = {
				values,
			}
			const url = generateUrl('/apps/text_templates/templates/{id}', { id: values.id })
			axios.put(url, req).then((response) => {
				showSuccess(t('text_templates', 'Template {name} saved', { name: values.name }))
			}).catch((error) => {
				showError(
					t('text_templates', 'Failed to save template {name}', { name: values.name })
					+ ': ' + (error.response?.data?.error ?? '')
				)
				console.error(error)
			}).then(() => {
				this.loading = false
			})
		},
	},
}
</script>

<style scoped lang="scss">
#text-templates_prefs {
	#text-templates-content {
		margin-left: 40px;
	}
	h2,
	.line,
	.settings-hint {
		display: flex;
		align-items: center;
		margin-top: 12px;
		.icon {
			margin-right: 4px;
		}
	}

	h2 .icon {
		margin-right: 8px;
	}

	.line {
		> label {
			width: 300px;
			display: flex;
			align-items: center;
		}
		> input {
			width: 300px;
		}
	}
}
</style>
