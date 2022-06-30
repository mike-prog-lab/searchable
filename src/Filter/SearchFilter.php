<?php

namespace MikeProgLab\Searchable\Filter;

interface SearchFilter
{
    /**
     * @return string
     */
    public function getField(): string;

    /**
     * @return mixed
     */
    public function getValue(): mixed;

    /**
     * @return string|null
     */
    public function getRelation(): ?string;

    /**
     * @return FilterOperator
     */
    public function getOperator(): FilterOperator;
}
