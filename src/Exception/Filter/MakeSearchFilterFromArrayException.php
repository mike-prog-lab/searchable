<?php

namespace MikeProgLab\Searchable\Exception\Filter;

use Throwable;

class MakeSearchFilterFromArrayException extends \Exception
{
    /**
     * @param array $input
     * @param Throwable|null $previous
     */
    public function __construct(private readonly array $input, ?Throwable $previous = null)
    {
        parent::__construct(
            "Failed to make SearchFilter from array input.",
            422,
            $previous
        );
    }

    /**
     * @return array
     */
    public function getInput(): array
    {
        return $this->input;
    }
}
