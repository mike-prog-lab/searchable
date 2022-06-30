<?php

namespace MikeProgLab\Searchable\Sort;

class Sorter
{
    /**
     * @param string $field
     * @param SorterDirection $direction
     */
    public function __construct(
        private readonly string $field,
        private readonly SorterDirection $direction
    )
    {
        //
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @return SorterDirection
     */
    public function getDirection(): SorterDirection
    {
        return $this->direction;
    }

}
