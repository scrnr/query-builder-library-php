# Join class

The `Join` class is used to construct join clauses for **SELECT** statement. It provides methods to specify the join type, join table, and join conditions.

## Methods

It has **five** public methods:

* [on](#on)
* [and](#and)
* [or](#or)
* [join](#join)
* [end](#end)

<a name='on'></a>
### On() [:top:](#methods)

```php
public function on(
    string|int $conditionOne,
    string|int $conditionTwo,
    ?string $tableOne = null,
    bool $needSecondTable = true
): static
```

This method specifies the **join condition** for the **JOIN** clause using the `ON` keyword.

##### Parameters:

* `conditionOne` (***String | Integer***) - The **first** condition for the join.
* `conditionTwo` (***String | Integer***) - The **second** condition for the join.
* `tableOne` (***String***) - ***Optional.*** The *name of the table* to use for the **first** condition.
* `needSecondTable` (***Boolean***) - The indicate if the *second table name* should be added to the condition. **Default - `true`**.

> ***NOTE:*** If `table` ***is not provided***, the method will use **the main name of the table**.

##### Return value:

* ***Static*** - The `Join` instance, which allows for method chaining.

#### Example

```php
$queryBuilder->select('users')
             ->all()
             ->from('orders')->column('price')
             ->join('orders', Join::LEFT)
               ->on('id', 'user_id')
             ->end()
             ->getQuery();
```

#### Output

```sql
SELECT users.*, orders.price
FROM users
LEFT JOIN orders
ON users.id = orders.user_id
```

<a name='and'></a>
### And() [:top:](#methods)

```php
public function and(
    string|int $conditionOne,
    string|int $conditionTwo,
    ?string $tableOne = null,
    bool $needSecondTable = true
): static;
```

This method adds an **additional condition** to the **JOIN** clause using the `AND` keyword.

##### Parameters:

* `conditionOne` (***String | Integer***) - The **first** condition for the join.
* `conditionTwo` (***String | Integer***) - The **second** condition for the join.
* `tableOne` (***String***) - ***Optional.*** The *name of the table* to use for the **first** condition.
* `needSecondTable` (***Boolean***) - The indicate if the *second table name* should be added to the condition. **Default - `true`**.

> ***NOTE:*** If `table` ***is not provided***, the method will use **the main name of the table**.

##### Return value:

* ***Static*** - The `Join` instance, which allows for method chaining.

#### Example

```php
$queryBuilder->select('orders')
             ->all()
             ->join('customers')
               ->on('customer_id', 'id')
               ->and('country', 'USA', 'customers', false)
             ->end()
             ->getQuery();
```

#### Output

```sql
SELECT orders.*
FROM orders
INNER JOIN customers
ON orders.customer_id = customers.id
AND customers.country = 'USA'
```

<a name='or'></a>
### Or() [:top:](#methods)

```php
public function or(
    string|int $conditionOne, 
    string|int $conditionTwo, 
    ?string $tableOne = null, 
    bool $needSecondTable = true
): static;
```

This method adds an **additional condition** to the **JOIN** clause using the `OR` keyword.

##### Parameters:

* `conditionOne` (***String | Integer***) - The **first** condition for the join.
* `conditionTwo` (***String | Integer***) - The **second** condition for the join.
* `tableOne` (***String***) - ***Optional.*** The *name of the table* to use for the **first** condition.
* `needSecondTable` (***Boolean***) - The indicate if the *second table name* should be added to the condition. **Default - `true`**.

> ***NOTE:*** If `table` ***is not provided***, the method will use **the main name of the table**.

##### Return value:

* ***Static*** - The `Join` instance, which allows for method chaining.

#### Example

```php
$queryBuilder->select('orders')
             ->all()
             ->join('customers')
               ->on('customer_id', 'id')
               ->and('country', 'USA', 'customers', false)
               ->or('country', 'Canada', 'customers', false)
             ->end()
             ->getQuery();
```

#### Output

```sql
SELECT orders.*
FROM orders
INNER JOIN customers
ON orders.customer_id = customers.id
AND customers.country = 'USA'
OR customers.country = 'Canada'
```

<a name='join'></a>
### Join() [:top:](#methods)

```php
public function join(string $joinTable, string $type = Join::INNER): static;
```

This method adds **a new join table** for the `Join` clause.

##### Parameters:

* `joinTable` (***String***) - The *name of the table* to join with the *current table*.
* `type` (***String***) - The *type* of join to perform. **Default - `Join::INNER`**.

> ***NOTE:***
>
> This should be one of the constants defined in the `Join` class:
>  * `Join::INNER`,
>  * `Join::LEFT`,
>  * `Join::RIGHT`.

##### Return value:

* ***Static*** - The `Join` instance, which allows for method chaining.

#### Example

```php
$queryBuilder->select('orders')
             ->column('id')
             ->from('customers')->alias('name', 'customer_name')
             ->from('products')->alias('name', 'product_name')
             ->join('customers')
               ->on('customer_id', 'id')
               ->join('products')
               ->on('product_id', 'id')
             ->end()
             ->getQuery();
```

#### Output

```sql
SELECT orders.id, customers.name AS customer_name, products.name AS product_name
FROM orders
INNER JOIN customers
ON orders.customer_id = customers.id
INNER JOIN products
ON orders.product_id = products.id
```

<a name='end'></a>
### End() [:top:](#methods)

```php
public function end(): Select;
```

This method **ends** the `Join` clause and **returns** the `Select` instance.

##### Parameters:

* This method takes no parameters.

##### Return value:

* ***Select*** - The `Select` instance.
