<?php

namespace MikeProgLab\Searchable\Exception\Pagination;

use Throwable;

class SearchListPaginationException extends \Exception
{
    /**
     * @param string $class
     * @param Throwable|null $previous
     */
    public function __construct(string $class, ?Throwable $previous = null)
    {
        parent::__construct("Failed to retrieve list in $class.", 422, $previous);
    }
}
