import Choices from 'choices.js'

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
                    item: 'choices__item choices__select__changer__item',
                },
            })

            this.refreshPlaceholder()

            this.$refs.input.addEventListener('showDropdown', async () => {
                this.select.clearChoices()

                await this.select.setChoices([
                    {
                        label: loadingMessage,
                        value: '',
                        disabled: true,
                    },
                ])

                await this.refreshChoices()
            })

            this.$refs.input.addEventListener('change', async () => {
                this.refreshPlaceholder()

                let value = this.select.getValue(true) ?? null

                // Prevent select from changing
                this.setChoices([
                    {
                        label: label,
                        value: state,
                        selected: true,
                    },
                ])

                return await updateSelected(value)
            })

            this.$refs.input.addEventListener('search', async (event) => {
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
            })

            this.$refs.input.addEventListener(
                'search',
                Alpine.debounce(async (event) => {
                    await this.refreshChoices({
                        search: event.detail.value?.trim(),
                    })

                    this.isSearching = false
                }, 250),
            )

            this.$wire.on('record-switcher:refresh', (details) => {
                this.select.clearChoices()
                this.select.setChoices([
                    {
                        label: details.label,
                        value: state,
                        selected: true,
                    },
                ])
            });
        },

        refreshChoices: async function (config = {}) {
            const choices = await this.getChoices(config)

            this.refreshPlaceholder()

            this.setChoices(choices)

            if (![null, undefined, ''].includes(this.state)) {
                const selectedVal = this.state
                const el = this.select.dropdown.getChild(
                    `.choices__item[data-value="${selectedVal}"]`,
                )

                if (el) {
                    this.select._highlightChoice(el)

                    // @todo improve with promise
                    setTimeout(
                        () =>
                            el.scrollIntoView({
                                block: 'nearest',
                            }),
                        100,
                    )
                }
            }
        },

        setChoices: function (choices) {
            this.select.setChoices(choices, 'value', 'label', true)
        },

        getChoices: async function ({ search }) {
            let results = await getResultsUsing(search)

            let grouped = {}

            results.forEach(function (item, i) {
                if (!item.group) {
                    return
                }

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

            if (![null, undefined, ''].includes(this.state)) {
                return
            }

            this.$el.querySelector(
                '.choices__list--single',
            ).innerHTML = `<div class="choices__placeholder choices__item">${
                placeholder ?? ''
            }</div>`
        },
    }
}
