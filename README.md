# Introduction

This plugin allows you to migrate your Prestashop data to Sylius. It is able to recreate the tree structure of
categories, import products and their variations, shops, product images, etc...

The wish is that you can decorate every part of it to use according to your needs.

Please use this plugin only on a developing site, ideally before starting work on the site, as this plugin has the
ability to completely remove the database for work.

I am not responsible for the loss of your data. I created this plugin with the aim of making life easier for developers,
I will try to keep the documentation as clear as possible.

I hope this plugin helps you as much as it helped me. I will continue to make it grow and evolve with the new projects
that I will meet (and your feedback perhaps? :blush:).

***

# Requirements

- Sylius 1.10 minimum
- PHP 8.0 minimum

***

# Installation

1. Add the plugin to your project

```bash 
$ composer require jgrasp/sylius-prestashop-migration-plugin
```

<br>

2. Add plugin dependency to your ```config/bundles.php``` file:

```php
    return [
        ...
        Jgrasp\PrestashopMigrationPlugin\PrestashopMigrationPlugin::class => ['all' => true], 
    ]
```

<br>

3. Add .env variables

```dotenv
   # Enter the correct Prestashop database login details.
   PRESTASHOP_DATABASE_URL=mysql://root@127.0.0.1/sylius_%kernel.environment%
   
   # Custom the valid URL where product images are stored. If this variable is empty, the plugin will try to find images with the Prestashop database. 
   PRESTASHOP_IMG_DIRECTORY_URL=https://www.example.com/img/p/ 
```

<br>

4. Create a new doctrine DBAL connection

```yaml
doctrine:
  dbal:
    connection:
      prestashop:
        url: '%env(resolve:PRESTASHOP_DATABASE_URL)%'
```

<br>

5. Add package configuration

Create a new configuration file in ```config/packages``` like ```prestashop.yaml``` and put this configuration :

```yaml
prestashop_migration:
  # The directory for product images
  public_directory: "%env(PRESTASHOP_IMG_DIRECTORY_URL)%"

  # Doctrine DBAL connection to retrieve data from Prestashop  
  connection: prestashop

  # Read the documentation to see how custom this field. 
  resources: ~
```
<br>

6. Custom Entities

Add the following code

```php
use PrestashopTrait;
```

in entities : 

- ```App\Entity\Addressing\Address```
- ```App\Entity\Addressing\Country```
- ```App\Entity\Addressing\Zone```
- ```App\Entity\Channel\Channel```
- ```App\Entity\Currency\Currency```
- ```App\Entity\Customer\Customer```
- ```App\Entity\Locale\Locale```
- ```App\Entity\Product\Product```
- ```App\Entity\Product\ProductOption```
- ```App\Entity\Product\ProductOptionValue```
- ```App\Entity\Product\ProductVariant```
- ```App\Entity\Shipping\ShippingMethod```
- ```App\Entity\Taxation\TaxRate```
- ```App\Entity\User\AdminUser```

This trait is essential & add a link between Sylius & Prestashop entities.

<br>

7. Upgrade your database

```bash
$ php bin/console doctrine:migrations:diff
$ php bin/console doctrine:migrations:migrate
```

<br>

8. Configure the locale parameter

The parameter must be a locale that exists in the list of active languages of the Prestashop you want to migrate. Without this, the migration of the translations will not be able to be done correctly.
```yaml
parameters:
    locale: en_EN
```

<br>

Congratulations ! Your project is ready for the migration. Let's start with [How to use](doc/how_to_use.md) !

***


