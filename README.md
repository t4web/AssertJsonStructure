AssertJsonStructure
===================

Codeception assert for checking REST API JSON response

Problem
------------
You have REST API method /users/api/users with responce:
```json
[
    {
        "user_id": 1,
        "surname": "Rabnovich",
        "name": "Nikolay",
        "patronymic": "Yur'evich",
        "username": "ryabina",
        "country_id": 153,
        "language_id": "ru",
        "birth_date": "1991-12-31",
        "email_main": "ryabina@hotmail.com",
        "gender_id": 1,
        "invitation_code": null,
        "created_date": "2014-12-31 12:09:48",
        "created_by": null,
        "changed_by": null,
        "changed_date": "2014-12-31 12:09:48",
        "is_approved": true
    },
    {
        "user_id": 2,
        "surname": "Konstantinova",
        "name": "Katerina",
        "patronymic": "Olegovna",
        "username": "katya",
        "country_id": 3,
        "language_id": "ru",
        "birth_date": "1994-11-01",
        "email_main": "pussy.k@yahoo.ua",
        "gender_id": 2,
        "invitation_code": null,
        "created_date": "2014-12-31 12:08:35",
        "created_by": null,
        "changed_by": null,
        "changed_date": "2014-12-31 12:08:35",
        "is_approved": true
    },
    ...
]
```
We need check JSON structure.

I want
------------
Check JSON structure easy, somting like this:
```php
$I->wantTo('Check GET /users/api/users');

$I->haveHttpHeader('Accept', 'application/json');

$I->sendGET('/users/api/users');

$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();

$response = json_decode($I->grabResponse(), true);

$I->assertJsonStructure(
'{
    "user_id": <integer>,
    "surname": <string|null>,
    "name": <string>,
    "username": <string>,
    "email_main": <string>,
    "location": <location>,
}',
$response);
```

Solution
------------
1. Download, install and initialize Codeception (http://codeception.com/).
2. Add FunctionalHelper.php (https://github.com/t4web/AssertJsonStructure/blob/master/tests/_support/FunctionalHelper.php) in your tests/_support/

  Now you can use `assertJsonStructure()` function for checking JSON structure. Supported types: `boolean`, `integer`,   `float`, `double`, `string`, `array`, `NULL`, `null` and custom type `dateTime`. You can add any complex types.

3. Write Functional test like this https://github.com/t4web/AssertJsonStructure/blob/master/functional/UsersCest.php

Advanced usage
------------
You must create you own custom types, for make you tests more easier. Example:
You have REST API method /users/api/users with responce:
```json
[
    {
        "user_id": 1,
        "surname": "Rabnovich",
        "name": "Nikolay",
        "username": "ryabina",
        "email_main": "ryabina@hotmail.com",
        "location": 
        {
          "country_id": 153,
          "city_id": 1434
        }
    },
    {
        "user_id": 2,
        "surname": "Konstantinova",
        "name": "Katerina",
        "username": "katya",
        "email_main": "pussy.k@yahoo.ua",
        "location": 
        {
          "country_id": 153,
          "city_id": 1434
        }
    },
    ...
]
```
You can create custom type `location` and check it anywhere the same way:
```php
$this->I->assertJsonStructure(
'{
    "user_id": <integer>,
    "surname": <string|null>,
    "name": <string>,
    "username": <string>,
    "email_main": <string>,
    "location": <location>,
}',
$user);
```
