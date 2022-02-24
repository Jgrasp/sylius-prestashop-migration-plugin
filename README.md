# Introduction

This plugin allows you to migrate your Prestashop data to Sylius. It is able to recreate the tree structure of
categories, import products and their variations, shops, product images, etc...

The wish is that you can decorate every part of it to use according to your needs.

Please use this plugin only on a developing site, ideally before starting work on the site, as this plugin has the
ability to completely remove the database for work.

I am not responsible for the loss of your data. I created this plugin with the aim of making life easier for developers,
I will try to keep the documentation as clear as possible.

I hope this plugin helps you as much as it helped me. I will continue to make it grow and evolve with the new projects
that I will meet (and your feedback perhaps? :)).

***

# Requirements

- Sylius 1.10 minimum 
- PHP 8.0 minimum
***
# Installation

1. Add the package to your project

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

3. Create a new DBAL connection

To retrieve data from Prestashop, the plugin will directly fetch information from the database.

```yaml
doctrine:
  dbal:
    connection:
      prestashop:
        url: '%env(resolve:PRESTASHOP_DATABASE_URL)%'
```




***

- [Installation](doc/installation.md)

Tuto :

Créer une nouvelle connexion doctrine pour Prestashop

```
doctrine:
    dbal:
        connections:
            prestashop:
                dbname:
                user:
                password:
                host:
                server_version:
```

Ajouter PrestashopTrait aux entités :

- Taxon
- Product

Todo :

A migrer :

- Groupe de clients
- Clients

A implémenter :

- Pour l'import des images, il faut prendre en compte les BDD très grandes et faire des flush réguliers.
- Améliorer le script d'import des images

# Ajouter une nouvelle entité à transformer :

Exemple avec une entité Book

## Créer un model

## Créer un

## Ajouter la configuration

```
prestashop_migration:
    resources:
        book:
            table: book
            repository: App\Prestashop\Repository\Book\BookRepository
            model: App\Prestashop\Model\Book\BookModel
            primary_key: id_book
            sylius: book
            
```

ATTENTION : LA LOCALE DE SYLIUS PAR DEFAUT DOIT ÊTRE UNE LOCALE EXISTANTE DANS PRESTASHOP POUR QUE L'IMPORT SE PASSE
BIEN

Créer un fichier de configuration par défaut
