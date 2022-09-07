<?php


namespace Modules\Payment\Vilt\Resources\PaymentMethodResource\Traits;

trait Translations
{
    public function loadTranslations(): array
    {
        return [
            "index" => __(" Payment Methods"),
            "create" => __('Create ' . " Payment Method"),
            "bulk" => __('Delete Selected ' . " Payment Method"),
            "edit_title" => __('Edit ' . " Payment Method"),
            "create_title" => __('Create New ' . " Payment Method"),
            "view_title" => __('View ' . " Payment Method"),
            "delete_title" => __('Delete ' . " Payment Method"),
            "bulk_title" => __('Run Bulk Action To ' . " Payment Method"),
        ];
    }
}

