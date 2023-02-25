<template>
	<div id="text-templates-content">
		<TextTemplate v-for="t in state.templates"
			:key="t.id"
			:template="t"
			:loading="loadingTemplateId === t.id"
			@delete="onDeleteTemplate(t)"
			@submit="onEditTemplate" />
		<NcButton @click="onAddTemplate">
			{{ t('text_templates', 'Add a template') }}
		</NcButton>
		<TextTemplate v-if="newTemplate"
			:template="newTemplate"
			:submit-button-label="t('text_templates', 'Create template')"
			:loading="creating"
			@cancel="newTemplate = null"
			@submit="onValidateNewTemplate" />
	</div>
</template>

<script>
import TextTemplate from './TextTemplate.vue'

import NcButton from '@nextcloud/vue/dist/Components/NcButton.js'

import { loadState } from '@nextcloud/initial-state'
import { generateOcsUrl } from '@nextcloud/router'
import axios from '@nextcloud/axios'
import { showSuccess, showError } from '@nextcloud/dialogs'

export default {
	name: 'TemplateManagement',

	components: {
		TextTemplate,
		NcButton,
	},

	props: {
		admin: {
			type: Boolean,
			required: true,
		},
	},

	data() {
		return {
			state: [],
			loadingTemplateId: null,
			creating: false,
			newTemplate: null,
		}
	},

	computed: {
	},

	watch: {
	},

	mounted() {
		if (this.admin) {
			this.state = loadState('text_templates', 'admin-config')
		} else {
			this.state = loadState('text_templates', 'user-config')
		}
	},

	methods: {
		onAddTemplate() {
			this.newTemplate = {
				id: -1,
				name: '',
				content: '',
			}
		},
		onValidateNewTemplate(template) {
			this.creating = true
			const req = {
				name: template.name,
				content: template.content,
			}
			const url = this.admin
				? generateOcsUrl('apps/text_templates/api/v1/admin/templates')
				: generateOcsUrl('apps/text_templates/api/v1/templates')
			axios.post(url, req).then((response) => {
				showSuccess(t('text_templates', 'Template {name} created', { name: template.name }))
				this.state.templates.push(response.data.ocs.data)
				this.newTemplate = null
			}).catch((error) => {
				showError(
					t('text_templates', 'Failed to create template {name}', { name: template.name })
					+ ': ' + (error.response?.data?.error ?? '')
				)
				console.error(error)
			}).then(() => {
				this.creating = false
			})
		},
		onEditTemplate(template) {
			this.loadingTemplateId = template.id
			const req = {
				name: template.name,
				content: template.content,
			}
			const url = this.admin
				? generateOcsUrl('apps/text_templates/api/v1/admin/templates/{id}', { id: template.id })
				: generateOcsUrl('apps/text_templates/api/v1/templates/{id}', { id: template.id })
			axios.put(url, req).then((response) => {
				showSuccess(t('text_templates', 'Template {name} saved', { name: template.name }))
				const i = this.state.templates.findIndex(t => t.id === template.id)
				if (i !== -1) {
					this.state.templates[i] = template
				}
			}).catch((error) => {
				showError(
					t('text_templates', 'Failed to save template {name}', { name: template.name })
					+ ': ' + (error.response?.data?.error ?? '')
				)
				console.error(error)
			}).then(() => {
				this.loadingTemplateId = null
			})
		},
		onDeleteTemplate(template) {
			this.loadingTemplateId = template.id
			const url = this.admin
				? generateOcsUrl('apps/text_templates/api/v1/admin/templates/{id}', { id: template.id })
				: generateOcsUrl('apps/text_templates/api/v1/templates/{id}', { id: template.id })
			axios.delete(url).then((response) => {
				showSuccess(t('text_templates', 'Template {name} deleted', { name: template.name }))
				const i = this.state.templates.findIndex(t => t.id === template.id)
				if (i !== -1) {
					this.state.templates.splice(i, 1)
				}
			}).catch((error) => {
				showError(
					t('text_templates', 'Failed to delete template {name}', { name: template.name })
					+ ': ' + (error.response?.data?.error ?? '')
				)
				console.error(error)
			}).then(() => {
				this.loadingTemplateId = null
			})
		},
	},
}
</script>

<style scoped lang="scss">
#text-templates-content {
	margin-left: 40px;
}
</style>
