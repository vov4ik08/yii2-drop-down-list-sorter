DropDownList Sorter for Yii2
============================
DropDownList Sorter for Yii2

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist i4erkasov/yii2-drop-down-list-sorter "*"
```

or add

```
"i4erkasov/yii2-drop-down-list-sorter": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

Добавляем в наш ActiveDataProvider в sort:
```php
$dataProvider = new yii\data\ActiveDataProvider([
            'query'      => $query,
            'pagination' => [
                'pageSize'      => 32,
                'route'         => '/catalog/',
                'pageSizeParam' => false,
            ],
            'sort'       => new \i4erkasov\dropdownlistsorter\data\Sort([
                'route'        => '/catalog/',
                'unsetParams'  => ['page', '_pjax'],
                'attributes'   => [
                    'index' => [
                        'asc'   => ['sort_index' => SORT_ASC],
                        'desc'  => false,
                        'label' => ['asc' => Yii::t('app', 'default')],
                    ],
                    'name'  => [
                        'asc'   => ['name' => SORT_ASC],
                        'desc'  => false,
                        'label' => ['asc' => Yii::t('app', 'by name')],
                    ],
                    'price' => [
                        'asc'   => ['price' => SORT_ASC],
                        'desc'  => ['price' => SORT_DESC],
                        'label' => [
                            'asc'  => Yii::t('app', 'price asc'),
                            'desc' => Yii::t('app', 'price desc'),
                        ],
                    ],
                ],
                'defaultOrder' => [
                    'index' => SORT_ASC,
                ],
            ]),
        ]);
```

Набор параметров такой же как для стандартного Yii2 Sort 

За изсключение: 

Параметр  defaultOrder является обязательным

и

Добавлен параметр unsetParams для исключения нежелательных GET параметров 
как показано выше 
при формирования сортировочного URL

Пример:
```php
'unsetParams'  => ['page', '_pjax'],
```

В вашем представлении предсталении (view) виджит

Пример php: 
```php
echo DropDownSorter::widget([
    'sort'       => $dataProvider->sort,
    'class'      => 'filter-select__select',
    'onchange'   => '$.pjax.reload({container: "#pjax-catalog", url: $(this).val()})',
    'attributes' => [
        'index',
        'name',
        'price',
    ],
]);
```

Пример с использование шаблонизатора yii2-twig
```php
{{ dropDownSorter_widget({
                        'sort': dataProvider.sort,
                        'options': {
                            'class': 'filter-select__select',
                            'onchange': '$.pjax.reload({container: "#pjax-catalog", url: $(this).val()})',
                        },
                        'attributes': [
                            'index',
                            'name',
                            'price'
                        ]
                    }) | raw }}
```

Оьратите внимание в выше узащынных примерах обработка событи на изменения DropDownList реализована через pjax
```php
'onchange': '$.pjax.reload({container: "#pjax-catalog", url: $(this).val()})',
```