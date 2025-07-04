<template>
	<div class="template" :class="{ disabled }">
		<NcTextField ref="name"
			input-class="name-input"
			:value.sync="name"
			:disabled="disabled"
			:label="namePlaceholder"
			:show-trailing-button="!!name && !disabled"
			@keydown.enter="onSubmit"
			@trailing-button-click="name = ''" />
		<textarea
			v-model="content"
			:disabled="disabled"
			class="content"
			:placeholder="contentPlaceholder" />
		<div class="footer">
			<NcButton v-if="!disabled && template.id !== -1"
				type="tertiary"
				:title="t('text_templates', 'Delete')"
				@click="$emit('delete')">
				<template #icon>
					<NcLoadingIcon v-if="loading" />
					<DeleteOutlineIcon v-else />
				</template>
			</NcButton>
			<div class="spacer" />
			<NcButton v-if="showCancelButton"
				type="tertiary"
				:title="cancelButtonLabel"
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
				{{ submitButtonLabel }}
				<template #icon>
					<slot name="submit-icon">
						<NcLoadingIcon v-if="loading" />
						<ArrowRightIcon v-else />
					</slot>
				</template>
			</NcButton>
		</div>
	</div>
</template>

<script>
import DeleteOutlineIcon from 'vue-material-design-icons/Delete.vue'
import UndoIcon from 'vue-material-design-icons/Undo.vue'
import ArrowRightIcon from 'vue-material-design-icons/ArrowRight.vue'

import NcButton from '@nextcloud/vue/dist/Components/NcButton.js'
import NcTextField from '@nextcloud/vue/dist/Components/NcTextField.js'
import NcLoadingIcon from '@nextcloud/vue/dist/Components/NcLoadingIcon.js'

export default {
	name: 'EditableTextTemplate',
	components: {
		NcButton,
		NcTextField,
		NcLoadingIcon,
		DeleteOutlineIcon,
		UndoIcon,
		ArrowRightIcon,
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
		cancelButtonLabel: {
			type: String,
			default: t('text_templates', 'Undo changes'),
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
	width: 340px;
	height: 285px;
	display: flex;
	flex-direction: column;
	border: 2px solid var(--color-border);
	border-radius: var(--border-radius-large);
	padding: 20px;

	&.disabled {
		.content,
		::v-deep .name-input {
			border: 0;
		}
	}

	::v-deep .name-input {
		font-weight: bold !important;
	}

	.content {
		width: 100%;
		flex-grow: 1;
		margin: 4px 0 0 0;
		resize: none;
	}

	.spacer {
		flex-grow: 1;
	}

	.footer {
		display: flex;
		margin-top: 8px;
	}
}
</style>
