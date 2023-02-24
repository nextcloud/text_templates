<template>
	<div class="template">
		<NcTextField class="name"
			:value.sync="name"
			:label="namePlaceholder"
			:show-trailing-button="!!name"
			@keydown.enter="onSubmit"
			@trailing-button-click="name = ''">
			<NcLoadingIcon v-if="loading" :size="16" />
			<TextTemplatesIcon v-else :size="16" />
		</NcTextField>
		<textarea
			v-model="content"
			class="content"
			:placeholder="contentPlaceholder" />
		<NcButton
			:disabled="!(name && content)"
			@click="onSubmit">
			{{ submitButtonLabel }}
		</NcButton>
		<NcButton @click="onCancel">
			{{ t('text_templates', 'Cancel') }}
		</NcButton>
		<NcButton v-if="template.id !== -1"
			@click="$emit('delete')">
			{{ t('text_templates', 'Delete') }}
		</NcButton>
	</div>
</template>

<script>
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
	},
	watch: {
	},
	mounted() {
	},
	methods: {
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
// nothing
</style>
