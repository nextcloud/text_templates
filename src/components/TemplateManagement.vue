<template>
	<div id="text-templates-content">
		<TextTemplate v-for="t in state.templates"
			:key="t.id"
			:template="t"
			:disabled="!admin && t.user_id === null"
			:loading="loadingTemplateId === t.id"
			class="template"
			@delete="onDeleteTemplate(t)"
			@submit="onEditTemplate" />
		<div class="add-wrapper">
			<NcButton @click="onAddTemplate">
				<template #icon>
					<PlusIcon />
				</template>
				{{ t('text_templates', 'Add a template') }}
			</NcButton>
		</div>
		<TextTemplate v-if="newTemplate"
			ref="new-template"
			:template="newTemplate"
			:submit-button-label="t('text_templates', 'Create template')"
			:loading="creating"
			class="template"
			@cancel="newTemplate = null"
			@submit="onValidateNewTemplate">
			<template #submit-icon>
				<StickerPlusOutlineIcon />
			</template>
		</TextTemplate>
	</div>
</template>

<script>
import StickerPlusOutlineIcon from 'vue-material-design-icons/StickerPlusOutline.vue'
import PlusIcon from 'vue-material-design-icons/Plus.vue'

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
		StickerPlusOutlineIcon,
		PlusIcon,
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
			this.$nextTick(() => {
				this.$refs['new-template']?.focus()
			})
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
	display: flex;
	flex-wrap: wrap;

	.add-wrapper {
		display: flex;
		align-items: center;
	}

	.template {
		margin: 12px;
	}
}
</style>
