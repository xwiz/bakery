<?php

namespace App\Models;

use Illuminate\Http\Request;

trait ScopeHelperTrait
{

    // where condition by field
    public function scopeByField($query, $field, $value, $operator = '=')
    {
        return $query->where($field, $operator, $value);
    }

    /**
     * Quick way to find and return a model by user_id field
     * @param  [[Type]] $query [[Description]]
     * @param  int $id    the user_id
     * @return [[Type]] [[Description]]
     */
    public function scopeByUser($query, $id)
    {
        return $query->where('user_id', '=', $id)->first();
    }

    // by list of fields with =
    public function scopeFilters($query, $filters)
    {
        foreach ($filters as $k => $v) {
            $query->byField($k, $v);
        }
        return $query;
    }

    // fast scope for ordering DESC
    public function scopeDesc($query, $field)
    {
        return $query->orderBy($field, 'desc');
    }

    // fast scope for ordering asc
    public function scopeAsc($query, $field)
    {
        return $query->orderBy($field, 'asc');
    }


    // search by date
    public function scopeToday($query, $field = 'created_at')
    {
        return $query->whereRaw("DATE(".$field.") = CAST(".date("'Y-m-d'", time())." as DATE)");
    }

    // search by date
    public function scopeByDay($query, $field, $value)
    {
        return $query->whereRaw("DATE(".$field.") = CAST(".date("'Y-m-d'", strtotime($value))." as DATE)");
    }

    // search by range between dates
    public function scopeByDayRange($query, $field, $value1, $value2)
    {
        return $query->whereRaw(
            "( DATE(".$field.") >= CAST(".date("'Y-m-d'", strtotime($value1))." as DATE) 
            AND DATE(".$field.") <= CAST(".date("'Y-m-d'", strtotime($value2))." as DATE) )");
    }

    // search by last days
    public function scopeByLastDays($query, $field, $days)
    {
        return $query->whereRaw(
            "( DATE(".$field.") >= CAST(".date("'Y-m-d'", strtotime("-$days days"))." as DATE) 
            AND DATE(".$field.") <= CAST(".date("'Y-m-d'", time())." as DATE) )");
    }

    // search by older than dates
    public function scopeByOlder($query, $field, $date)
    {
        return $query->whereRaw(
            "DATE(".$field.") <= CAST(".date("'Y-m-d'", $date)." as DATE)");
    }

    // scope by field like
    public function scopeByFieldLike($query, $field, $value)
    {
        return $query->where($field, 'like', "%".$value."%");
    }

    // scope by list for field LIKE
    public function scopeByFieldListLike($query, $fields, $value)
    {
        $query->where(
            function ($query) use ($fields, $value) {
                foreach ($fields as $field) {
                    $query->orWhere($field, 'like', "%".$value."%");
                }
            });
        return $query;
    }
}