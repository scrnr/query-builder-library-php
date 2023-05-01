# Update class

The `Update` class provides a fluent *interface* for building and executing **UPDATE** queries. It allows you to specify the table to update and set new values for columns, as well as add conditions to the **WHERE** clause.

## Methods

It has **five** public methods:

* [`set()`](#set)
* [`setPrepare()`](#setprepare)
* [`where()`](#where)
* [`getQuery()`](/docs/QueryBuilder.md#getquery)
* [`getAll()`](/docs/QueryBuilder.md#getall)

<a name='set'></a>
### Set() [:top:](#methods)

```php
public function set(array $columns, array $values): static;
```
This method allows you to specify the *columns* and *values* that you want to **update** in a database table. You can pass an array of column names and values as parameters to this method.

##### Parameters:

* `columns` (***Array***) - The array of *column names* to update.
* `values` (***Array***) - The array of *values* to set for the *columns* that were specified in the `$columns` parameter.

##### Return value:

* ***Static*** - The `Update` instance, which allows for method chaining.

#### Example

```php
$queryBuilder->update('users')
             ->set(['phone', 'email'], [89123456789, 'example@email.com'])
             ->getQuery();
```

#### Output

```sql
UPDATE users
SET phone = 89123456789, email = "example@email.com"
```

<a name='setprepare'></a>
### SetPrepare() [:top:](#methods)

```php
public function setPrepare(string ...$columns): static;
```

This method allows you to specify the *columns* that you want to **update** in a database table, and prepares placeholders for the corresponding *values*. You can pass **one** or **more** *column names* as parameters to this method.

##### Parameters:

* `columns` (***String[]***) - ***Variadic.*** The parameter that accepts **one** or **more** *column names* to update.

##### Return value:

* ***Static*** - The `Update` instance, which allows for method chaining.

#### Example

```php
$queryBuilder->update('users')
             ->setPrepare('phone', 'email')
             ->getAll([89123456789, 'example@email.com']);
```

#### Output

```php
Array
(
  [0] => UPDATE users SET phone = :phone, email = :email
  [1] => Array
      (
        [:phone] => 89123456789
        [:email] => example@email.com
      )
)
```

<a name='where'></a>
### Where() [:top:](#methods)

```php
public function where(): Where;
```

This method starts building the *WHERE* clause of the **UPDATE** query. It returns an instance of the `Where` class, which can be used to add conditions to the *WHERE* clause.

##### Parameters:

* This method takes no parameters.

##### Return value:

* ***Where*** - The instance of the `Where` class, which starts building the *WHERE* clause of the query.

#### Example

```php
$queryBuilder->update('users')
             ->set(['phone', 'email'], [89123456789, 'example@email.com'])
             ->where()
               ->equal('id', 5)
             ->end()
             ->getQuery();
```

#### Output

```sql
UPDATE users
SET phone = 89123456789, email = "example@email.com"
WHERE users.id = 5
```

#### For more information about the methods of the `Where` class, click [here](/docs/Where%20And%20Having.md#where-and-having-class)
