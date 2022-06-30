<?php

namespace MikeProgLab\Searchable\Filter;

use Illuminate\Database\Eloquent\Model;

class FilterModel extends FilterSolid
{
    /**
     * @param Model $model
     */
    public function __construct(
        private readonly Model $model,
    )
    {
        parent::__construct(
            $this->model->getForeignKey(),
            $this->model->getKey(),
        );
    }
}
