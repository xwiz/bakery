<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use RuntimeException;
use ReflectionObject;
use App\Repositories\AddressRepository;
use App\Repositories\CountryRepository;
use App\Repositories\StateRepository;
use App\Repositories\CityRepository;
use Illuminate\Validation\ValidationException;
use App\Models\Country;

/**
 * Mix this in to your model class to enable fillable relations.
 * Usage:
 *     use Illuminate\Database\Eloquent\Model;
 *     use LaravelFillableRelations\Eloquent\Concerns\HasFillableRelations;
 *
 *     class Foo extends BaseModel
 *     {
 *         use HasFillableRelations;
 *         protected $fillable_relations = ['bar'];
 *
 *         function bar()
 *         {
 *             return $this->hasOne(Bar::class);
 *         }
 *     }
 *
 *     $foo = new Foo(['bar' => ['id' => 42]]);
 *     // or perhaps:
 *     $foo = new Foo(['bar' => ['name' => "Ye Olde Pubbe"]]);
 *
 * @mixin Model
 */
trait FillableRelationsTrait
{
    ///**
    // * The relations that should be mass assignable.
    // *
    // * @var array
    // */
    // protected $fillable_relations = [];

    public function fillableRelations()
    {
        return isset($this->fillable_relations) ? $this->fillable_relations : [];
    }

    public function extractFillableRelations(array $attributes)
    {
        $relationsAttributes = [];

        foreach ($this->fillableRelations() as $relationName) {
            $val = Arr::pull($attributes, $relationName);
            if ($val !== null) {
                $relationsAttributes[$relationName] = $val;
            }
        }

        return [$relationsAttributes, $attributes];
    }

    public function fillRelations(array $relations)
    {
        foreach ($relations as $relationName => $attributes) {
            $relation = $this->{Str::camel($relationName)}();

            $relationType = (new ReflectionObject($relation))->getShortName();
            $method = "fill{$relationType}Relation";
            if (!method_exists($this, $method)) {
                throw new RuntimeException("Unknown or unfillable relation type {$relationType} ({$relationName})");
            }
            $this->{$method}($relation, $attributes, $relationName);
        }
    }

    public function fill(array $attributes)
    {
        list($relations, $attributes) = $this->extractFillableRelations($attributes);

        parent::fill($attributes);

        $this->fillRelations($relations);

        return $this;
    }

    public static function create(array $attributes = [])
    {
        list($relations, $attributes) = (new static)->extractFillableRelations($attributes);

        $model = new static($attributes);
        $model->fillRelations($relations);
        $model->save();

        return $model;
    }

    /**
     * @param BelongsTo $relation
     * @param array|Model $attributes
     */
    public function fillBelongsToRelation(BelongsTo $relation, $attributes, $relationName)
    {
        $entity = $attributes;
        if ($relationName == 'address') {
            $address = $this->extractAddress($attributes);
            $relation->associate($address);
            return;
        }
        if (!$attributes instanceof Model) {
            $model = $relation->getRelated();

            $fillable = array_flip($model->getFillable());
            $guarded = array_flip($model->getExcludedTransforms());
            $test = array_intersect_key($attributes, $fillable);
            $test = array_diff_key($test, $guarded);
            $entity = $model->where($test)->first();
            if (!$entity) {
                $className = get_class($model);
                $repoClass = str_replace("Models", "Repositories", $className)."Repository";
                $mRepo = resolve($repoClass);
                if (isset($attributes['id'])) {
                    $mRepo->update($attributes, $attributes['id']);
                } else {
                    $entity = $mRepo->create($attributes, true);
                }
            }
            $this->{$relation->getForeignKeyName()} = $entity->{$entity->getKeyName()};
        }

        $relation->associate($entity);
        $this->load($relationName);
    }
    
    /**
     * Extract and save address if available
     * @param array $addressInput The request data
     * @param App\Models\BaseModel $model The model file. Pass th)is to indicate update
     */
    private function extractAddress($addressInput)
    {
        if (!isset($addressInput['country_id'])) {
            if (isset($addressInput['country'])) {
                $countryRepo = resolve(CountryRepository::class);
                $country = $countryRepo->searchQuery($addressInput['country'])->first();
            } else {
                $country = Country::nigeria()->first();
            }
            $addressInput['country_id'] = $country->id;
        }
        if (!isset($addressInput['state_id'])) {
            if (isset($addressInput['state'])) {
                $stateRepo = resolve(StateRepository::class);
                $condition = ['country_id' => $addressInput['country_id']];
                $state = $stateRepo->searchQuery($addressInput['state'], null, null, $condition)->first();
                if (!$state) {
                    throw new ValidationException(['state' =>"Could not find state ".$addressInput['state']]);
                }
                $addressInput['state_id'] = $state->id;
            }
        }
        if (!isset($addressInput['city_id'])) {
            if (isset($addressInput['city'])) {
                $cityRepo = resolve(CityRepository::class);
                $condition = ['state_id' => $addressInput['state_id']];
                $city = $cityRepo->searchQuery($addressInput['city'], null, null, $condition)->first();
                if ($city == null) {
                    $city = $cityRepo->searchQuery(str_replace('-', ' ', $addressInput['city']), null, null, $condition)->first();
                }
                if (!$city) {
                    throw new ValidationException(['city' => "Could not find city ".$addressInput['city']]);
                }
                $addressInput['city_id'] = $city->id;
            }
        }

        $addressRepo = resolve(AddressRepository::class);
        $address = $addressRepo->allQuery($addressInput)->first();
        if (!$address) {
            return $addressRepo->create($addressInput);
        }
        return $address;
    }

    /**
     * @param HasOne $relation
     * @param array|Model $attributes
     */
    public function fillHasOneRelation(HasOne $relation, $attributes, $relationName)
    {
        $this->fillHasOneOrManyRelation($relation, [$attributes], $relationName);
    }

    /**
     * @param HasMany $relation
     * @param array $attributes
     */
    public function fillHasManyRelation(HasMany $relation, array $attributes, $relationName)
    {
        $this->fillHasOneOrManyRelation($relation, $attributes, $relationName);
    }

    /**
     * @param HasOneOrMany $relation
     * @param array $attributes
     */
    private function fillHasOneOrManyRelation($relation, array $attributes, $relationName)
    {

//        $relation->delete();

        foreach ($attributes as $related) {
            if (!$related instanceof Model) {
                if (isset($related['address']))
                {        
                    $address = $this->extractAddress($related['address']);
                    $related['address_id'] = $address->id;
                }

                //prevent address creation without validation
                if (!$this->exists) {
                    $this->save();
                    $relation = $this->{Str::camel($relationName)}();
                }
        
                if (method_exists($relation, 'getHasCompareKey')) { // Laravel 5.3
                    $foreign_key = explode('.', $relation->getHasCompareKey());
                    $related[$foreign_key[1]] = $relation->getParent()->getKey();
                } else {  // Laravel 5.5+
                    $related[$relation->getForeignKeyName()] = $relation->getParentKey();
                }
                $related = $relation->getRelated()->newInstance($related);
                $related->exists = $related->wasRecentlyCreated;
                if (isset($related['id'])) {
                    $related->update();
                }
            }

            $relation->save($related);
        }
        $this->load($relationName);
    }

    /**
     * @param BelongsToMany $relation
     * @param array $attributes
     */
    public function fillBelongsToManyRelation(BelongsToMany $relation, array $attributes, $relationName)
    {
        if (!$this->exists) {
            $this->save();
            $relation = $this->{Str::camel($relationName)}();
        }

        $relation->detach();
        $pivotColumns = [];
        foreach ($attributes as $related) {
            if (isset($related['pivot']) && is_array($related['pivot'])) {
                $pivotColumns = $related['pivot'];
                unset($related['pivot']);
            }
            if (!$related instanceof Model) {
                $relationKey = Str::snake(get_class($relation))."_id";
                $related = $relation->getRelated()
                    ->where($related)->firstOrCreate([$relationKey => $related->id]);
            }

            $relation->attach($related, $pivotColumns);
        }
    }

    /**
     * @param MorphTo $relation
     * @param array|Model $attributes
     */
    public function fillMorphToRelation(MorphTo $relation, $attributes, $relationName)
    {
        $entity = $attributes;

        if (! $entity instanceof Model) {
            $entity = $relation->getRelated()->firstOrCreate($entity);
        }

        $relation->associate($entity);
    }

    /**
     * @param HasMany $relation
     * @param array $attributes
     */
    public function fillMorphManyRelation(MorphMany $relation, array $attributes, $relationName)
    {
        if (!$this->exists) {
            $this->save();
            $relation = $this->{Str::camel($relationName)}();
        }

        $relation->delete();

        foreach ($attributes as $related) {
            if (!$related instanceof Model) {
                if (method_exists($relation, 'getHasCompareKey')) { // Laravel 5.3
                    $foreign_key = explode('.', $relation->getHasCompareKey());
                    $related[$foreign_key[1]] = $relation->getParent()->getKey();
                } else {  // Laravel 5.5+
                    $related[$relation->getForeignKeyName()] = $relation->getParentKey();
                }
                $related = $relation->getRelated()->newInstance($related);
                $related->exists = $related->wasRecentlyCreated;
            }

            $relation->save($related);
        }
    }
}