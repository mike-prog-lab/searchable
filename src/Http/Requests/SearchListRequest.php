<?php

namespace MikeProgLab\Searchable\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use MikeProgLab\Searchable\Filter\FilterType;
use MikeProgLab\Searchable\Filter\SearchFilter;
use MikeProgLab\Searchable\Pagination\PaginatorSettings;
use MikeProgLab\Searchable\Rules\SearchFilterRule;
use MikeProgLab\Searchable\Sort\Sorter;
use MikeProgLab\Searchable\Sort\SorterDirection;

class SearchListRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'search.value' => ['sometimes', 'string'],
            'filters.*' => ['sometimes', new SearchFilterRule()],
            'sort.field' => ['sometimes', 'string', 'max:255'],
            'sort.direction' => ['sometimes', new Enum(SorterDirection::class)],
            'pagination.page' => ['sometimes', 'required_with:pagination.perPage', 'integer', 'min:1'],
            'pagination.perPage' => ['sometimes', 'required_with:pagination.page', 'integer', 'min:1'],
        ];
    }

    /**
     * @return SearchFilter[]
     * @throw MakeSearchFilterFromArrayException
     */
    public function getSearchFilters(): array
    {
        /** @var array $filters */
        $filters = $this->request->get('filters', []);

        return collect($filters)->map(function ($filter) {
            return FilterType::searchFilterFromArray($filter);
        })->filter(function ($filter) {
            return $filter instanceof SearchFilter;
        })->toArray();
    }

    /**
     * @return string|null
     */
    public function getSearchValue(): ?string
    {
        $search = $this->request->get('search', ['value' => null]);

        return $search['value'];
    }

    /**
     * @return PaginatorSettings|null
     */
    public function getPaginatorSettings(): ?PaginatorSettings
    {
        $pagination = $this->request->get('pagination');

        if (! $pagination) {
            return null;
        };

        return new PaginatorSettings(
            $pagination['page'],
            $pagination['perPage'],
        );
    }

    /**
     * @return Sorter|null
     */
    public function getSorter(): ?Sorter
    {
        $sort = $this->request->get('sort');

        if (! $sort) {
            return null;
        }

        return new Sorter(
            $sort['field'],
            SorterDirection::from($sort['direction']),
        );
    }
}
