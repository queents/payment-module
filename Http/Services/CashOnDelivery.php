<?php


namespace Modules\Payment\Http\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\Payment\Entities\Payment;
use Modules\Payment\Enums\CashOnDeliveryEnum;
use Modules\Payment\Http\Helpers\PaymentSaveToLogs;
use Modules\Payment\Http\Interfaces\IPaymentInterface;

class CashOnDelivery implements IPaymentInterface
{

    use PaymentSaveToLogs;
    private Collection $data ;


    /**
     * @param array $attributes
     * @return $this
     * @throws ValidationException
     *
     */
    public function validate(array $attributes): self
    {
        $validation =Validator::make($attributes, CashOnDeliveryEnum::VALIDATION);

        if ($validation->fails()) {
            throw ValidationException::withMessages($validation->errors()->messages());

        }

        $this->data = collect($validation->validated());
        return $this;
    }
    /**
     * @return $this
     */
    public function saveToPayment():self
    {
        $user=auth()->user();

        $record=Payment::create(
            [
                "model_id"=>$user->id,
                "model_table" => $user->getTable(),
                "order_id" =>$this->data['order_id'],
                "order_table" =>$this->data['order_table'],
                "payment_method_id" => $this->data['paymentMethodId'],
                "payment_status_id" => 3,
                "amount" =>$this->data['amount'],
                "notes" =>$this->data['notes']
            ]
        );

        return $this;
    }

    public function init(): IPaymentInterface
    {
        // TODO: Implement init() method.
        return $this;
    }

    public function pay(): array
    {
        return [
            "status" => true,
            "message" => "",
            "data" => ,
        ];

        // TODO: Implement pay() method.
    }

    public function callBack($request): void
    {

        // TODO: Implement callBack() method.
    }
}
