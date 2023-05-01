# Delete class

The `Delete` class provides a fluent *interface* for building and executing **DELETE** queries. It allows you to specify the table to delete from and add conditions to the *WHERE* clause.

## Methods

It has **three** public methods:

* [`where()`](#where)
* [`getQuery()`](/docs/QueryBuilder.md#getquery)
* [`getAll()`](/docs/QueryBuilder.md#getall)

<a name='where'></a>
### Where() [:top:](#methods)

```php
public function where(): Where;
```

This method starts building the *WHERE* clause of the **DELETE** query. It returns an instance of the `Where` class, which can be used to add conditions to the *WHERE* clause.

##### Parameters:

* This method takes no parameters.

##### Return value:

* ***Where*** - The instance of the `Where` class, which starts building the *WHERE* clause of the query.

#### Example

```php
$queryBuilder->delete('users')->where()->equal('id', 5)->end()->getQuery();
```

#### Output

```sql
DELETE FROM users WHERE users.id = 5
```

#### For more information about the methods of the `Where` class, click [here](/docs/Where%20And%20Having.md#where-and-having-class)
