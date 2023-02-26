<template>
	<div class="template-picker-content">
		<h2>
			{{ t('text_templates', 'Text template picker') }}
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
		<div class="templates">
			<TemplateEntry v-for="t in filteredTemplates"
				:key="t.id"
				:template="t"
				@click.native="onSubmit(t)" />
		</div>
	</div>
</template>

<script>
import MagnifyIcon from 'vue-material-design-icons/Magnify.vue'
import CloseIcon from 'vue-material-design-icons/Close.vue'

import TemplateEntry from '../components/TemplateEntry.vue'

// import NcLoadingIcon from '@nextcloud/vue/dist/Components/NcLoadingIcon.js'
// import NcEmptyContent from '@nextcloud/vue/dist/Components/NcEmptyContent.js'
import NcTextField from '@nextcloud/vue/dist/Components/NcTextField.js'

import axios from '@nextcloud/axios'
import { generateOcsUrl } from '@nextcloud/router'
import { showError } from '@nextcloud/dialogs'

export default {
	name: 'TemplateCustomPickerElement',

	components: {
		// NcLoadingIcon,
		// NcEmptyContent,
		NcTextField,
		MagnifyIcon,
		CloseIcon,
		TemplateEntry,
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
			searchQuery: '',
			searching: false,
			inputPlaceholder: t('text_templates', 'Search templates'),
			templates: [],
		}
	},

	computed: {
		filteredTemplates() {
			return this.templates.filter(t => {
				return t.name.match(this.searchQuery)
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
			this.$emit('submit', template.content)
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
	//padding: 16px;
	overflow-y: auto;
	max-height: 800px;

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
		grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
		grid-gap: 8px;
		overflow-y: scroll;
		scrollbar-width: auto;
		scrollbar-color: var(--color-primary);
		margin-top: 12px;
		padding-right: 16px;
	}
}
</style>
