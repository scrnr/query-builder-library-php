# Where and Having class

The `Where` and `Having` classes are used in SQL statements to filter data based on certain conditions. The `Where` class is used in **SELECT**, **DELETE** and **UPDATE** statements. The `Having` class is used in **SELECT** statements with **GROUP BY** clauses. Both classes provide methods for chaining conditions together with the [`and()`](#and) and [`or()`](#or) methods. They also have the [`end()`](#end) method that returns the query object to allow for further method chaining or execution of the query.

## Methods

It has **seventeen** public methods:

* [and](#and)
* [or](#or)
* [equal](#equal) or [notEqual](#equal)
* [less](#less) or [lessOrEqual](#less)
* [greater](#greater) or [greaterOrEqual](#greater)
* [like](#like) or [notLike](#like)
* [null](#null) or [notNull](#null)
* [between](#between) or [notBetween](#between)
* [in](#in) or [notIn](#in)
* [end](#end)

<a name='and'></a>
### And() [:top:](#methods)

```php
public function and(): static;
```

This method adds an `AND` operator to the **WHERE** clause.

##### Parameters:

* This method takes no parameters.

##### Return value:

* ***Static*** - The `Where` instance, which allows for method chaining.

<a name='or'></a>
### Or() [:top:](#methods)

```php
public function or(): static;
```

This method adds an `OR` operator to the **WHERE** clause.

##### Parameters:

* This method takes no parameters.

##### Return value:

* ***Static*** - The `Where` instance, which allows for method chaining.

<a name='equal'></a>
### Equal() or NotEqual() [:top:](#methods)

```php
public function equal(
    string $column,
    mixed $value = null, 
    ?string $table = null, 
    ?string $sqlFunction = null
): static;
```
```php
public function notEqual(
    string $column, 
    mixed $value = null, 
    ?string $table = null, 
    ?string $sqlFunction = null
): static;
```

This method adds an `=` or `<>` comparison to the **WHERE** clause.

##### Parameters:

* `column` (***String***) - The *name of the column* to compare.
* `value` (***Mixed***) - ***Optional.*** The *value to compare to*.
* `table` (***String***) - ***Optional.*** The *name of the table* that the column belongs to.

> ***NOTE:*** If `table` ***is not provided***, the method will use **the main name of the table**.

* `sqlFunction` (***String***) - ***Optional.*** The *SQL function* to apply to the column.

> ***NOTE:***
>
> This should be one of the constants defined in the `SqlFuncList` class:
>  * `SqlFuncList::COUNT`,
>  * `SqlFuncList::SUM`,
>  * `SqlFuncList::AVG`,
>  * `SqlFuncList::MIN`,
>  * `SqlFuncList::MAX`.

##### Return value:

* ***Static*** - The `Where` instance, which allows for method chaining.

#### Example

```php
$queryBuilder->select('orders')
             ->all()
             ->where()
               ->equal('*', 10, sqlFunction: SqlFuncList::COUNT)
               ->or()
               ->notEqual('*', 11, sqlFunction: SqlFuncList::COUNT)
             ->end()
             ->getQuery();
```

#### Output

```sql
SELECT products.*
FROM products
WHERE COUNT(*) = 10
OR COUNT(*) <> 10
```

<a name='less'></a>
### Less() or LessOrEqual() [:top:](#methods)

```php
public function less(
    string $column, 
    mixed $value = null, 
    ?string $table = null, 
    ?string $sqlFunction = null
): static;
```
```php
public function lessOrEqual(
    string $column, 
    mixed $value = null, 
    ?string $table = null, 
    ?string $sqlFunction = null
): static;
```

This method adds a `<` or `<=` comparison to the **WHERE** clause.

##### Parameters:

* `column` (***String***) - The *name of the column* to compare.
* `value` (***String***) - ***Optional.*** The *value to compare to*.
* `table` (***String***) - ***Optional.*** The *name of the table* that the column belongs to.

> ***NOTE:*** If `table` ***is not provided***, the method will use **the main name of the table**.

* `sqlFunction` (***String***) - ***Optional.*** The *SQL function* to apply to the column.

> ***NOTE:***
>
> This should be one of the constants defined in the `SqlFuncList` class:
>  * `SqlFuncList::COUNT`,
>  * `SqlFuncList::SUM`,
>  * `SqlFuncList::AVG`,
>  * `SqlFuncList::MIN`,
>  * `SqlFuncList::MAX`.

##### Return value:

* ***Static*** - The `Where` instance, which allows for method chaining.

#### Example

```php
$queryBuilder->select('orders')
             ->all()
             ->where()
               ->less('id', 100)
               ->or()
               ->lessOrEqual('id', 100)
             ->end()
             ->getQuery();
```

#### Output

```sql
SELECT orders.*
FROM orders
WHERE orders.id < 100
OR orders.id <= 100
```

<a name='greater'></a>
### Greater() or GreaterOrEqual() [:top:](#methods)

```php
public function greater(
    string $column, 
    mixed $value = null, 
    ?string $table = null,
    ?string $sqlFunction = null
): static;
```
```php
public function greaterOrEqual(
    string $column, 
    mixed $value = null, 
    ?string $table = null,
    ?string $sqlFunction = null
): static;
```

This method adds a `>` or `>=` comparison to the **WHERE** clause.

##### Parameters:

* `column` (***String***) - The *name of the column* to compare.
* `value` (***String***) - ***Optional.*** The *value to compare to*.
* `table` (***String***) - ***Optional.*** The *name of the table* that the column belongs to.

> ***NOTE:*** If `table` ***is not provided***, the method will use **the main name of the table**.

* `sqlFunction` (***String***) - ***Optional.*** The *SQL function* to apply to the column.

> ***NOTE:***
>
> This should be one of the constants defined in the `SqlFuncList` class:
>  * `SqlFuncList::COUNT`,
>  * `SqlFuncList::SUM`,
>  * `SqlFuncList::AVG`,
>  * `SqlFuncList::MIN`,
>  * `SqlFuncList::MAX`.

##### Return value:

* ***Static*** - The `Where` instance, which allows for method chaining.

#### Example

```php
$queryBuilder->select('orders')
             ->all()
             ->where()
               ->greater('id', 100)
               ->or()
               ->greaterOrEqual('id', 100)
             ->end()
             ->getQuery();
```

#### Output

```sql
SELECT orders.*
FROM orders
WHERE orders.id > 100
OR orders.id >= 100
```

<a name='like'></a>
### Like() or NotLike() [:top:](#methods)

```php
public function like(
    string $column,
    ?string $pattern = null,
    ?string $table = null
): static
```
```php
public function notLike(
    string $column,
    ?string $pattern = null,
    ?string $table = null
): static
```

This method adds `LIKE` or `NOT LIKE` comparison to the **WHERE** clause.

##### Parameters:

* `column` (***String***) - The *name of the column* to compare.
* `pattern` (***String***) - ***Optional.*** The *pattern* to compare to.
* `table` (***String***) - ***Optional.*** The *name of the table* that the column belongs to.

> ***NOTE:*** If `table` ***is not provided***, the method will use **the main name of the table**.

##### Return value:

* ***Static*** - The `Where` instance, which allows for method chaining.

#### Example

```php
$queryBuilder->select('customers')
             ->all()
             ->where()
               ->like('name', '%john%')
               ->or()
               ->notLike('name', '%john%')
             ->end()
             ->getQuery();
```

#### Output

```sql
SELECT customers.*
FROM customers
WHERE customers.name LIKE '%john%'
OR customers.name NOT LIKE '%john%'
```

<a name='null'></a>
### Null() or NotNull() [:top:](#methods)

```php
public function null(string $column, ?string $table = null): static;
```
```php
public function notNull(string $column, ?string $table = null): static;
```

This method adds `NULL` or `NOT NULL` comparison to the **WHERE** clause.

##### Parameters:

* `column` (***String***) - The *name of the column* to compare.
* `table` (***String***) - ***Optional.*** The *name of the table* that the column belongs to.

> ***NOTE:*** If `table` ***is not provided***, the method will use **the main name of the table**.

##### Return value:

* ***Static*** - The `Where` instance, which allows for method chaining.

#### Example

```php
$queryBuilder->delete('users')
             ->where()
               ->null('email')
               ->or()
               ->notNull('email')
             ->end()
             ->getQuery();
```

#### Output

```sql
DELETE FROM users
WHERE users.email IS NULL
OR users.email IS NOT NULL
```

<a name='between'></a>
### Between() or NotBetween() [:top:](#methods)

```php
public function between(
    string $column,
    string|int|null $valueOne = null,
    string|int|null $valueTwo = null,
    ?string $table = null
): static;
```
```php
public function notBetween(
    string $column,
    string|int|null $valueOne = null,
    string|int|null $valueTwo = null,
    ?string $table = null
): static;
```

This method adds `BETWEEN` or `NOT BETWEEN` comparison to the **WHERE** clause.

##### Parameters:

* `column` (***String***) - The *name of the column* to compare.
* `valueOne` (***String | Integer***) - ***Optional.*** The **lower** bound of the range.
* `valueTwo` (***String | Integer***) - ***Optional.*** The **upper** bound of the range.
* `table` (***String***) - ***Optional.*** The *name of the table* that the column belongs to.

> ***NOTE:*** If `table` ***is not provided***, the method will use **the main name of the table**.

##### Return value:

* ***Static*** - The `Where` instance, which allows for method chaining.

#### Example

```php
$queryBuilder->select('sales')
             ->all()
             ->where()
               ->between('sale_amount', 1000, 5000)
               ->or()
               ->notBetween('sale_amount', 1000, 5000)
             ->end()
             ->getQuery();
```

#### Output

```sql
SELECT sales.*
FROM sales
WHERE sales.sale_amount BETWEEN 1000 AND 5000
OR sales.sale_amount NOT BETWEEN 1000 AND 5000
```

<a name='in'></a>
### In() or NotIn() [:top:](#methods)

```php
public function in(
    string $column,
    array|string|null $values = null,
    ?string $table = null
): static;
```
```php
public function notIn(
    string $column,
    array|string|null $values = null,
    ?string $table = null
): static;
```

This method adds `IN` or `NOT IN` comparison to the **WHERE** clause.

##### Parameters:

* `column` (***String***) - The *name of the column* to compare.
* `values` (***Array | String***) - ***Optional.*** The *array of values* (***array***) or *subquery* (***string***) to compare to.
* `table` (***String***) - ***Optional.*** The *name of the table* that the column belongs to.

> ***NOTE:*** If `table` ***is not provided***, the method will use **the main name of the table**.

##### Return value:

* ***Static*** - The `Where` instance, which allows for method chaining.

#### Example

```php
// First
$queryBuilder->select('products')
             ->all()
             ->where()
               ->in('category_id', [1, 2])
             ->end()
             ->getQuery();

// Second
$queryBuilder->select('products')
             ->all()
             ->where()
               ->in('category_id', $queryBuilder->select('categories')->all()->getQuery())
             ->end()
             ->getQuery();
```

#### Output

```sql
-- First
SELECT products.*
FROM products
WHERE products.category_id IN (1, 2)

-- Second
SELECT products.*
FROM products
WHERE products.category_id IN (SELECT categories.* FROM categories)
```

<a name='end'></a>
### End() [:top:](#methods)

```php
public function end(): Delete | Select | Update;
```

This method **ends** the current **WHERE** clause and **returns** the `Delete` or `Select` or `Update` instance.

##### Parameters:

* This method takes no parameters.

##### Return value:

* ***Select | Update | Delect*** - The `Where` instance, which allows for method chaining.
