Lara-role
================================================

[![Build Status](https://travis-ci.org/svilborg/lara-role.svg?branch=master)](https://travis-ci.org/svilborg/lara-role)

Role and/or Resource base ACL implementation

## Installation

Clone the repo and use Role/Resource middlewares.
See also User model & migrations for the required user fields.
Roles and/or Resources are defined in database.

## Usage

For role based only ACL

```

    Route::get('/items', [
        'uses' => 'ItemsController@index',
        'middleware' => [
            Role::class
        ]
        'acl' => [
            'role' => [
                'admin',
                'viewer',
                'user'
            ]
        ]
    ]);


    Route::get('/items', [
        'uses' => 'ItemsController@create',
        'middleware' => [
            Role::class
        ]
        'acl' => [
            'role' => [
                'admin'
            ]
        ]
    ]);
```


For resource based ACL

```

    Route::get('/items', [
        'uses' => 'ItemsController@index',
        'middleware' => [
            Resource::class
        ]
        'acl' => []
    ]);



```

