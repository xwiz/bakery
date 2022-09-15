<?php namespace App\Models;

use Aloha\Twilio\Twilio;
use Djunehor\Sms\Concrete\InfoBip;
use Djunehor\Sms\Concrete\SmartSmsSolutions;
use App\Providers\SmsFactoryProvider;
use App\Providers\MtargetSMSProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\MessageBag;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Encryption\Encrypter;

class BaseModel extends Model
{

    /**
     * =====================================================
     * Base Model for extra query functions
     * =====================================================
     */

     /**
      * Gets the fields of this model that should be encrypted
      */
     public $encrypts = [];

    /**
     * Default hidden attributes
     * These attributes will be excluded from JSON
     */
    protected $hidden = ['pivot', 'password', 'updated_at', 'deleted_at'];

    /**
     * Default attributes excluded from search
     * These attributes will be excluded from search function
     */
    protected $excludeSearch = ['pivot', 'password'];

    protected $admin_fillable = ['is_approved', 'approved_by', 'is_published', 'published_by'];

    public static $number_formats = ['amount', 'wallet_balance', 'price', 'paid_amount', 'fees'];

    public $file_fields = ['file_url', 'picture_url', 'logo_url', 'icon_url'];

    /**
     * Exclude from transforms
     * @var array
     */
    protected $excludeTransforms = ['pivot', 'password', 'deleted_at'];

    /**
     * Override the original setAttribute to encrypt fields as needed
     */
    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->encrypts)) {
            $crypt = new Encrypter(config('constants.model_key'));
            $value = $crypt->encrypt($value);
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * Override the original getAttribute to decrypt fields as needed
     */
    public function getAttribute($key)
    {
        if (in_array($key, $this->encrypts)) {
            $crypt = new Encrypter(config('constants.model_key'));
            return $crypt->decrypt($this->attributes[$key]);
        }

        return parent::getAttribute($key);
    }

    /**
     * Override the original attributesToArray to decrypt fields as needed
     */
    public function attributesToArray()
    {
        $attributes = parent::attributesToArray();

        foreach ($attributes as $key => $value) {
            if (in_array($key, $this->encrypts)) {
                $crypt = new Encrypter(config('constants.model_key'));
                $attributes[$key] = $crypt->decrypt($value);
            }
        }

        return $attributes;
    }


    /**
     * Gets a unique slug using the title for the optionally specified slug field for this table
     *
     * @param string $title The string you want to use to generate a slug
     * @param string  $s    The slug field in the table
     *
     * @return string A unique slug
     */
    public function getSlugExpr($title, $s = 'slug')
    {
        $slug = Str::slug($title);
        $slugCount = count( $this->whereRaw($s." REGEXP '^{$slug}(-[0-9]*)?$'")->get() );

        return ($slugCount > 0) ? "{$slug}-{$slugCount}" : $slug;
    }

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
    // search by older than dates by day
    public function scopeByOlderDays($query, $field, $days)
    {
        $date = date("'Y-m-d'", strtotime("-$days days"));
        return $query->whereRaw(
            "DATE(".$field.") <= CAST(".$date." as DATE)");
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

    /**
     * We need custom key getter because default is not reliable
     *
     * @param $query The query builder object
     *
     * @return string A unique cache key for this query
     */
    public static function customCacheKey($query)
    {
        return md5($query->getModel()->getConnectionName().$query->toSql().serialize($query->getBindings()));
    }

    /**
     * Finds an instance of this model by specified slug
     *
     * @param string $slug The model slug
     * @param string $field The slug field of the model
     *
     * @return App\Models\BaseModel
     */
    public static function findBySlug($slug, $field = 'slug')
    {
        return self::where($field, $slug)->first();
    }

    /**
     * Check if column is transformable.
     * For this we check $excludeTransforms list. If it presented there - then it's not transformable. By default - all fields transforms at API
     * @param $key
     * @return bool
     */
    public function isTransformable($key)
    {
        return (array_search($key, $this->excludeTransforms) === false && array_search($key, $this->hidden) === false);
    }

    /**
     * Check if column is searchable.
     * For this we check $searchable list.
     * @param $key
     * @return bool
     */
    public function isSearchable($key)
    {
        return array_search($key, $this->excludeSearch) === false;
    }


    /**
     * Get instance with initiated data from array
     *
     * @param $data
     *
     * @return \Illuminate\Support\Collection|null|static
     */
    public static function instanceFromArray($data)
    {
        $class = get_called_class();
        if (isset($data['id'])) {
            // return empty object
            $object = new $class();
            $object->forceFill($data);
        }
        if (!isset($object)) {
            // return empty object
            $object = new $class;
        }
        return $object;
    }


    /**
     * Get a list of table columns for this model
     */
    public function getTableColumns()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }

    /**
     * Send sms to phone number from model
     * @param $message
     * @param $phoneNumber
     */
    public function sendSMS($message, $phoneNumber)
    {
        if (app()->environment() != 'production') {
            \Log::debug("sms sent $phoneNumber");
            return true;
        }
        $send = send_sms($message, $phoneNumber, config('laravel-sms.sender'), MtargetSMSProvider::class);
        return $send;
    }

    public function sendInfoBip($message, $phoneNumber)
    {
        $username = config('laravel-sms.infobip.username');
        $password = config('laravel-sms.infobip.password');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://wp98zq.api.infobip.com/sms/2/text/advanced',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{"messages":[{"from":"infoSMS","destinations":[{"to":"'.$phoneNumber.'"}],"text":"'.$message.'"}]}',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic ' . base64_encode("$username:$password"),
                'Content-Type: application/json',
                'Accept: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
    }
}
