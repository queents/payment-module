<?php


namespace Modules\Payment\Vilt\Resources\PaymentMethodIntegrationResource\Traits;

trait Translations
{
    public function loadTranslations(): array
    {
        return [
            "index" => __(" Payment Method Integrations"),
            "create" => __('Create ' . " Payment Method Integration"),
            "bulk" => __('Delete Selected ' . " Payment Method Integration"),
            "edit_title" => __('Edit ' . " Payment Method Integration"),
            "create_title" => __('Create New ' . " Payment Method Integration"),
            "view_title" => __('View ' . " Payment Method Integration"),
            "delete_title" => __('Delete ' . " Payment Method Integration"),
            "bulk_title" => __('Run Bulk Action To ' . " Payment Method Integration"),
        ];
    }
}

