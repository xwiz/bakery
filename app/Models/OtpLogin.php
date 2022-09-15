<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
 use Illuminate\Database\Eloquent\Factories\HasFactory;;
/**
 * @OA\Schema(
 *      schema="OtpLogin",
 *      required={},
 *      @OA\Property(
 *          property="id",
 *          description="",
 *          readOnly=true,
 *          nullable=true,
 *          type="integer",
 *          format="int32"
 *      ),
 *      @OA\Property(
 *          property="user_id",
 *          description="",
 *          readOnly=false,
 *          nullable=true,
 *          type="integer",
 *          format="int32"
 *      ),
 *      @OA\Property(
 *          property="attempts",
 *          description="",
 *          readOnly=false,
 *          nullable=true,
 *          type="integer",
 *          format="int32"
 *      ),
 *      @OA\Property(
 *          property="created_at",
 *          description="",
 *          readOnly=true,
 *          nullable=true,
 *          type="string",
 *          format="date-time"
 *      ),
 *      @OA\Property(
 *          property="updated_at",
 *          description="",
 *          readOnly=true,
 *          nullable=true,
 *          type="string",
 *          format="date-time"
 *      )
 * )
 */class OtpLogin extends BaseModel
{
    use HasFactory;    public $table = 'otp_logins';

    public $fillable = [
        'user_id',
        'otp',
        'ip_address',
        'user_agent',
        'attempts'
    ];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'attempts' => 'integer'
    ];

    public static $rules = [
        'otp' => 'max:6|min:6',
        'ip_address' => 'max:15|min:7',
        'user_agent' => 'max:500'
    ];

    
}
