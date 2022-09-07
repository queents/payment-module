<?php


namespace Modules\Payment\Vilt\Resources\PaymentResource\Traits;

trait Translations
{
    public function loadTranslations(): array
    {
        return [
            "index" => __(" Payments"),
            "create" => __('Create ' . " Payment"),
            "bulk" => __('Delete Selected ' . " Payment"),
            "edit_title" => __('Edit ' . " Payment"),
            "create_title" => __('Create New ' . " Payment"),
            "view_title" => __('View ' . " Payment"),
            "delete_title" => __('Delete ' . " Payment"),
            "bulk_title" => __('Run Bulk Action To ' . " Payment"),
        ];
    }
}

