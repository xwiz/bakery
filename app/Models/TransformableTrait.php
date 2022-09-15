<?php

namespace App\Models;

trait TransformableTrait
{
    /**
     * Exclude from transforms
     * @var array
     */
    protected $excludeTransforms = ['pivot', 'password', 'deleted_at'];

    /**
     * Check if column is transformable.
     * For this we check $excludeTransforms list. If it presented there - then it's not transformable. By default - all fields transforms at API
     * @param $key
     * @return bool
     */
    public function isTransformable($key)
    {
        return array_search($key, $this->excludeTransforms) === false;
    }
    
    /**
     * Get a list of table columns for this model
     */
    public function getTableColumns()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}
