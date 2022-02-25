# How to use

### Show me the list !

This tool works only in command line. To display all available commands, use the following command :

```bash
$ php bin/console list prestashop

# Will print 
Available commands for the "prestashop" namespace:
  prestashop:migration:address         
  prestashop:migration:admin_user      
  prestashop:migration:all             
  prestashop:migration:channel         
  prestashop:migration:country         
  prestashop:migration:currency        
  prestashop:migration:customer        
  prestashop:migration:locale          
  prestashop:migration:product         
  prestashop:migration:product:images  
  prestashop:migration:taxon 
```

Lot of commands ! Indeed, it's possible to manually migrate each entity.

But unfortunately, some commands have dependencies between them and data can be missed if they were not executed in the
correct order.

For example, it would be impossible to import the addresses without first importing the users.

### One command to import them all

Fortunately, there is a command among the list that allows migrating the entities in the correct order.

```bash
$ php bin/console prestashop:migration:all
```

This command will simply call a list of commands in a defined order. However, it only "completes" the database, without deleting existing data. (For more information, you can see the [How it works]() documentation).

<br>

If you want to delete the existing data, you can add the ```--force``` parameter

```bash
$ php bin/console prestashop:migration:all --force
```

:warning: This command will completely delete the database and re-create it. Be sure of what you are doing. Never use this command on a live site. Also verify that the database user has sufficient rights.

<br>

And that's all. You don't have to do anything more, this tool takes care of everything for you.

Well, maybe not everything. A Prestashop project generally contains specific developments, additional fields that you would like to find on Sylius.

<br>

Let's see now [How you can customize this plugin]().
