<template>
	<div class="template-picker-content">
		<h2>
			{{ t('text_templates', 'Text templates') }}
		</h2>
		<div class="input-wrapper">
			<NcTextField
				ref="template-search-input"
				:value.sync="searchQuery"
				:show-trailing-button="searchQuery !== ''"
				:label="inputPlaceholder"
				@trailing-button-click="onClear">
				<template #trailing-button-icon>
					<CloseIcon :size="16" />
				</template>
				<MagnifyIcon :size="16" />
			</NcTextField>
		</div>
		<NcEmptyContent v-if="filteredTemplates.length === 0"
			:title="t('text_templates', 'No template found')">
			<template #icon>
				<TextTemplatesIcon />
			</template>
			<template #action>
				<a :href="settingsUrl"
					target="_blank">
					<NcButton>
						{{ t('text_templates', 'Add templates') }}
						<template #icon>
							<PlusIcon />
						</template>
					</NcButton>
				</a>
			</template>
		</NcEmptyContent>
		<div class="templates">
			<TemplatePickerEntry v-for="t in filteredTemplates"
				:key="t.id"
				:template="t"
				:tabindex="0"
				@click="onSubmit(t)"
				@click.native="onSubmit(t)" />
			<a v-if="searchQuery === '' && filteredTemplates.length > 0"
				class="add-result"
				:href="settingsUrl"
				target="_blank">
				<NcButton>
					{{ t('text_templates', 'Add templates') }}
					<template #icon>
						<PlusIcon />
					</template>
				</NcButton>
			</a>
		</div>
	</div>
</template>

<script>
import PlusIcon from 'vue-material-design-icons/Plus.vue'
import MagnifyIcon from 'vue-material-design-icons/Magnify.vue'
import CloseIcon from 'vue-material-design-icons/Close.vue'

import TextTemplatesIcon from '../components/icons/TextTemplatesIcon.vue'

import TemplatePickerEntry from '../components/TemplatePickerEntry.vue'

import NcEmptyContent from '@nextcloud/vue/dist/Components/NcEmptyContent.js'
import NcTextField from '@nextcloud/vue/dist/Components/NcTextField.js'
import NcButton from '@nextcloud/vue/dist/Components/NcButton.js'

import axios from '@nextcloud/axios'
import { generateOcsUrl, generateUrl } from '@nextcloud/router'
import { showError } from '@nextcloud/dialogs'

export default {
	name: 'TemplateCustomPickerElement',

	components: {
		TemplatePickerEntry,
		NcEmptyContent,
		NcTextField,
		NcButton,
		MagnifyIcon,
		CloseIcon,
		PlusIcon,
		TextTemplatesIcon,
	},

	props: {
		providerId: {
			type: String,
			required: true,
		},
		accessible: {
			type: Boolean,
			default: false,
		},
	},

	data() {
		return {
			settingsUrl: generateUrl('/settings/user/additional#text-templates_prefs'),
			searchQuery: '',
			searching: false,
			inputPlaceholder: t('text_templates', 'Search templates'),
			templates: [],
		}
	},

	computed: {
		filteredTemplates() {
			const re = new RegExp(this.searchQuery, 'i')
			return this.templates.filter(t => {
				return t.name.match(re) || t.content.match(re)
			})
		},
	},

	watch: {
	},

	mounted() {
		this.focusOnInput()
		this.getTemplates()
	},

	beforeDestroy() {
	},

	methods: {
		getTemplates() {
			const url = generateOcsUrl('apps/text_templates/api/v1/templates')
			axios.get(url).then((response) => {
				this.templates = response.data.ocs.data
			}).catch((error) => {
				showError(
					t('text_templates', 'Failed to get templates')
					+ ': ' + (error.response?.data?.error ?? '')
				)
				console.error(error)
			})
		},
		focusOnInput() {
			setTimeout(() => {
				this.$refs['template-search-input'].$el.getElementsByTagName('input')[0]?.focus()
			}, 300)
		},
		onSubmit(template) {
			this.$emit('submit', template.content.trim())
		},
		onClear() {
			this.searchQuery = ''
		},
	},
}
</script>

<style scoped lang="scss">
.template-picker-content {
	width: 100%;
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
	overflow-y: auto;
	max-height: 800px;
	padding: 12px 16px 0 16px;

	h2 {
		display: flex;
		align-items: center;
	}

	.input-wrapper {
		display: flex;
		align-items: center;
		width: 100%;
		input {
			flex-grow: 1;
		}
		.input-loading {
			padding: 0 4px;
		}
	}

	.empty-content-wrapper {
		display: flex;
		align-items: center;
		height: 5000px;

		.empty-content-img {
			width: 100px;
		}
	}

	.templates {
		width: 98%;
		// ugly but...makes it take all available space
		height: 5000px;
		//flex-grow: 1;
		display: grid;
		grid-auto-rows: 160px;
		grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
		grid-gap: 8px;
		overflow-y: scroll;
		scrollbar-width: auto;
		scrollbar-color: var(--color-primary);
		margin-top: 12px;
		padding-right: 16px;
		padding-bottom: 16px;

		.add-result {
			display: flex;
			align-items: center;
			justify-content: center;
		}
	}
}
</style>
