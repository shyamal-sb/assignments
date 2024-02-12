<?php

namespace App\Http\Helpers\QueryBuilder; //My folder

use Unlu\Laravel\Api\QueryBuilder; //extent this for custom queryBuilder

class PostQueryBuilder extends QueryBuilder {

    public function where($column,$value){
        $this->query->where($column,$value);
        return $this;
    }

    public function filterByUserId($query, $id, $operator)
    {
      return $query->whereHas('comment', function($q) use ($id, $operator) {
        return $q->where('published_at', $operator, $id);
      });
    }


    public function postedBy($query, $id, $operator)
    {
        return $query->with('comment', function($q) use ($id, $operator) {
            return $q->where('published_at', $operator, $id);
        });
    }

}