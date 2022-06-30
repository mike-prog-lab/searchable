<?php

namespace MikeProgLab\Searchable\Filter;

use Illuminate\Support\Arr;
use MikeProgLab\Searchable\Exception\Filter\MakeSearchFilterException;
use MikeProgLab\Searchable\Exception\Filter\MakeSearchFilterFromArrayException;

enum FilterType: string
{
    case MODEL = 'model';
    case SOLID = 'solid';
    case PERIOD = 'period';
    case ENUM = 'enum';
    case AGGREGATE = 'aggregate';

    /**
     * @param array $input
     * @return SearchFilter
     * @throws MakeSearchFilterFromArrayException
     */
    public static function searchFilterFromArray(array $input): SearchFilter
    {
        try {
            return self::from($input['type'])
                ->make(Arr::only($input, ['field', 'data', 'relation']));
        } catch (\ValueError|MakeSearchFilterException $exception) {
            throw new MakeSearchFilterFromArrayException($input, $exception);
        }
    }

    /**
     * @param array $input
     * @return SearchFilter
     * @throws MakeSearchFilterException
     */
    public function make(array $input): SearchFilter
    {
        try {
            $filter = match ($this) {
                self::PERIOD => new FilterPeriod(
                    field: $input['field'],
                    from: $input['data']['from'] ?? null,
                    to: $input['data']['to'] ?? null
                ),
                self::SOLID => new FilterSolid(
                    field: $input['field'],
                    value: $input['data']['value']
                ),
                self::MODEL => new FilterModel(
                    model: $input['data']['user']
                ),
                self::ENUM => new FilterEnum(
                    field: $input['field'],
                    value: $input['data']['value']
                ),
                self::AGGREGATE => new FilterAggregate(
                    field: $input['field'],
                    value: $input['data']['value']
                )
            };

            $filter->setRelation($input['relation'] ?? null);

            if (
                isset($input['data']['negation'])
                && $filter instanceof FilterSolid
            ) {
                $filter->setOperator(FilterOperator::NOT_EQUAL);
            }

            return $filter;
        } catch (\Throwable $exception) {
            throw new MakeSearchFilterException($input, $exception);
        }
    }
}
