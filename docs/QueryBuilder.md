# QueryBuilder class

This class serves as the entry point for using the library and provides methods to create queries for various database operations.

## Methods

It has **four** public methods:
* [`select()`](#select)
* [`insert()`](#insert)
* [`update()`](#update)
* [`delete()`](#delete)

All the methods of the `QueryBuilder` class return an **instance** of the corresponding statement class: `Select`, `Insert`, `Update` or `Delete`. These objects provide an interface for building the corresponding SQL statement.

<a name='select'></a>
### Select() [:top:](#methods)

```php
public function select(string $tableName): Select
```

##### Parameters:

* `tableName` (***String***) - The *name of the table* to select from.

##### Return value:

* ***Select*** - The instance of the `Select` class, which can be used to build the **SELECT** statement.

#### For more information about the methods of the `Select` class, click [here](/docs/Select.md#select-class)

<a name='insert'></a>
### Insert() [:top:](#methods)

```php
public function insert(string $tableName): Insert
```

##### Parameters:

* `tableName` (***String***) - The *name of the table* to insert into.

##### Return value:

* ***Insert*** - The instance of the `Insert` class, which can be used to build the **INSERT** statement.

#### For more information about the methods of the `Insert` class, click [here](/docs/Insert.md#insert-class)

<a name='update'></a>
### Update() [:top:](#methods)

```php
public function update(string $tableName): Update
```

##### Parameters:

* `tableName` (***String***) - The *name of the table* where the update will take place.

##### Return value:

* ***Update*** - The instance of the `Update` class, which can be used to build the **UPDATE** statement.

#### For more information about the methods of the `Update` class, click [here](/docs/Update.md#update-class)

<a name='delete'></a>
### Delete() [:top:](#methods)

```php
public function delete(string $tableName): Delete
```

##### Parameters:

* `tableName` (***String***) - The *name of the table* to delete from.

##### Return value:

* ***Delete*** - The instance of the `Delete` class, which can be used to build the **DELETE** statement.

#### For more information about the methods of the `Delete` class, click [here](/docs/Delete.md#delete-class)

## NOTE

All methods of the `QueryBuilder` class, in addition to their own methods, include ***two*** common methods:
* [`getQuery()`](#getquery)
* [`getAll()`](#getall)

<a name='getquery'></a>
### getQuery() [:top:](#methods)

```php
public function getQuery(bool $withValues = true): string
```

##### Parameters:

* `withValues` (***Boolean***) - ***Optional.*** The parameter that indicates whether to include parameter values in the query. Set to `true` to include parameter values or `false` to include parameter values as placeholders

##### Return value:

* ***String*** - The **SELECT** query as a string.

**This method can be used to retrieve the final SQL query, but it's not suitable for executing the query if it has placeholders.**

The bool parameter `$withValues` specifies whether or not to include the parameter values in the query.

If `$withValues` is `true`, the parameter values will be included as **VALUES** in the query.

```sql
SELECT products.*
FROM products
WHERE products.category = 'laptop'
ORDER BY products.price ASC
LIMIT 10
```

If `$withValues` is `false`, the parameter values will be included as **PLACEHOLDERS** in the query.

```sql
SELECT products.*
FROM products
WHERE products.category = :products_category
ORDER BY :order ASC
LIMIT :limit
```

This method returns the **SELECT** query as a string.

> ***NOTE:*** If at least *one value* is not received, the method returns an *string with placeholders* (like `$withValues` is `false`).

<a name='getall'></a>
### getAll() [:top:](#methods)

```php
public function getAll(?array $values = null): array
```

##### Parameters:

* `values` (***Array | Null***) - ***Optional.*** The array containing parameter values to be used in the query.

##### Return value:

* ***Array*** - The array containing the **SELECT** query as a string and the *array* of the prepared values.

**This method should be used when the query has placeholders.**

This method returns an *array* with **two** elements:
* ***String*** with substituted placeholders.
* ***Associative array*** with the prepared parameter values where the **keys** are the placeholders, and the **values** are the corresponding prepared parameter values.

The `$values` parameter is ***optional***, and it should be passed if any placeholders are used in the query and you have not passed any values before.

#### Example

```php
$queryBuilder->select('products')
             ->all()
             ->where()
               ->equal('category', 'laptop')
             ->end()
             ->order('price')
             ->limit(10)
             ->getAll();
```

```php
$queryBuilder->select('products')
             ->all()
             ->where()
               ->equal('category')
             ->end()
             ->order()
             ->limit()
             ->getAll(['laptop', 'price', 10]);
```

##### These queries return the **same** array:

```php
Array
(
  [0] => SELECT products.* FROM products WHERE products.category = :products_category ORDER BY :order ASC LIMIT :limit
  [1] => Array
    (
      [:products_category] => 'laptop'
      [:order] => products.price
      [:limit] => 10
    )
)
```

Also, if you used only a part of the values when calling the function, then in `$values` array you can pass the remaining values in the *order* they were called in the functions.

```php
$queryBuilder->select('products')
             ->all()
             ->where()
               ->equal('category')
             ->end()
             ->order('price')
             ->limit()
             ->getAll(['laptop', 10]);
```

**The returned values will be the same.**
