# Sorter

Для сортировки данных используется объект `Sorter`:

```php
use jugger\data\Sorter;

$sorter = new Sorter([
    'col1' => Sorter::ASC,
    'col3' => Sorter::DESC,
]);
$sorter->set('col2', Sorter::ASC_NAT);
$sorter->set('col4', Sorter::DESC_NAT);

$columns = $sorter->getColumns();
$columns['col1']; // Sorter::ASC
$columns['col2']; // Sorter::ASC_NAT
$columns['col3']; // Sorter::DESC
$columns['col4']; // Sorter::DESC_NAT
```

Объект сортировки только хранит в себе информацию о сортировке, но сам не занимается сортировкой. Реализация сортировки лежит на плечах объекта данных - `DataSet`.
