<?php

namespace MikeProgLab\Searchable\Filter;

enum FilterOperator: string
{
    case EQUAL = '=';
    case NOT_EQUAL = '!=';
    case BETWEEN = 'between';
    case IN = 'In';
    case GREATER_THEN = '>';
    case LESS_THEN = '<';
}
