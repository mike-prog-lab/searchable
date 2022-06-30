<?php

namespace MikeProgLab\Searchable\Search;

enum SearchOperator: string
{
    case LIKE = 'LIKE';

    /**
     * @param string $value
     * @return mixed
     */
    public function formatSearchValue(string $value): string
    {
        return match ($this) {
            self::LIKE => "%{$value}%",
        };
    }
}
