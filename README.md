# QueryBuilder for MySQL driver in PHP

<div>
  <img alt="Packagist PHP Version" src="https://img.shields.io/packagist/dependency-v/scrnr/query-builder/php?color=orange&label=PHP&logo=php&logoColor=white">
  <img alt="Packagist Version" src="https://img.shields.io/packagist/v/scrnr/query-builder?label=Packagist&logo=packagist&logoColor=white">
  <img alt="Packagist License" src="https://img.shields.io/packagist/l/scrnr/query-builder?label=LICENSE&logo=reacthookform&logoColor=white">
</div>

### For full documentation click [here](/docs/Table%20Of%20Contents.md)

### Table of contents
* [Description](#description)
* [Features](#features)
* [Installation](#installation)
* [How to use](#how-to-use)
* [Example](#example)
  * [First](#first-example)
  * [Second](#second-example)
  * [Third](#third-example)
* [Contribution](#contribution)
* [Author](#author)
* [License](#license)

<a name='description'></a>
## Description [:top:](#table-of-contents)

The PHP Database Query Library is a powerful and easy-to-use PHP library that allows you to easily write queries to database and receive a string with the builded query. It provides a simple and intuitive interface for building complex SQL statements in your PHP code.

<a name='features'></a>
## Features [:top:](#table-of-contents)

* **Easy to use**: The library provides a straightforward interface for building queries. You can easily create complex SQL statements without having to write raw SQL code.
* **Secure**: The library is designed with security in mind. It uses prepared statements to prevent SQL injection attacks.
* **Flexible**: The library supports a wide range of SQL operations such as SELECT, INSERT, UPDATE, DELETE.
* **Object-oriented approach**: The library follows an object-oriented approach to make it easier for developers to work with databases in PHP.
* **Easy to integrate**: The library is easy to integrate with existing PHP projects.

<a name='installation'></a>
## Installation [:top:](#table-of-contents)

You can install this library using [Composer](https://getcomposer.org/). Just add the following line to your `composer.json` file:

```json
"require": {
  "scrnr/query-builder": "*"
}
```

Or you can use this **command**:

```bash
composer require scrnr/query-builder
```

<a name='how-to-use'></a>
## How to use [:top:](#table-of-contents)

To use the library, first, you need to create a new instance of the `QueryBuilder` class:

```php
use Scrnr\QueryBuilder\QueryBuilder;

$queryBuilder = new QueryBuilder();
```

Then, you can use the various methods provided by the class to build your query. 
The `QueryBuilder` class has **four** public methods:
* [`select()`](/docs/QueryBuilder.md#select)
* [`insert()`](/docs/QueryBuilder.md#insert)
* [`update()`](/docs/QueryBuilder.md#update)
* [`delete()`](/docs/QueryBuilder.md#delete)

<a name='example'></a>
## Example [:top:](#table-of-contents)

The following example demonstrates how to use the `QueryBuilder` class to build SQL statements. 

```php
<?php

// Include the namespace
use Scrnr\QueryBuilder\QueryBuilder;

// Require the Composer autoload file
require_once __DIR__ . '/vendor/autoload.php';

// Create a new QueryBuilder instance
$queryBuilder = new QueryBuilder();
```

<a name='first-example'></a>
### First example [:top:](#table-of-contents)

```php
$queryBuilder->select('products')->all()->getQuery();
```
#### Output

```sql
SELECT products.* FROM products
```

In this example, we call the `select()` method with the *table name* and the `all()` method to select **all** columns. Then, we call the `getQuery()` method to get the SQL query.

<a name='second-example'></a>
### Second example [:top:](#table-of-contents)

```php
$queryBuilder->select('posts')
             ->columns('id', 'title', 'content', 'date')
             ->from('category')->alias('title', 'category')
             ->innerJoin('categories')->on('category_id', 'id')
             ->getQuery();
```

#### Output

```sql
SELECT posts.id, posts.title, posts.content, posts.date, category.title AS category
FROM posts
INNER JOIN categories
ON posts.category_id = categories.id
```

In this example, we call the `select()` method with the *table name*, the `columns()` method to select **specific** columns, the `from()` method with the *second table name*, and the `alias()` method to specify the *column and its alias*, and the `innerJoin()` method to join the *categories* table. Finally, we call the `getQuery()` method to get the SQL query

<a name='third-example'></a>
### Third example [:top:](#table-of-contents)

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

#### Output

```php
Array
(
  [0] => SELECT products.* FROM products WHERE products.category = :products_category ORDER BY :order ASC LIMIT :limit
  [1] => Array
      (
        [:products_category] => laptop
        [:order] => price
        [:limit] => 10
      )
)
```

In this example, we call the `select()` method with the *table name* and the `all()` method to select **all** columns. Then, we call the `where()` method to start building the **WHERE** clause, the `equal()` method to *specify the condition*, and the `end()` method to end the **WHERE** clause. Next, we call the `order()` method to *order* the results and the `limit()` method to *limit* the number of results returned. Finally, we call the `getAll()` method to get the SQL query and its prepared parameters as an array.

**For more information about the `getAll()` method click [here](/docs/QueryBuilder.md#getall).**

<a name='contribution'></a>
## Contribution [:top:](#table-of-contents)

Contributions to this library are welcome. You can report issues, suggest new features or submit pull requests on the [GitHub repository](https://github.com/scrnr/query-builder-library-php).

<a name='author'></a>
## Author [:top:](#table-of-contents)

:bust_in_silhouette: GitHub: [scrnr](https://github.com/scrnr)

<a name='license'></a>
## License [:top:](#table-of-contents)

This library is released under the MIT License. Please review the [LICENSE](https://github.com/scrnr/query-builder-library-php/blob/main/LICENSE) file for more information
