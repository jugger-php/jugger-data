# DataSet

Набор данных - это сами данные (массив, объект запроса, ...), а также вспомогательные инструменты: пагинация и сортировка.

## Создание набора данных

Для того чтобы создать набор нужно просто создать экземпляр необходимого класса:
```php
use jugger\data\driver\ArrayDataSet;

$data = [
    [
        'id' => 1,
        'name' => 'name1',
        'number' => 123,
    ],
    [
        'id' => 2,
        'name' => 'name2',
        'number' => 456,
    ],
    [
        'id' => 3,
        'name' => 'name3',
        'number' => 789,
    ]
];

$dataset = new ArrayDataSet($data);
```

Использовать набор данных на массиве редко когда нужно (а когда вообще?), поэтому все последующие примеры будут рассматриваться на `QueryDataSet` - набор данных базы данных:

```php
use jugger\data\driver\QueryDataSet;

$query = (new Query($db))->from('test_table');
$dataset = new QueryDataSet($query);
```

## Получение данных

Чтобы получить сформированные данные, необходимо вызвать соответствующий метод:
```php
$rows = $dataset->getData();
```

В переменной `rows` хранятся все строки из исходного запроса.

## Сортировка

Для того, чтобы добавить сортировку к данным, необходимо задать соответсвующий объект:
```php
use jugger\data\Sorter;

$dataset->sorter = new Sorter([
    'id' => Sorter::ASC,
    'name' => Sorter::DESC,(
]);
// отсортированные данные
$rows = $dataset->getData();
```

[Подробнее про Sorter](sorter.md).

## Пагинация

Для разбиения данных на страницы, используется объект `Paginator`:

```php
use jugger\data\Paginator;

$dataset->paginator = new Paginator(10, 2);
// 2-ая страница, по 10 записей
$rows = $dataset->getData();
```

[Подробнее про Paginator](paginator.md).
