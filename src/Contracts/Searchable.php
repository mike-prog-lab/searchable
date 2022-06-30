<?php

namespace MikeProgLab\Searchable\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;
use MikeProgLab\Searchable\Filter\SearchFilter;
use MikeProgLab\Searchable\Sort\Sorter;

interface SearchableRepository
{
    /**
     * @param SearchFilter ...$filters
     * @return static
     */
    public function filter(SearchFilter ...$filters): static;

    /**
     * @param Sorter $sorter
     * @return $this
     */
    public function sort(Sorter $sorter): static;

    /**
     * @param string $value
     * @return $this
     */
    public function search(string $value): static;

    /**
     * @return mixed
     */
    public function list(): mixed;

    /**
     * @return LengthAwarePaginator
     */
    public function paginate(): LengthAwarePaginator;
}
