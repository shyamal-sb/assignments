<?php

namespace App\Http\Helpers\QueryBuilder; //My folder

use Unlu\Laravel\Api\QueryBuilder; //extent this for custom queryBuilder

class BlogQueryBuilder extends QueryBuilder {

    /*
    private function hasAppends() {
        return (count($this->appends) > 0);
    }

    private function addAppendsToModel($result) {
        $result->map(function ($item) {
            $item->append($this->appends);
            return $item;
        });

        return $result;
    }
    

    public function get() {
        $result = $this->query->get();

        if ($this->hasAppends()) {
            $result = $this->addAppendsToModel($result);
        }

        return $result;
    }

    public function count() {
        return $this->query->count();
    }
    */
    
    public function where($column,$value){
        $this->query->where($column,$value);
        return $this;
    }
}