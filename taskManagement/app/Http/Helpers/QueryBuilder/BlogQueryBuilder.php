<?php

namespace App\Http\Helpers\QueryBuilder; //My folder

use Unlu\Laravel\Api\QueryBuilder; //extent this for custom queryBuilder

class BlogQueryBuilder extends QueryBuilder {

    public function where($column,$value){
        $this->query->where($column,$value);
        return $this;
    }
}