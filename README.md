# Vilt Payment

Payment Services Integrations & Management Module for VILT Framework

## Installation

You can install the package via composer:

```bash
composer require queents/payment-module
```

Install the package

```bash
php artisan payment:install
```

and now clear cache

```bash
php artisan optimize:clear
```

##  List of providers

- [Fawry Pay](https://fawry.com/)
- [Paytabs](https://site.paytabs.com/en)
- [Paymob](https://paymob.com/)

## How to use

you have 4 facade methods
```php
//request contain data that will sent to payment methods
\PaymentModule::pay($request->all())
//return error message empty if success
\PaymentModule::getErrorMessage()
//return array with data that contain the payment url
\PaymentModule::getData()
//takes payment method id and the request from payment getway
\PaymentModule::callback($request->all(),$paymentMethod)
```
## Data base ERD

**Notes**
- Payments table has **order_id ,order_table** morph relation
> maybe you have orders table for customers and vendor_orders for vendors
- Payments table has **model_id ,model_table** morph relation
> maybe you have users table for customers and vendors table for vendors
- name and description columns are json format to allow you to use spatie translation if you want

## Classes
We have a facade class that use payment services as explained in how to use  after that payment use factory pattern to fill payment methods from DB if it's available with it's integrations and have return payment instance for given payment id from request.

## UML


## Traits
We have 2 traits that you can use where ever you want
- HttpHelper
> This trait has post method using GuzzleHttp tacks **uri ,data** as parameters and public variables timeout as integer and header as array contain default header  so you can change it from out side from the trait
- PaymentSaveToLogs
> Tacks only response and payload and store them as json

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Queen Tech Solutions](https://github.com/queents)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
