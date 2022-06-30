<?php

namespace MikeProgLab\Searchable\Service;

use Illuminate\Contracts\Queue\QueueableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use MikeProgLab\Searchable\Exception\Pagination\SearchListPaginationException;
use MikeProgLab\Searchable\Filter\SearchFilter;
use MikeProgLab\Searchable\Pagination\PaginatorSettings;
use MikeProgLab\Searchable\Sort\Sorter;

interface SearchableService
{
    /**
     * @param SearchFilter $filter
     * @return $this
     */
    public function setFilter(SearchFilter $filter): static;

    /**
     * @param string|null $searchValue
     * @param PaginatorSettings|null $paginatorSettings
     * @param Sorter|null $sorter
     * @return QueueableCollection|array|LengthAwarePaginator
     * @throws SearchListPaginationException
     */
    public function list(
        ?string $searchValue,
        PaginatorSettings $paginatorSettings = null,
        Sorter $sorter = null
    ): QueueableCollection|array|LengthAwarePaginator;
}
