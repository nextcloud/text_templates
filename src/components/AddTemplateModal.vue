<template>
	<div class="add-template-modal">
		<NcModal :key="newTemplate"
			:show="show"
			:container="null"
			@close="closeModal">
			<div class="add-template-modal-body">
				<h3>{{ t('text_templates', 'Add a new text template') }}</h3>
				<EditableTextTemplate
					ref="new-template"
					:template="newTemplate"
					:submit-button-label="t('text_templates', 'Create')"
					:cancel-button-label="t('text_templates', 'Cancel')"
					:loading="creating"
					class="template"
					@cancel="closeModal"
					@submit="onValidateNewTemplate">
					<template #submit-icon>
						<StickerPlusOutlineIcon />
					</template>
				</EditableTextTemplate>
			</div>
		</NcModal>
	</div>
</template>

<script>
import NcModal from '@nextcloud/vue/dist/Components/NcModal.js'
import EditableTextTemplate from './EditableTextTemplate.vue'
import StickerPlusOutlineIcon from 'vue-material-design-icons/StickerPlusOutline.vue'
import { generateOcsUrl } from '@nextcloud/router'
import axios from '@nextcloud/axios'
import { showSuccess, showError } from '@nextcloud/dialogs'

export default {
	name: 'AddTemplateModal',
	components: {
		NcModal,
		EditableTextTemplate,
		StickerPlusOutlineIcon,
	},
	props: {
		show: {
			type: Boolean,
			required: true,
			default: false,
		},
	},
	data() {
		return {
			newTemplate: {
				id: -1,
				name: '',
				content: '',
			},
			creating: false,
		}
	},
	mounted() {
	},
	methods: {
		closeModal() {
			this.$emit('update:show', false)
			console.warn(this.newTemplate)
		},
		onValidateNewTemplate(template) {
			this.creating = true
			const req = {
				name: template.name,
				content: template.content,
			}
			const url = generateOcsUrl('apps/text_templates/api/v1/templates')
			axios.post(url, req).then((response) => {
				showSuccess(t('text_templates', 'Template {name} created', { name: template.name }))
				this.newTemplate = {
					id: -1,
					name: '',
					content: '',
				}
				this.$emit('callback')
				this.closeModal()
				this.$forceUpdate()
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
	},
}
</script>
<style scoped>
.add-template-modal-body h3 {
	text-align: center;
}

.add-template-modal-body {
	display: flex;
	width: 100%;
	flex-direction: column;
	place-items: center;
	margin-bottom: 10%;
}
</style>
