<?php


namespace Modules\Payment\Vilt\Resources\PaymentStatusResource\Traits;

trait Translations
{
    public function loadTranslations(): array
    {
        return [
            "index" => __(" Payment Status"),
            "create" => __('Create ' . " Payment Status"),
            "bulk" => __('Delete Selected ' . " Payment Status"),
            "edit_title" => __('Edit ' . " Payment Status"),
            "create_title" => __('Create New ' . " Payment Status"),
            "view_title" => __('View ' . " Payment Status"),
            "delete_title" => __('Delete ' . " Payment Status"),
            "bulk_title" => __('Run Bulk Action To ' . " Payment Status"),
        ];
    }
}

