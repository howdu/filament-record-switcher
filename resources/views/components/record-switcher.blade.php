<div
    x-ignore
    x-load-css="[@js(\Filament\Support\Facades\FilamentAsset::getStyleHref(
    'record-switcher',
    package: 'howdu/filament-record-switcher'
    ))]"
    ax-load
    ax-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc(
        'record-switcher',
        'howdu/filament-record-switcher'
    ) }}"
    x-data="selectChangerComponent({
        getResultsUsing: async (search) => await $wire.getRecordSwitcherOptions(search),
        label: @js($label),
        loadingMessage: '{{ __('filament-forms::components.select.loading_message') }}',
        noSearchResultsMessage: '{{ __('filament-forms::components.select.no_search_results_message') }}',
        optionsLimit: @js($limit_results),
        placeholder: '{{ __('filament-forms::components.select.placeholder') }}',
        searchPrompt: '{{ __('filament-forms::components.select.search_prompt') }}',
        searchingMessage: '{{ __('filament-forms::components.select.searching_message') }}',
        state: @js($value),
        updateSelected: (value) => window.location.href = value,
    })"
    wire:ignore
    x-on:keydown.esc="select.dropdown.isActive && $event.stopPropagation()"
    class="filament-record-switcher relative flex items-center gap-2 cursor-pointer"
>
    @if (! empty($icon))
        <x-dynamic-component
            :component="$icon"
            :x-tooltip.raw="$icon_name ?? ''"
            class="inline-block h-6 w-6 stroke-current text-gray-500 dark:text-gray-300"
        />
    @endif

    <select
        x-ref="input"
        class="pointer-events-none appearance-none border-none bg-transparent !bg-none p-0 text-2xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-3xl"
    >
        <option value="{{ $value }}">{{ $label }}</option>
    </select>
</div>
