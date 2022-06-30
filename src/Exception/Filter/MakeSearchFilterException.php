<?php

namespace MikeProgLab\Searchable\Exception\Filter;

use Throwable;

class MakeSearchFilterException extends \Exception
{
    /**
     * @param array $data
     * @param Throwable|null $previous
     */
    public function __construct(
        private readonly array $data,
        ?Throwable $previous = null
    )
    {
        parent::__construct(
            'Unsupported data provided to make SearchFilter: ' . json_encode($data),
            400,
            $previous
        );
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
