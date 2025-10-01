import Choices from 'choices.js/public/assets/scripts/choices.js'

export default function selectChangerComponent({
    getResultsUsing,
    hasDynamicSearchResults,
    label,
    loadingMessage,
    noSearchResultsMessage,
    optionsLimit,
    placeholder,
    searchPrompt,
    searchingMessage,
    state,
    updateSelected,
}) {
    return {
        isSearching: false,
        select: null,
        selectedOptions: [],
        isStateBeingUpdated: false,
        state,
        _handlers: {},

        init: async function () {
            this.select = new Choices(this.$refs.input, {
                allowHTML: true,
                duplicateItemsAllowed: false,
                itemSelectText: '',
                loadingText: loadingMessage,
                maxItemCount: -1,
                noChoicesText: searchPrompt,
                noResultsText: noSearchResultsMessage,
                placeholderValue: placeholder,
                removeItemButton: false,
                renderChoiceLimit: optionsLimit,
                searchEnabled: true,
                searchFields: ['label'],
                searchPlaceholderValue: searchPrompt,
                searchResultLimit: optionsLimit,
                shouldSort: false,
                searchFloor: hasDynamicSearchResults ? 0 : 1,
                classNames: {
                    containerOuter: 'choices choices__select__changer',
                    containerInner: 'choices__inner',
                    input: 'choices__input',
                    listDropdown: 'choices__list--dropdown',
                    item: 'choices__item choices__select__changer__item',
                    list: 'choices__list',
                    placeholder: 'choices__placeholder',
                },
            })

            this.refreshPlaceholder()

            this._handlers.showDropdown = async () => {
                this.select.clearChoices()
                await this.select.setChoices([
                    {
                        label: loadingMessage,
                        value: '',
                        disabled: true,
                    },
                ])
                await this.refreshChoices()
            }
            this._handlers.change = async () => {
                this.refreshPlaceholder()
                let value = this.select.getValue(true) ?? null
                this.setChoices([
                    {
                        label: label,
                        value: state,
                        selected: true,
                    },
                ])
                return await updateSelected(value)
            }
            this._handlers.search = async (event) => {
                let search = event.detail.value?.trim()
                this.isSearching = true
                this.select.clearChoices()
                await this.select.setChoices([
                    {
                        label: [null, undefined, ''].includes(search)
                            ? loadingMessage
                            : searchingMessage,
                        value: '',
                        disabled: true,
                    },
                ])
            }
            this._handlers.debouncedSearch = Alpine?.debounce
                ? Alpine.debounce(async (event) => {
                    await this.refreshChoices({
                        search: event.detail.value?.trim(),
                    })
                    this.isSearching = false
                }, 250)
                : async (event) => {
                    await this.refreshChoices({
                        search: event.detail.value?.trim(),
                    })
                    this.isSearching = false
                }

            this.$refs.input.addEventListener('showDropdown', this._handlers.showDropdown)
            this.$refs.input.addEventListener('change', this._handlers.change)
            this.$refs.input.addEventListener('search', this._handlers.search)
            this.$refs.input.addEventListener('search', this._handlers.debouncedSearch)

            this._handlers.wireRefresh = (details) => {
                this.select.clearChoices()
                this.select.setChoices([
                    {
                        label: details.label,
                        value: state,
                        selected: true,
                    },
                ])
            }
            if (this.$wire && this.$wire.on) {
                this.$wire.on('record-switcher:refresh', this._handlers.wireRefresh)
            }
        },

        destroy: function () {
            if (this.select) {
                this.select.destroy()
                this.select = null
            }
            if (this.$refs.input) {
                this.$refs.input.removeEventListener('showDropdown', this._handlers.showDropdown)
                this.$refs.input.removeEventListener('change', this._handlers.change)
                this.$refs.input.removeEventListener('search', this._handlers.search)
                this.$refs.input.removeEventListener('search', this._handlers.debouncedSearch)
            }
            if (this.$wire && this.$wire.off && this._handlers.wireRefresh) {
                this.$wire.off('record-switcher:refresh', this._handlers.wireRefresh)
            }
        },

        refreshChoices: async function (config = {}) {
            let choices = []
            try {
                choices = await this.getChoices(config)
            } catch (e) {
                choices = []
            }
            this.refreshPlaceholder()
            this.setChoices(choices)
            if (![null, undefined, ''].includes(this.state)) {
                const selectedVal = this.state
                const el = this.select.dropdown.getChild(
                    `.choices__item[data-value="${selectedVal}"]`,
                )
                if (el) {
                    this.select._highlightChoice(el)
                    setTimeout(() => el.scrollIntoView({ block: 'nearest' }), 100)
                }
            }
        },

        setChoices: function (choices) {
            this.select.setChoices(choices, 'value', 'label', true)
        },

        getChoices: async function ({ search }) {
            let results = []
            try {
                results = await getResultsUsing(search)
            } catch (e) {
                results = []
            }
            let grouped = {}
            results.forEach(function (item) {
                if (!item.group) return
                if (!grouped[item.group]) {
                    grouped[item.group] = {
                        label: item.group,
                        id: item.group,
                        disabled: false,
                        choices: [],
                    }
                }
                grouped[item.group].choices.push(item)
            })
            return Object.keys(grouped).length === 0
                ? results
                : Object.values(grouped)
        },

        refreshPlaceholder: function () {
            this.select._renderItems()
            if (![null, undefined, ''].includes(this.state)) return
            const singleList = this.$el.querySelector('.choices__list--single')
            if (singleList) {
                singleList.innerHTML = `<div class="choices__placeholder choices__item">${placeholder ?? ''}</div>`
            }
        },
    }
}
