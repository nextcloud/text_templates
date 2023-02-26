<template>
	<div class="template">
		<NcTextField ref="name"
			class="name"
			:value.sync="name"
			:disabled="disabled"
			:label="namePlaceholder"
			:show-trailing-button="!!name && !disabled"
			@keydown.enter="onSubmit"
			@trailing-button-click="name = ''">
			<NcLoadingIcon v-if="loading" :size="16" />
			<TextTemplatesIcon v-else :size="16" />
		</NcTextField>
		<textarea
			v-model="content"
			:disabled="disabled"
			class="content"
			:placeholder="contentPlaceholder" />
		<div class="footer">
			<NcButton v-if="!disabled && template.id !== -1"
				:title="t('text_templates', 'Delete')"
				@click="$emit('delete')">
				<template #icon>
					<DeleteIcon />
				</template>
			</NcButton>
			<div class="spacer" />
			<NcButton v-if="showCancelButton"
				:title="t('text_templates', 'Cancel')"
				@click="onCancel">
				<template #icon>
					<UndoIcon />
				</template>
			</NcButton>
			<NcButton v-if="showSaveButton"
				type="primary"
				:title="submitButtonLabel"
				:disabled="!(name && content)"
				@click="onSubmit">
				<template #icon>
					<slot name="submit-icon">
						<ContentSaveIcon />
					</slot>
				</template>
			</NcButton>
		</div>
	</div>
</template>

<script>
import DeleteIcon from 'vue-material-design-icons/Delete.vue'
import UndoIcon from 'vue-material-design-icons/Undo.vue'
import ContentSaveIcon from 'vue-material-design-icons/ContentSave.vue'

import TextTemplatesIcon from './icons/TextTemplatesIcon.vue'

import NcButton from '@nextcloud/vue/dist/Components/NcButton.js'
import NcTextField from '@nextcloud/vue/dist/Components/NcTextField.js'
import NcLoadingIcon from '@nextcloud/vue/dist/Components/NcLoadingIcon.js'

export default {
	name: 'TextTemplate',
	components: {
		TextTemplatesIcon,
		NcButton,
		NcTextField,
		NcLoadingIcon,
		DeleteIcon,
		UndoIcon,
		ContentSaveIcon,
	},
	props: {
		template: {
			type: Object,
			required: true,
		},
		submitButtonLabel: {
			type: String,
			default: t('text_templates', 'Save'),
		},
		loading: {
			type: Boolean,
			default: false,
		},
		disabled: {
			type: Boolean,
			default: false,
		},
	},
	data() {
		return {
			name: this.template.name,
			content: this.template.content,
			namePlaceholder: t('text_templates', 'Template name'),
			contentPlaceholder: t('text_templates', 'Template content'),
		}
	},
	computed: {
		showSaveButton() {
			return !this.disabled && (this.name !== this.template.name || this.content !== this.template.content)
		},
		showCancelButton() {
			return this.showSaveButton || this.template.id === -1
		},
	},
	watch: {
	},
	mounted() {
	},
	methods: {
		focus() {
			this.$refs.name.$el.getElementsByTagName('input')[0]?.focus()
		},
		onCancel() {
			this.$emit('cancel')
			this.name = this.template.name
			this.content = this.template.content
		},
		onSubmit() {
			const submittedTemplate = {
				id: this.template.id,
				name: this.name,
				content: this.content,
			}
			this.$emit('submit', submittedTemplate)
		},
	},
}
</script>

<style scoped lang="scss">
.template {
	//border: solid 2px var(--color-border-maxcontrast);
	box-shadow: 0 0 10px var(--color-box-shadow);
	border-radius: var(--border-radius-large);
	padding: 12px 20px;

	.content {
		width: 300px;
		height: 100px;
		margin: 4px 0 0 0;
	}

	.spacer {
		flex-grow: 1;
	}

	.footer {
		display: flex;
	}
}
</style>
