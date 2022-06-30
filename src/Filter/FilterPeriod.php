<?php

namespace MikeProgLab\Searchable\Filter;

class FilterPeriod extends Filter
{
    /**
     * @param string $field
     * @param string|null $from
     * @param string|null $to
     */
    public function __construct(
        private readonly string $field,
        private readonly ?string $from,
        private readonly ?string $to
    )
    {
        $this->operator = FilterOperator::BETWEEN;
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
        return [$this->from, $this->to];
    }
}
