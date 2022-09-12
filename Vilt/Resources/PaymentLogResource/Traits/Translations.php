<?php


namespace Modules\Payment\Vilt\Resources\PaymentLogResource\Traits;

trait Translations
{
    public function loadTranslations(): array
    {
        return [
            "index" => __(" Payment Logs"),
            "create" => __('Create ' . " Payment Log"),
            "bulk" => __('Delete Selected ' . " Payment Log"),
            "edit_title" => __('Edit ' . " Payment Log"),
            "create_title" => __('Create New ' . " Payment Log"),
            "view_title" => __('View ' . " Payment Log"),
            "delete_title" => __('Delete ' . " Payment Log"),
            "bulk_title" => __('Run Bulk Action To ' . " Payment Log"),
        ];
    }
}

