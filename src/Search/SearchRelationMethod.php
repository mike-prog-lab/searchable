<?php

namespace MikeProgLab\Searchable\Search;

use Illuminate\Database\Eloquent\Builder;

/**
 * This ENUM is used to determine which
 * Builder method should be used for which field
 * in order.
 */
enum SearchRelationMethod: string
{
    case ROOT = 'where';
    case OTHER = 'whereRelation';

    /**
     * @param string $value
     * @param SearchField ...$fields
     * @return array|\Closure[]
     */
    public function getEloquentSearchMainQueryArguments(
        string $value,
        SearchField ...$fields
    ): array
    {
        $closure = function (Builder $subQuery) use ($value, $fields) {
            $firstField = array_pop($fields);

            $subQuery->{$this->value}(...$this->getSearchSubQueryWhereArguments($firstField, $value));

            foreach ($fields as $field) {
                $subQuery->{$this->getOrMethod()}(...$this->getSearchSubQueryWhereArguments($field, $value));
            }
        };

        return [
            $closure,
        ];
    }

    /**
     * @param SearchField $field
     * @param $value
     * @return array
     */
    public function getSearchSubQueryWhereArguments(SearchField $field, $value): array
    {
        return match ($this) {
            self::ROOT => [
                $field->getName(),
                $field->getOperator()->value,
                $field->getOperator()->formatSearchValue($value)
            ],
            self::OTHER => [
                $field->getRelation(),
                $field->getName(),
                $field->getOperator()->value,
                $field->getOperator()->formatSearchValue($value)
            ]
        };
    }

    /**
     * @return string
     */
    public function getOrMethod(): string
    {
        return "or" . ucfirst($this->value);
    }
}
