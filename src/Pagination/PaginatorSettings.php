<?php

namespace MikeProgLab\Searchable\Pagination;

class PaginatorSettings
{
    /**
     * @param int $page
     * @param int $perPage
     */
    public function __construct(
        private int $page,
        private int $perPage,
    )
    {
        //
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    /**
     * @return int
     */
    public function getPerPage(): int
    {
        return $this->perPage;
    }

    /**
     * @param int $perPage
     */
    public function setPerPage(int $perPage): void
    {
        $this->perPage = $perPage;
    }

}
