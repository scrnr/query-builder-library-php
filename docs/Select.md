
# Select class

The `Select` class provides a fluent *interface* for building and executing **SELECT** queries and the methods for selecting columns, filtering and sorting results, grouping and limiting output, and performing unionswith other **SELECT** queries.

## Methods

It has **nineteen** public methods:

* [`distinct()`](#distinct)
* [`from()`](#from)
* [`resetTable()`](#resetTable)
* [`column()`](#column)
* [`columns()`](#columns)
* [`all()`](#all)
* [`alias()`](#alias)
* [`aliases()`](#aliases)
* [`sqlFunctions()`](#sqlFunctions)
* [`where()`](#where)
* [`having()`](#having)
* [`join()`](#join)
* [`limit()`](#limit)
* [`group()`](#group)
* [`order()`](#order)
* [`union()`](#union)
* [`unionAll()`](#unionAll)
* [`getQuery()`](/docs/QueryBuilder.md#getquery)
* [`getAll()`](/docs/QueryBuilder.md#getall)

<a name='distinct'></a>
### Distinct() [:top:](#methods)

```php
public function distinct(): static;
```

This method specifies that only **distinct** values should be returned in the result set.

##### Parameters:

* This method takes no parameters.

##### Return value:

* ***Static*** - The `Select` instance, which allows for method chaining.

#### Example

```php
$queryBuilder->select('customers')
             ->distinct()
             ->column('country')
             ->getQuery();
```

#### Output

```sql
SELECT DISTINCT customers.country
FROM customers
```

<a name='from'></a>
### From() [:top:](#methods)

```php
public function from(string $tableName): static;
```

This method **changes** the *name of the table* from which the data will be selected.

##### Parameters:

* `tableName` (***String***) - The *name of the table* to to select data from.

##### Return value:

* ***Static*** - The `Select` instance, which allows for method chaining.

#### Example

```php
$queryBuilder->select('orders')
             ->column('id')
             ->from('customers')->column('customer_name')
             ->join('customers')
               ->on('customer_id', 'id')
             ->end()
             ->getQuery();
```

#### Output

```sql
SELECT orders.id, customers.customer_name
FROM orders
INNER JOIN customers
ON orders.customer_id = customers.id
```

<a name='resetTable'></a>
### ResetTable() [:top:](#methods)

```php
public function resetTable(): static;
```

This method **resets** the *name of the table* to **initial one**.

##### Parameters:

* This method takes no parameters.

##### Return value:

* ***Static*** - The `Select` instance, which allows for method chaining.

#### Example

```php
$queryBuilder->select('orders')
             ->column('id')
             ->from('customers')->column('customer_name')
             ->resetTable()
             ->columns('date', 'total_price')
             ->join('customers')
               ->on('customer_id', 'id')
             ->end()
             ->getQuery();
```

#### Output

```sql
SELECT orders.id, customers.customer_name, orders.date, orders.total_price
FROM orders
INNER JOIN customers
ON orders.customer_id = customers.id
```

<a name='column'></a>
### Column() [:top:](#methods)

```php
public function column(string $column): static;
```

This method specifies a **single column** to *select* from the table.

##### Parameters:

* `column` (***String***) - The *name of the column* to select.

##### Return value:

* ***Static*** - The `Select` instance, which allows for method chaining.

#### Example

```php
$queryBuilder->select('orders')
             ->column('id')
             ->getQuery();
```

#### Output

```sql
SELECT orders.id
FROM orders
```

<a name='columns'></a>
### Columns() [:top:](#methods)

```php
public function columns(string ...$columns): static;
```

This method specifies the **columns** to *select* from the table.

##### Parameters:

* `columns` (***String[]***) - ***Variadic.*** The parameter that accepts **one** or **more** *column names* to *select*.

##### Return value:

* ***Static*** - The `Select` instance, which allows for method chaining.

#### Example

```php
$queryBuilder->select('orders')
             ->columns('id', 'total_price', 'date')
             ->getQuery();
```

#### Output

```sql
SELECT orders.id, orders.total_price, orders.date
FROM orders
```

<a name='all'></a>
### All() [:top:](#methods)

```php
public function all(): static;
```

This method selects **all** columns from the table.

##### Parameters:

* This method takes no parameters.

##### Return value:

* ***Static*** - The `Select` instance, which allows for method chaining.

#### Example

```php
$queryBuilder->select('orders')
             ->all()
             ->getQuery();
```

#### Output

```sql
SELECT orders.*
FROM orders
```

<a name='alias'></a>
### Alias() [:top:](#methods)

```php
public function alias(string $column, ?string $alias = null): static;
```

This method allows you to specify an **alias** for a *column* in the `SELECT` statement.

##### Parameters:

* `columns` (***String***) - The *name* of the column to alias.
* `alias` (***String***) - ***Optional.*** The *alias* to use for the column.

> ***NOTE:*** If `alias` ***is not provided***, the method will use the *name of the table* and the *name of the column* as the *alias* (e.g. `table_column`).

##### Return value:

* ***Static*** - The `Select` instance, which allows for method chaining.

#### Example

```php
$queryBuilder->select('customers')
             ->alias('customer_id', 'id')
             ->getQuery();
```

#### Output

```sql
SELECT customers.customer_id AS id FROM customers
```

<a name='aliases'></a>
### Aliases() [:top:](#methods)

```php
public function aliases(array $columns): static;
```

This method allows you to specify **aliases** for *multiple columns* in the `SELECT` statement. You can pass an array where the `keys` are the *column names* and the `values` are the *aliases* (e.g. `['column' => 'alias']`).

##### Parameters:

* `columns` (***Array***) - An *array* where the `keys` are the *column names* and the `values` are the *aliases*.

##### Return value:

* ***Static*** - The `Select` instance, which allows for method chaining.

#### Example

```php
$queryBuilder->select('customers')
             ->aliases(['customer_id' => 'id', 'customer_name' => 'name'])
             ->getQuery();
```

#### Output

```sql
SELECT customers.customer_id AS id, customers.customer_name AS name
FROM customers
```

<a name='sqlFunctions'></a>
### SqlFunctions() [:top:](#methods)

```php
public function sqlFunctions(
    string $functionName,
    string $column,
    ?string $table = null,
    ?string $alias = null
): static;
```

This method adds an **SQL function** to the `SELECT` statement, such as `COUNT`, `AVG`, `MIN`, `MAX` and `SUM`. 

##### Parameters:

* `functionName` (***String***) - The name of the **SQL function** to apply to the column.

> ***NOTE:***
>
> This should be one of the constants defined in the `SqlFuncList` class:
>  * `SqlFuncList::COUNT`,
>  * `SqlFuncList::SUM`,
>  * `SqlFuncList::AVG`,
>  * `SqlFuncList::MIN`,
>  * `SqlFuncList::MAX`.

* `column` (***String***) - The *name of the column* to apply the **SQL function** to.
* `table` (***String***) - ***Optional.*** The *name of the table* where the column resides.

> ***NOTE:*** If `table` ***is not provided***, the method will use **the main name of the table**.

* `alias` (***String***) - ***Optional.*** The *alias* to use for the *column*.

> ***NOTE:*** If `alias` ***is not provided***, it means that the *alias* is not used.

##### Return value:

* ***Static*** - The `Select` instance, which allows for method chaining.

#### Example

```php
// First
$queryBuilder->select('products')
             ->sqlFunctions(SqlFuncList::MIN, 'price', alias: 'smallest_price')
             ->getQuery();

// Second
$queryBuilder->select('products')
             ->sqlFunctions(SqlFuncList::MIN, 'price')
             ->getQuery();
```

#### Output

```sql
-- First
SELECT MIN(products.price) AS smallest_price
FROM products

-- Second 
SELECT MIN(products.price)
FROM products
```

<a name='where'></a>
### Where() [:top:](#methods)

```php
public function where(): Where;
```

This method starts building the **WHERE** clause of the **SELECT** query. It returns an instance of the `Where` class, which can be used to add conditions to the **WHERE** clause.

##### Parameters:

* This method takes no parameters.

##### Return value:

* ***Where*** - The instance of the `Where` class, which starts building the **WHERE** clause of the query.

#### Example

```php
$queryBuilder->select('customers')
             ->all()
             ->where()
               ->equal('country', 'Mexico')
             ->end()
             ->getQuery();
```

#### Output

```sql
SELECT customers.*
FROM customers
WHERE customers.country = 'Mexico'
```

#### For more information about the methods of the `Where` class, click [here](/docs/Where%20And%20Having.md#where-and-having-class)


<a name='having'></a>
### Having() [:top:](#methods)

```php
public function having(): Having;
```

This method starts building the **HAVING** clause of the **SELECT** query. It returns an instance of the `Having` class, which can be used to add conditions to the **HAVING** clause.

##### Parameters:

* This method takes no parameters.

##### Return value:

* ***Having*** - The instance of the `Having` class, which starts building the **HAVING** clause of the query.

#### Example

```php
$queryBuilder->select('customers')
             ->sqlFunctions(SqlFuncList::COUNT, 'id', alias: 'count')
             ->column('country')
             ->group('country')
             ->having()
               ->greater('id', 5, sqlFunction: SqlFuncList::COUNT)
             ->end()
             ->order('id', OrderBy::DESC, sqlFunction: SqlFuncList::COUNT)
             ->getQuery();
```

#### Output

```sql
SELECT COUNT(customers.id) AS count, customers.country
FROM customers
GROUP BY customers.country
HAVING COUNT(customers.id) > 5
ORDER BY COUNT(customers.id) DESC
```

#### For more information about the methods of the `Having` class, click [here](/docs/Where%20And%20Having.md#where-and-having-class)

<a name='join'></a>
### Join() [:top:](#methods)

```php
public function join(string $joinTable, string $type = Join::INNER): Join
```

This method starts building the **JOIN** clause of the **SELECT** query. It takes the *name of the table* and the *type of join* as parameters to this method, and returns an instance of the `Join` class.

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

* ***Join*** - The instance of the `Join` class, which starts building the *JOIN* clause of the query.

#### Example

```php
$queryBuilder->select('orders')
             ->column('id')
             ->from('customers')->column('customer_name')
             ->join('customers')
               ->on('id', 'customer_id')
             ->end()
             ->getQuery();
```

#### Output

```sql
SELECT orders.id, customers.customer_name
FROM orders
INNER JOIN customers
ON orders.customer_id = customers.id
```

#### For more information about the methods of the `Join` class, click [here](/docs/Join.md#join-class)

<a name='limit'></a>
### Limit() [:top:](#methods)

```php
public function limit(
    ?int $quantity = null, 
    ?int $offset = null, 
    bool $needOffset = false
): static;
```

This method *limits the number* of results returned by the `SELECT` statement.

##### Parameters:

* `quantity` (***Integer***) - ***Optional.*** The *number of rows* to return.
* `offset` (***Integer***) - ***Optional.*** The *number of rows* to skip.
* `needOffset` (***Boolean***) - Whether to include the `offset` in the `limit`. **Default - `false`**.

> ***NOTE:***
>
> If `offset` is not **empty**, then `needOffset` is `true`.

##### Return value:

* ***Static*** - The `Select` instance, which allows for method chaining.

#### Example

```php
// First
$queryBuilder->select('products')
             ->all()
             ->limit(needOffset: true)
             ->getAll([20, 50]);

// Second
$queryBuilder->select('products')
             ->all()
             ->limit(10)
             ->getQuery();

// Third
$queryBuilder->select('products')
             ->all()
             ->limit(20, 50)
             ->getQuery();
```

#### Output

```sql
-- First
Array
(
  [0] => SELECT products.* FROM products LIMIT :offset, :limit
  [1] => Array
      (
        [:limit] => 20
        [:offset] => 50
      )
)

-- Second
SELECT products.*
FROM products
LIMIT 10

-- Third
SELECT products.*
FROM products
LIMIT 50, 20
```

<a name='group'></a>
### Group() [:top:](#methods)

```php
public function group(string $column, ?string $table = null): static;
```

This method allows you to **group** the results of the `SELECT` statement by the *column*.

##### Parameters:

* `column` (***String***) - The *name of the column* to group by.
* `table` (***String***) - ***Optional.*** The *name of the table* where the column resides.

> ***NOTE:***
>
> If `table` ***is not provided***, the method will use **the main name of the table**.

##### Return value:

* ***Static*** - The `Select` instance, which allows for method chaining.

#### Example

```php
$queryBuilder->select('orders')
             ->sqlFunctions(SqlFuncList::COUNT, 'id', alias: 'number_of_orders')
             ->from('shippers')->column('name')
             ->join('shippers', Join::LEFT)
               ->on('shipper_id', 'id')
             ->end()
             ->group('name')
             ->getQuery();
```

#### Output

```sql
SELECT COUNT(orders.id) AS number_of_orders, shippers.name
FROM orders
LEFT JOIN shippers
ON orders.shipper_id = shippers.id
GROUP BY shippers.name
```

<a name='order'></a>
### Order() [:top:](#methods)

```php
public function order(
    ?string $column = null,
    string $direction = OrderBy::ASC,
    ?string $table = null,
    ?string $sqlFunction = null
): static;
```

This method adds an `ORDER BY` clause to the `SELECT` query.

##### Parameters:

* `column` (***String***) - ***Optional.*** The *name of the column* to sort by.
* `direction` (***String***) - The *direction* to order the results in. Can be either `ASC` (**ascending**) or `DESC` (**descending**). **Default - `ASC`**.

> ***NOTE:***
>
> This should be one of the constants defined in the `OrderBy` class:
>  * `OrderBy::ASC`,
>  * `OrderBy::DESC`.

* `table` (***String***) - ***Optional.***  The *name of the table* the column belongs to.

> ***NOTE:*** If `table` ***is not provided***, the method will use **the main name of the table**.

* `sqlFunction` (***String***) - ***Optional.*** The name of the **SQL function** to apply to the column.

> ***NOTE:***
>
> This should be one of the constants defined in the `SqlFuncList` class:
>  * `SqlFuncList::COUNT`,
>  * `SqlFuncList::SUM`,
>  * `SqlFuncList::AVG`,
>  * `SqlFuncList::MIN`,
>  * `SqlFuncList::MAX`.

##### Return value:

* ***Static*** - The `Select` instance, which allows for method chaining.

#### Example

```php
// First
$queryBuilder->select('customers')
             ->all()
             ->order('country')
             ->order('name', OrderBy::DESC)
             ->getQuery();

// Second
$queryBuilder->select('customers')
             ->sqlFunctions(SqlFuncList::COUNT, 'id', alias: 'count')
             ->order('id', OrderBy::DESC, sqlFunction: SqlFuncList::COUNT)
             ->getQuery();

// Third
$queryBuilder->select('customers')
             ->all()
             ->order()
             ->getAll(['price']);
```

#### Output

```sql
-- First
SELECT customers.*
FROM customers
ORDER BY customers.country ASC, customers.name DESC

-- Second
SELECT COUNT(customers.id) AS count
FROM customers
ORDER BY COUNT(customers.id) DESC

-- Third
Array
(
  [0] => SELECT products.* FROM products ORDER BY :order ASC
  [1] => Array
      (
        [:order] => price
      )
)
```

<a name='union'></a>
### Union() [:top:](#methods)

```php
public function union(string $tableName): static;
```

This method adds a `UNION` clause to the **SELECT** query, which allows combining the results of **multiple** `SELECT` queries.

##### Parameters:

* `tableName` (***String***) - The *name of the table* to perform the **union** with.

##### Return value:

* ***Static*** - The `Select` instance, which allows for method chaining.

#### Example

```php
$queryBuilder->select('customers')
             ->column('city')
             ->union('suppliers')
             ->column('city')
             ->order('city')
             ->getQuery();
```

#### Output

```sql
SELECT customers.city
FROM customers
UNION
SELECT suppliers.city
FROM suppliers
ORDER BY suppliers.city ASC
```

<a name='unionAll'></a>
### UnionAll() [:top:](#methods)

```php
public function unionAll(string $tableName): static;
```

This method adds a `UNION ALL` clause to the **SELECT** query, which is similar to the `UNION` clause but includes all rows, even if they are duplicates.

##### Parameters:

* `tableName` (***String***) - The *name of the table* to perform the **union all** with.

##### Return value:

* ***Static*** - The `Select` instance, which allows for method chaining.

#### Example

```php
$queryBuilder->select('customers')
             ->column('city')
             ->unionAll('suppliers')
             ->column('city')
             ->order('city')
             ->getQuery();
```

#### Output

```sql
SELECT customers.city
FROM customers
UNION ALL
SELECT suppliers.city
FROM suppliers
ORDER BY suppliers.city ASC
```
