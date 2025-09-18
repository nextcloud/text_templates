<template>
	<div id="text-templates-content">
		<p> {{ featureInfo }}</p>
		<NcButton type="primary"
			@click="onAddTemplate">
			<template #icon>
				<PlusIcon />
			</template>
			{{ t('text_templates', 'Add a template') }}
		</NcButton>
		<div class="template-list">
			<EditableTextTemplate v-if="newTemplate"
				ref="new-template"
				:template="newTemplate"
				:submit-button-label="t('text_templates', 'Create')"
				:cancel-button-label="t('text_templates', 'Cancel')"
				:loading="creating"
				class="template"
				@cancel="newTemplate = null"
				@submit="onValidateNewTemplate">
				<template #submit-icon>
					<StickerPlusOutlineIcon />
				</template>
			</EditableTextTemplate>
			<EditableTextTemplate v-for="t in editableTemplates"
				:key="t.id"
				:template="t"
				:loading="loadingTemplateId === t.id"
				class="template"
				@delete="onDeleteTemplate(t)"
				@submit="onEditTemplate" />
		</div>
		<div v-if="!admin" class="link-to-admin-settings">
			<h2>
				{{ t('text_templates', 'Admin-defined templates') }}
			</h2>
			<p> {{ t('text_templates', 'Admins can create and manage text templates for everyone.') }}</p>
			<div class="template-list">
				<DisplayTextTemplate v-for="t in adminTemplates"
					:key="t.id"
					:template="t"
					class="template" />
			</div>
			<a v-if="isAdminUser"
				:href="adminSettingsUrl"
				target="_blank">
				<NcButton alignment="start-reverse">
					{{ t('text_templates', 'Edit admin templates') }}
					<template #icon>
						<ArrowTopRightIcon />
					</template>
				</NcButton>
			</a>
		</div>
	</div>
</template>

<script>
import StickerPlusOutlineIcon from 'vue-material-design-icons/StickerPlusOutline.vue'
import PlusIcon from 'vue-material-design-icons/Plus.vue'
import ArrowTopRightIcon from 'vue-material-design-icons/ArrowTopRight.vue'

import EditableTextTemplate from './EditableTextTemplate.vue'
import DisplayTextTemplate from './DisplayTextTemplate.vue'

import NcButton from '@nextcloud/vue/dist/Components/NcButton.js'

import { loadState } from '@nextcloud/initial-state'
import { generateOcsUrl, generateUrl } from '@nextcloud/router'
import axios from '@nextcloud/axios'
import { showSuccess, showError } from '@nextcloud/dialogs'
import { getCurrentUser } from '@nextcloud/auth'

export default {
	name: 'TemplateManagement',

	components: {
		EditableTextTemplate,
		DisplayTextTemplate,
		NcButton,
		StickerPlusOutlineIcon,
		PlusIcon,
		ArrowTopRightIcon,
	},

	props: {
		admin: {
			type: Boolean,
			required: true,
		},
	},

	data() {
		return {
			adminSettingsUrl: generateUrl('/settings/admin#text-templates_prefs'),
			state: { templates: [] },
			loadingTemplateId: null,
			creating: false,
			newTemplate: null,
		}
	},

	computed: {
		isAdminUser() {
			const user = getCurrentUser()
			return user && user.isAdmin
		},
		editableTemplates() {
			return this.admin
				? this.state.templates
				: this.state.templates.filter(t => t.user_id !== null)
		},
		adminTemplates() {
			return this.admin
				? []
				: this.state.templates.filter(t => t.user_id === null)
		},

		featureInfo() {
			return this.admin
				? this.t('text_templates', 'Text templates are snippets of text that users can quickly insert when they\'re using Text, Collectives, Talk, Mail or any place where the smart picker is available. Here you can create templates that will be available to all users on this instance.')
				: this.t('text_templates', 'Text templates are snippets of text that you can quickly insert when you\'re using Text, Collectives, Talk, Mail or any place where the smart picker is available.')
		},
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
				this.state.templates.unshift(response.data.ocs.data)
				this.newTemplate = null
			}).catch((error) => {
				showError(
					t('text_templates', 'Failed to create template {name}', { name: template.name })
					+ ': ' + (error.response?.data?.error ?? ''),
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
					this.state.templates[i].name = template.name
					this.state.templates[i].content = template.content
				}
			}).catch((error) => {
				showError(
					t('text_templates', 'Failed to save template {name}', { name: template.name })
					+ ': ' + (error.response?.data?.error ?? ''),
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
					+ ': ' + (error.response?.data?.error ?? ''),
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
	.template-list {
		display: flex;
		flex-wrap: wrap;
		gap: calc(var(--default-grid-baseline) * 3);
		margin: calc(var(--default-grid-baseline) * 3) 0;
	}
}

.link-to-admin-settings {
		margin-top: calc(var(--default-grid-baseline) * 6);
		border-top: 1px solid var(--color-border);
		padding-top: calc(var(--default-grid-baseline) * 3);
	}
</style>
