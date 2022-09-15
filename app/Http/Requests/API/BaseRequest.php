<?php

namespace App\Http\Requests\API;

use InfyOm\Generator\Request\APIRequest;
use Str;

abstract class BaseRequest extends APIRequest
{
    private $clean = false;

    public $removeRequired = true;
    /**
     * Regex constant to match unique constraints
     */
    protected static $umatch = '/unique:.*?,?.*?(\||\z)/';

    public $model = null;

    public $messages = [];

    public function all($KEYS = null)
    {
        return $this->sanitize(parent::all($KEYS));
    }

    /**
     * Get custom validation messages
     */
    public function messages()
    {
        if ($this->model == null) {
            $this->getModel();
        }
        return $this->messages;
    }

    public function getModel()
    {
        if ($this->model == null) {
            //try to get from classname
            $modelClass= "App\\Models".str_replace(["App\\Http\\Requests\\API", "Create", "Update", "APIRequest"], ["", "", "", ""], get_class($this));
            $this->model = new $modelClass;
            if (property_exists($modelClass, 'messages')) {
                $this->messages = $modelClass::$messages;
            }
        }
        return $this->model;
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        if ($this->removeRequired) {
            $requestClass = str_replace("App\\Http\\Requests\\API\\", "", get_class($this));
            if (Str::startsWith($requestClass, 'Update')){
                $rules = $this->rules();
                $filtered = [];
                foreach ($rules as $k => $rule) {
                    $r = str_replace(['required|', '|required'], ["", ""], $rule);
                    $r = preg_replace(self::$umatch, '', $r);
                    $filtered[$k] = $r;
                }
                $validator->setRules($filtered);
            }
        }
    }

    /**
     * Remove null inputs and trim inputs
     */
    protected function sanitize(Array $inputs)
    {
        if ($this->clean) {
            return $inputs;
        }

        foreach ($inputs as $k => $item) {
            if ($item === null) {
                unset($inputs[$k]);
            }
            if (is_string($item)) {
                $inputs[$k] = trim($item);
            }
        }

        $this->replace($inputs);
        $this->clean = true;
        return $inputs;
    }
}
