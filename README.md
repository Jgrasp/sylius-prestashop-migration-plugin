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
