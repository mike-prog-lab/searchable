<?php

namespace MikeProgLab\Searchable\Service;

use Illuminate\Contracts\Queue\QueueableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use MikeProgLab\Searchable\Filter\SearchFilter;
use MikeProgLab\Searchable\Pagination\PaginatorSettings;
use MikeProgLab\Searchable\Repository\SearchableEloquentRepository;
use MikeProgLab\Searchable\Sort\Sorter;

class SearchEloquentService implements SearchableService
{
    /** @var SearchFilter[] */
    protected array $filters = [];

    /**
     * @param SearchableEloquentRepository $repository
     */
    public function __construct(
        protected SearchableEloquentRepository $repository
    )
    {
        //
    }

    /**
     * @param SearchFilter $filter
     * @return static
     */
    public function setFilter(SearchFilter $filter): static
    {
        $this->filters[] = $filter;

        return $this;
    }

    /**
     * @param ?string $searchValue
     * @param PaginatorSettings|null $paginatorSettings
     * @param Sorter|null $sorter
     * @return QueueableCollection|array|LengthAwarePaginator
     */
    public function list(
        ?string $searchValue = null,
        PaginatorSettings $paginatorSettings = null,
        Sorter $sorter = null
    ): QueueableCollection|array|LengthAwarePaginator
    {

        $query = $this->repository->filter(...$this->filters);

        if ($searchValue) {
            $query->search($searchValue);
        }

        if ($sorter) {
            $query->sort($sorter);
        }

        if ($paginatorSettings) {
            return $query->setPage($paginatorSettings->getPage())
                ->setItemsPerPage($paginatorSettings->getPerPage())
                ->paginate();
        }

        return $query->list();
    }
}
