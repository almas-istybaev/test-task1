# test-task1

##Задача:

Вы имеете две сущности: Категория, Товар со следующими полями:

**Category:** 

    id (int) 
    title (string length min: 3, max 12) 
    eId (int|null) //произвольный id из любой другой системы

**Product:**

    id (int)
    categories (связь: ManyToMany)
    title (string length min: 3, max 12)
    price (float range min: 0, max 200)
    eId (int|null) //произвольный id из любой другой системы


Необходимо реализовать консольную команду, которая читает два нижеприведенных файла Json 
и добавляет/обновляет записи в БД

1. файл categories.json имеет не более 100 строк
2. файл products.json имеет ~ 3млн. строк
3. учесть валидацию данных

_**categories.json:**_

    [
        { "eId": 1, "title": "Category 1"},
        { "eId": 2,"title": "Category 2"},
        { "eId": 2,"title": "Category 33333333"},
        ... + ~ 100 rows
    ]

_**products.json:**_

    [
        {"eId": 1, "title": "Product 1", "price": 101.01, "categoriesEId": [1,2]},
        {"eId": 2, "title": "Product 2", "price": 199.01, "categoryEId": [2,3]},
        {"eId": 3, "title": "Product 33333333", "price": 999.01, "categoryEId": [3,1]},
        ... + ~ 3'000'000 rows
    ]
