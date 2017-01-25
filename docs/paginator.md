# Paginator

Для хранения информации о пагинации испльзуется объект `Paginator`:

```php
use jugger\data\Paginator;

// 1-ая страница, по 10 записей на странице
$pager = new Paginator(10);

// 3-ая страница, по 10 записей на странице
$pager = new Paginator(10, 3);
```

Объект пагинации, также как и `Sorter`, только хранит в себе инфомацию. Но помимо хранения, объект пагинации также отвечает за получение вспомогательных данных `pageMax` (количество страниц) и `offset` (отступ):

```php
// разбивка по 10 записей на странице
$pager = new Paginator(10);

// меняем общее число записей

$pager->totalCount = 4;
$pager->getPageMax();   // 1

$pager->totalCount = 51;
$pager->getPageMax();   // 6

$pager->totalCount = 100;
$pager->getPageMax();   // 10

// меняем текущую страницу (при общем кол-ве записей 100)

$pager->pageNow = 2;
$pager->getOffset();    // 10

$pager->pageNow = 123;
$pager->getOffset();    // 90

$pager->pageNow = -123;
$pager->getOffset();    // 0
```
