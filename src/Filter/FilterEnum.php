<?php

namespace MikeProgLab\Searchable\Filter;

class FilterEnum extends Filter
{
    /**
     * @param string $field
     * @param array $value
     */
    public function __construct(
        private readonly string $field,
        private readonly array $value
    )
    {
        $this->operator = FilterOperator::IN;
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @return array
     */
    public function getValue(): array
    {
        return $this->value;
    }
}
