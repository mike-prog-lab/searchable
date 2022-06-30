<?php

namespace MikeProgLab\Searchable\Search;

use App\Entities\DTO\Searchable\Search\SearchOperator;

class SearchField
{
    public const NO_RELATION = 'root';

    /**
     * @param string $name
     * @param SearchOperator $operator
     * @param string|null $relation
     */
    public function __construct(
        private readonly string $name,
        private readonly SearchOperator $operator = SearchOperator::LIKE,
        private readonly ?string $relation = self::NO_RELATION
    )
    {
        //
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getRelation(): string
    {
        return $this->relation;
    }

    /**
     * @return SearchOperator
     */
    public function getOperator(): SearchOperator
    {
        return $this->operator;
    }
}
