<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Vinkla\Hashids\Facades\Hashids;

class Order extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'orders';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable  = [
        'order_number',
        'user_id',
        'total_amount',
        'status',
        'products'
    ];

    protected $casts = [
        'products' => 'array',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    public static function boot(): void
    {
        parent::boot();

        static::saving(function ($model) {
            //
        });

        static::created(static function ($model) {
            $orderNumber = Hashids::encode($model->id);
            $model->order_number = $orderNumber;
            $model->save();
        });
    }
}
