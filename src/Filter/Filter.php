<?php

namespace MikeProgLab\Searchable\Filter;

abstract class Filter implements SearchFilter
{
    /**
     * @var string|null
     */
    protected ?string $relation = null;

    /**
     * @var FilterOperator
     */
    protected FilterOperator $operator = FilterOperator::EQUAL;

    /**
     * @return string|null
     */
    public function getRelation(): ?string
    {
        return $this->relation;
    }

    /**
     * @param string|null $relation
     */
    public function setRelation(?string $relation): void
    {
        $this->relation = $relation;
    }

    /**
     * @return FilterOperator
     */
    public function getOperator(): FilterOperator
    {
        return $this->operator;
    }

    /**
     * @param FilterOperator $operator
     */
    public function setOperator(FilterOperator $operator): void
    {
        $this->operator = $operator;
    }
}
