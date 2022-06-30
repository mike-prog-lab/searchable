<?php

namespace MikeProgLab\Searchable\Filter;

class FilterSolid extends Filter
{
    /**
     * @var string|null
     */
    protected ?string $relation = null;

    /**
     * @param string $field
     * @param string $value
     */
    public function __construct(
        private readonly string $field,
        private readonly string $value
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
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

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
}
