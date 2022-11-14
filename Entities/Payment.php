<?php

namespace Modules\Payment\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $payment_method_id
 * @property integer $payment_status_id
 * @property integer $model_id
 * @property string $model_table
 * @property string $transaction_code
 * @property float $amount
 * @property string $notes
 * @property string $created_at
 * @property string $updated_at
 * @property PaymentMethod $paymentMethod
 * @property PaymentStatus $paymentStatus
 */
class Payment extends Model
{
    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paymentMethod()
    {
        return $this->belongsTo('Modules\Payment\Entities\PaymentMethod');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paymentStatus()
    {
        return $this->belongsTo('Modules\Payment\Entities\PaymentStatus');
    }
}
