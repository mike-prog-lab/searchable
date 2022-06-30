<?php

namespace MikeProgLab\Searchable\Repository;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use MikeProgLab\Searchable\Contracts\SearchableRepository;
use MikeProgLab\Searchable\Filter\FilterAggregate;
use MikeProgLab\Searchable\Filter\FilterOperator;
use MikeProgLab\Searchable\Filter\SearchFilter;
use MikeProgLab\Searchable\Search\SearchField;
use MikeProgLab\Searchable\Search\SearchRelationMethod;
use MikeProgLab\Searchable\Sort\Sorter;

abstract class SearchableEloquentRepository implements SearchableRepository
{
    /** @var Builder */
    protected Builder $queryBuilder;

    /** @var int  */
    protected int $page = 1;

    /** @var int  */
    protected int $itemsPerPage = 10;

    /**
     * @var Closure[]
     */
    protected array $searchWhereQueries = [];

    /**
     * @var Closure[]
     */
    protected array $filterWhereQueries = [];

    /**
     * @return Builder
     */
    abstract protected function initQuery(): Builder;

    /**
     * This method is in charge of executing request
     * and format it in required type and format.
     *
     * @return Collection
     */
    protected function executeQuery(): Collection
    {
        return $this->queryBuilder->get();
    }


    /**
     * @return LengthAwarePaginator
     */
    protected function paginateQuery(): LengthAwarePaginator
    {
        return $this->queryBuilder->paginate(
            perPage: $this->itemsPerPage,
            page: $this->page,
        );
    }

    /**
     * @return Collection
     */
    public function list(): Collection
    {
        $this->applyQueries();

        return $this->executeQuery();
    }

    /**
     * @return LengthAwarePaginator
     */
    public function paginate(): LengthAwarePaginator
    {
        $this->applyQueries();

        return $this->paginateQuery();
    }

    /**
     * @return void
     */
    protected function applyQueries(): void
    {
        $this->queryBuilder->where(function (Builder $query) {
            foreach ($this->filterWhereQueries as $filterQuery) {
                $filterQuery($query);
            }
        });

        $this->queryBuilder->where(function (Builder $query) {
            foreach ($this->searchWhereQueries as $searchQuery) {
                $searchQuery($query);
            }
        });
    }

    /**
     * Search method will perform search on following fields in the
     * query.
     *
     * @return SearchField[]
     */
    abstract static public function getSearchableFields(): array;

    public function __construct()
    {
        $this->queryBuilder = static::initQuery();
    }

    /**
     * @param int $page
     * @return static
     */
    public function setPage(int $page): static
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @param int $itemsPerPage
     * @return static
     */
    public function setItemsPerPage(int $itemsPerPage): static
    {
        $this->itemsPerPage = $itemsPerPage;

        return $this;
    }

    /**
     * @return Builder
     */
    protected function flushQuery(): Builder
    {
        $this->queryBuilder = static::initQuery();

        return $this->queryBuilder;
    }

    /**
     * @param SearchFilter ...$filters
     * @return $this
     */
    public function filter(SearchFilter ...$filters): static
    {
        $closure = function (Builder $query) use ($filters) {
            foreach ($filters as $filter) {

                if ($filter->getRelation()) {
                    $query->whereHas($filter->getRelation(), function (Builder $subQuery) use ($filter) {
                        $this->applyFilterToQuery($subQuery, $filter);
                    });
                    continue;
                }

                $this->applyFilterToQuery($query, $filter);
            }
        };
        $this->filterWhereQueries[] = $closure;

        return $this;
    }

    /**
     * @param Builder $query
     * @param SearchFilter $filter
     * @return void
     */
    protected function applyFilterToQuery(Builder $query, SearchFilter $filter): void
    {
        $filterArguments = [
            $filter->getField(),
            $filter->getOperator()->value,
            $filter->getValue(),
        ];

        if ($filter->getOperator() === FilterOperator::IN) {
            $query->whereIn($filter->getField(), $filter->getValue());
            return;
        }

        if ($filter->getOperator() === FilterOperator::BETWEEN) {
            [$from, $to] = $filter->getValue();

            if (! $from) {
                $query->where(
                    $filter->getField(),
                    FilterOperator::LESS_THEN->value,
                    $to
                );
                return;
            }

            if (! $to) {
                $query->where(
                    $filter->getField(),
                    FilterOperator::GREATER_THEN->value,
                    $from
                );
                return;
            }

            $query->whereBetween($filter->getField(), $filter->getValue());
            return;
        }

        if ($filter instanceof FilterAggregate) {
            $query->having(...$filterArguments);
            return;
        }

        $query->where(...$filterArguments);
    }

    /**
     * @param Sorter $sorter
     * @return $this
     */
    public function sort(Sorter $sorter): static
    {
        $this->queryBuilder->reorder($sorter->getField(), $sorter->getDirection()->value);

        return $this;
    }

    /**
     * @param string $value
     * @return mixed
     */
    public function search(string $value): static
    {
        $fields = static::getSearchableFields();

        if (count($fields) < 1) {
            return $this;
        }

        $this->searchWhereQueries[] = function (Builder $query) use ($value, $fields) {
            $this->setSearchWhereConditions(
                $query,
                $value,
                ...$fields
            );
        };

        return $this;
    }

    /**
     * @param Builder $query
     * @param string $value
     * @param SearchField ...$fields
     * @return void
     */
    protected function setSearchWhereConditions(Builder $query, string $value, SearchField ...$fields): void
    {
        $fieldsByRelation = collect($fields)->groupBy(function (SearchField $field) {
            return $field->getRelation();
        });

        $rootFields = $fieldsByRelation[SearchField::NO_RELATION] ?? [];
        unset($fieldsByRelation[SearchField::NO_RELATION]);

        if (! empty($rootFields)) {
            $this->applyRelationFieldsWhereQuery(
                $query,
                $value,
                SearchRelationMethod::ROOT,
                true,
                ...$rootFields
            );
        }

        foreach ($fieldsByRelation as $relationFields) {
            $this->applyRelationFieldsWhereQuery(
                $query,
                $value,
                SearchRelationMethod::OTHER,
                false,
                ...$relationFields
            );
        }
    }

    /**
     * @param Builder $query
     * @param string $value
     * @param SearchRelationMethod $whereMethod
     * @param bool $first
     * @param SearchField ...$fields
     * @return void
     */
    protected function applyRelationFieldsWhereQuery(
        Builder $query,
        string $value,
        SearchRelationMethod $whereMethod,
        bool $first = false,
        SearchField ...$fields,
    ): void
    {
        $queryMethod = $first ? 'where' : 'orWhere';

        $query->$queryMethod(...$whereMethod->getEloquentSearchMainQueryArguments(
            $value,
            ...$fields
        ));
    }
}
