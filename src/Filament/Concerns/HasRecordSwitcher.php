<?php

namespace Howdu\FilamentRecordSwitcher\Filament\Concerns;

use Filament\Facades\Filament;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\Renderless;

use function Filament\Support\generate_search_column_expression;
use function Filament\Support\generate_search_term_expression;

trait HasRecordSwitcher
{
    protected int $maxSelectOptions = 10;

    public function getHeading(): string | Htmlable
    {
        if (! $this->isFilamentRecordSwitcherPluginInstalled()) {
            return parent::getHeading();
        }

        return new HtmlString(Blade::render('filament-record-switcher::components.record-switcher', [
            'value' => $this->getRecord()->getKey(),
            'label' => $this->getRecordSwitcherTitle(),
            'limit_results' => $this->maxSelectOptions,
        ]));
    }

    public function getRecordSwitcherTitle(): string | Htmlable
    {
        return $this->getRecordTitle();
    }

    /**
     * @return array<array{'label': string, 'value': string}>
     */
    #[Renderless]
    public function getRecordSwitcherOptions(?string $search = null): array
    {
        $query = $this->getRecordSwitcherQuery();

        if ($search) {
            static::applyRecordSwitcherAttributeConstraints($query, $search);
        }

        $query = $this->modifyRecordSwitcherQuery($query, $search);

        return $query->get()
            ->map(fn (Model $model) => $this->recordSwitcherItem($model))
            ->toArray();
    }

    protected static function applyRecordSwitcherAttributeConstraints(Builder $query, string $search): void
    {
        /** @var Connection $databaseConnection */
        $databaseConnection = $query->getConnection();

        $isForcedCaseInsensitive = static::isRecordSwitcherForcedCaseInsensitive();

        $search = generate_search_term_expression($search, $isForcedCaseInsensitive, $databaseConnection);

        foreach (explode(' ', $search) as $searchWord) {
            $query->where(function (Builder $query) use ($searchWord) {
                $isFirst = true;

                foreach (static::getRecordSwitcherSearchColumns() as $attributes) {
                    static::applyRecordSwitcherAttributeConstraint(
                        query: $query,
                        search: $searchWord,
                        searchAttributes: Arr::wrap($attributes),
                        isFirst: $isFirst,
                    );
                }
            });
        }
    }

    /**
     * @param  array<string>  $searchAttributes
     */
    protected static function applyRecordSwitcherAttributeConstraint(Builder $query, string $search, array $searchAttributes, bool &$isFirst): Builder
    {
        $isForcedCaseInsensitive = static::isRecordSwitcherForcedCaseInsensitive();

        /** @var Connection $databaseConnection */
        $databaseConnection = $query->getConnection();

        foreach ($searchAttributes as $searchAttribute) {
            $whereClause = $isFirst ? 'where' : 'orWhere';

            $query->when(
                str($searchAttribute)->contains('.')
                && ! str($searchAttribute)->contains('`'),
                function (Builder $query) use ($databaseConnection, $isForcedCaseInsensitive, $searchAttribute, $search, $whereClause): Builder {
                    return $query->{"{$whereClause}Relation"}(
                        (string) str($searchAttribute)->beforeLast('.'),
                        generate_search_column_expression((string) str($searchAttribute)->afterLast('.'), $isForcedCaseInsensitive, $databaseConnection),
                        'like',
                        "%{$search}%",
                    );
                },
                fn (Builder $query) => $query->{$whereClause}(
                    generate_search_column_expression($searchAttribute, $isForcedCaseInsensitive, $databaseConnection),
                    'like',
                    "%{$search}%",
                ),
            );

            $isFirst = false;
        }

        return $query;
    }

    protected function recordSwitcherItem(Model $model): array
    {
        $item = [
            'value' => self::getResource()::getUrl('edit', ['record' => $model->getRouteKey()]),
            'label' => $this->recordSwitcherItemLabel($model),
        ];

        if ($group = $this->recordSwitcherItemGroup($model)) {
            $item['group'] = $group;
        }

        /*if ($this->record && $model->getKey() === $this->record->getKey()) {
            $item['selected'] = true;
        }*/

        return $item;
    }

    protected static function getRecordSwitcherSearchColumns(): array
    {
        return self::getResource()::getGloballySearchableAttributes();
    }

    protected function recordSwitcherItemGroup(Model $model): ?string
    {
        return null;
    }

    protected function getRecordSwitcherQuery(): Builder
    {
        /** @var Builder $query */
        $query = $this->getModel()::query();

        return $query->limit($this->maxSelectOptions);
    }

    protected function modifyRecordSwitcherQuery(Builder $query, ?string $search): Builder
    {
        return $query;
    }

    protected function recordSwitcherItemLabel(Model $model): string
    {
        return self::getResource()::getRecordTitle($model);
    }

    /**
     * Wrap the given JSON selector.
     *
     * @param  string  $value
     * @return string
     */
    protected function wrapJsonSelector($value)
    {
        [$field, $path] = explode('->', $value);

        return 'json_unquote(json_extract(`' . $field . '`, \'$."' . $path . '"\'))';
    }

    protected static function isRecordSwitcherForcedCaseInsensitive()
    {
        return true;
    }

    private function isFilamentRecordSwitcherPluginInstalled(): bool
    {
        return Filament::hasPlugin('filament-record-switcher');
    }
}
