# Insert class

The `Insert` class provides a fluent *interface* for building and executing **INSERT** queries. It allows you to specify the table to insert into and the values to insert for each column.


## Methods

It has **six** public methods:

* [`columns()`](#columns)
* [`values()`](#values)
* [`prepareValues()`](#preparevalues)
* [`duplicateKey()`](#duplicatekey)
* [`getQuery()`](/docs/QueryBuilder.md#getquery)
* [`getAll()`](/docs/QueryBuilder.md#getall)

<a name='columns'></a>
### Columns() [:top:](#methods)

```php
public function columns(string ...$columns): static;
```

This method allows you to specify the *columns* that you want to *insert* data into. You can pass **any** number of *column names* as parameters to this method.

##### Parameters:

* `columns` (***String[]***) - ***Variadic.*** The parameter that accepts one or more strings representing the *column names* to *insert* data into.

##### Return value:

* ***Static*** - The `Insert` instance, which allows for method chaining.

<a name='values'></a>
### Values() [:top:](#methods)

```php
public function values(array $values): static;
```

This method allows you to specify the *values* that you want to *insert* into the *columns* that you previously specified using the [`columns()`](#columns) method.

##### Parameters:

* `values` (***Array***) - The *array of values* to *insert* into the *columns* that were previously specified using the [`columns()`](#columns) method.

##### Return value:

* ***Static*** - The `Insert` instance, which allows for method chaining.

#### Example

```php
$queryBuilder->insert('users')
             ->columns('first_name', 'last_name', 'age', 'city', 'email')
             ->values(['Eric', 'Smith', 20, 'NY', 'example@email.com'])
             ->getAll();
```

#### Output

```php
Array
(
  [0] => INSERT INTO users (users.first_name, users.last_name, users.age, users.city, users.email) VALUES (:users_first_name,:users_last_name,:users_age,:users_city,:users_email)
  [1] => Array
      (
        [:users_first_name] => Eric
        [:users_last_name] => Smith
        [:users_age] => 20
        [:users_city] => NY
        [:users_email] => example@email.com
      )
)
```

<a name='preparevalues'></a>
### PrepareValues() [:top:](#methods)

```php
public function prepareValues(int $times = 1): static;
```

This method *prepares the values* that you want to *insert* into the database by adding **placeholders** to the *query* as **many times** as specified in the `$times` parameter.

> ***NOTE:*** The method is based on the *name of the columns* that were specified in the [`columns()`](#columns) method.

##### Parameters:

* `times` (***Integer***) - ***Optional.*** The parameter that specifies how *many* to the **placeholder values** should be duplicated. ***Default - `1`***.

##### Return value:

* ***Static*** - The `Insert` instance, which allows for method chaining.

#### Example

```php
$queryBuilder->insert('users')
             ->columns('first_name', 'last_name', 'age', 'city', 'email')
             ->prepareValues(2)
             ->getAll([
                'Eric', 'Smith', 20, 'NY', 'example@email.com',
                'Eugene', 'Armstrong', 25, 'LA', 'example@gmail.com'
              ]);
```

#### Output

```php
Array
(
  [0] => INSERT INTO users (users.first_name, users.last_name, users.age, users.city, users.email) VALUES (:users_first_name, :users_last_name, :users_age, :users_city, :users_email), (:users_first_name2, :users_last_name2, :users_age2, :users_city2, :users_email2)
  [1] => Array
      (
        [:users_first_name] => Eric
        [:users_last_name] => Smith
        [:users_age] => 20
        [:users_city] => NY
        [:users_email] => example@email.com
        [:users_first_name2] => Eugene
        [:users_last_name2] => Armstrong
        [:users_age2] => 25
        [:users_city2] => LA
        [:users_email2] => example@gmail.com
      )
)
```

<a name='duplicatekey'></a>
### DuplicateKey() [:top:](#methods)

```php
public function duplicateKey(string|array $columns, string|array $values): static;
```

This method allows you to specify what should happen if a *duplicate key* is encountered during the insertion process.

##### Parameters:

* `columns` (***String | String[]***) - A *string* or an *array of strings* representing the **column names** that should be updated if a *duplicate key* is encountered.
* `values` (***String | String[]***) - A *string* or an *array of strings* representing the **values** that should be used to update the columns if a *duplicate key* is encountered.

##### Return value:

* ***Static*** - The `Insert` instance, which allows for method chaining.

> ***NOTE:*** If the `columns` parameter is `string` then the `values` parameter must be `string` too. If the `columns` parameter is `array` then the `values` parameter must be `array` too **the same length**.

#### Example

```php
$queryBuilder->insert('statistics')
             ->columns('date', 'views')
             ->prepareValues()
             ->dublicateKey('views', 'views + 1')
             ->getAll(['2023-04-20', 1]);
```

#### Output

```php
Array
(
  [0] => INSERT INTO statistics (statistics.date, statistics.views) VALUES (:statistics_date, :statistics_views) ON DUPLICATE KEY UPDATE statistics.views = "views + 1"
  [1] => Array
      (
        [:statistics_date] => 2023-04-20
        [:statistics_views] => 1
      )
)
```
