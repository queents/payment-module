<?php

namespace Modules\Payment\Entities;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * @property integer $id
 * @property mixed $name
 * @property mixed $description
 * @property string $color
 * @property string $icon
 * @property string $created_at
 * @property string $updated_at
 */
class PaymentStatus extends Model
{
    use HasTranslations;

    public $translatable = ['name', 'description'];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payment_status';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['name', 'description', 'color', 'icon', 'created_at', 'updated_at'];
}
