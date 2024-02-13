<?php

namespace App\Http\Helpers\QueryBuilder;

use Unlu\Laravel\Api\QueryBuilder;

class AllQueryBuilder extends QueryBuilder {

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
        $this->transformQuery();
        $result = $this->query->get();

        if ($this->hasAppends()) {
            $result = $this->addAppendsToModel($result);
        }

        return $result;
    }

    public function count() {
        $this->transformQuery();
        return $this->query->count();
    }

    private function transformQuery() {
        $excludeColumns = ['business_id', 'appointment_id', 'alarm_code', 'system_serial_number'];
        foreach ($this->query->getQuery()->wheres as $key => $param) {
            if (in_array($param['column'], $excludeColumns)) {
                continue;
            }

            if ($param['type'] == 'In') {
                foreach ($param['values'] as $k => $value) {
                    $this->query->getQuery()->wheres[$key]['values'][$k] = $this->transformValue($value);
                }
            } else {
                $this->query->getQuery()->wheres[$key]['value'] = $this->transformValue($param['value']);
            }
        }
    }

    private function transformValue($value) {
        if ($value === 'true') {
            $value = true;
        }

        if ($value === 'false') {
            $value = false;
        }

        if (is_numeric($value)) {
            if (intval($value)) {
                $value = (int)$value;
            }

            if (doubleval($value)) {
                $value = (double)$value;
            }
        }

        return $value;
    }

    public function filterBySkip($query, $skip) {
        return $query->skip((int)$skip);
    }

    public function withRelation($relations) {
        $this->query->with($relations);
        return $this;
    }

    public function withRelationCount($relations) {
        $this->query->withCount($relations);
        return $this;
    }

    public function withoutGlobalScope($scope) {
        $this->query->withoutGlobalScope($scope);
        return $this;
    }

    public function withoutGlobalScopes(array $scopes = null) {
        $this->query->withoutGlobalScopes($scopes);
        return $this;
    }

    function isValidTimeStamp($timestamp)
    {
        return is_string($timestamp) &&
            ((string) (int) $timestamp === $timestamp)
            && ($timestamp <= PHP_INT_MAX)
            && ($timestamp >= ~PHP_INT_MAX);
    }

    public function where($column,$value){
        $this->query->where($column,$value);
        return $this;
    }
}