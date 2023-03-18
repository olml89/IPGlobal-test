<p align="center"><a href="https://www.ipglobal.es" target="_blank"><img src="https://www.ipglobal.es/wp-content/uploads/2017/12/logo-ipg-techhub.png" width="400" alt="Ipglobal : Tech Hub"></a></p>

<p align="center">
<a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/License-MIT-green.svg" alt="License"></a>
<a href="https://github.com/olml89/IPGlobal-test"><img src="https://github.com/olml89/IPGlobal-test/actions/workflows/build.yml/badge.svg" alt="Build status"></a>
<a href="https://codecov.io/gh/olml89/IPGlobal-test"><img src="https://codecov.io/gh/olml89/IPGlobal-test/branch/master/graph/badge.svg?token=SL6ANXRH0A" alt="Coverage status"></a>
</p>

# Requirements

Implementation using **[Laravel 10](https://github.com/laravel/framework)** of a Blog application prototype with two functionalities:

- Page listing the existing posts
- Individual post page, showing the post information and a brief sheet about the author

The blog has to expose a public API with two endpoints:

- **GET /posts/{:postId}** to get the information of a post, including the author's data
- **POST /posts** to publish new posts

You can consult the full specifications in spanish in the [original document](https://github.com/olml89/IPGlobal-test/blob/master/docs/specifications.pdf).

# Development phases

## Phase 1: Prototype

On this early stage I installed **Laravel 10**, organized the application to follow a DDD directory structure and 
developed a prototype with two mock functionalities, post publishing and post retrieval. The post publishing
one only showed a 201 response after validating the input data, and the retrieving feature got the data from the
remote JsonPlaceholderApi.

Steps:

- Validate the input when publishing a post (verifying no parameters were missing
and the user_id represented a valid auto-incremental id).

- Handle the errors when trying to get a post that didn't exist on JsonApiPlaceholder.

- Manipulate the JsonApiPlaceholder response to include the user information inside the post response
instead of simply showing the user_id field.

- As the post entity changes to allocate a user entity, mock some user data when publishing a post 
and display it as it was the post's author information.

## Phase 2: Persistence

The next step was to implement persistence, so I installed **Doctrine 3** as I prefer it to Eloquent.
I also installed **Doctrine Migrations 3.6** in order to generate the database schema automatically from 
my entity mappings, but I had to develop a thin wrapper around it to be able to run its commands from artisan
and use the already configured connection in the application. 

On this stage I decided to change the auto-incremental entities id for a UUID as it is a strategy more decoupled
from the persistence infrastructure.

Steps:

- Refactor the post creation feature to persist the information in the database (still with a mocked user).

- Implement a feature to register users through a console command (at first specifying all its parameters, later 
on develop another command that only asks for email and password and mocks random dummy data for the rest
of the parameters).

- Refactor the post retrieval feature to get it from the database (with the mocked user attached).

Even I had no more use for the JsonPlaceholderApi post retriever at this point, I still left it in a separate
use case as a proof of concept (even I had to do some adjustments to it, as the format of their ids and ours
is incompatible now).

## Phase 3: Authentication

With the post creation and retrieval features working, the only thing that's left regarding the API is to 
provide a mechanism to publish a post with its real author information instead of a mocked user one's. So
what I've done is developing a very simple authentication process where the user credentials are checked
against an endpoint that returns an Api token valid for an hour if they are correct.

Using this token in a header, we can check which user is performing the request to publish a post and then
attach it automatically to the created post.

Steps:

- Implement a feature to check the user credentials and generate an Api token.

- Refactor the post publishing feature to need a valid Api token in order to perform the action.

# How to use it

First you have to install the application with its dependencies:

````php
composer install
````

Then you will have to generate the database schema:

````php
php artisan doctrine:migrations:migrate
````

After that you can start creating new users:

````php
php artisan user:create
        {password}
        {name}
        {username}
        {email}
        {address_street}
        {address_suite}
        {address_city}
        {address_zipcode}
        {address_geo_lat}
        {address_geo_lng}
        {phone}
        {website}
        {company_name}
        {company_catchphrase}
        {company_bs}
````

Or if you prefer to skip specifying all the parameters and to create a random
user setting only **email** and **password**:

````php
php artisan user:create {email} {password}
````

Using the credentials of the user, now you can get an Api authentication token:

```http
   ### Expect code 200 and an authentication token
   POST /api/auth
   Content-Type: application/json
   
   Body
    {
        "email": "created.user@test.com",
        "password": "12345" 
    }
   
   Response
    {
        "user_id": "a114298d-14c9-3f06-bd51-282d1e985c6e",
        "token": "2437d1dc0ea2921dcd1a0a27fcf0debb",
        "expires_at": "2023-03-16 17:42:14"
    }
```

This allows you to create new posts:

```http
   ### Expect code 201 and info about the created post
   POST /api/posts
   Content-Type: application/json
   Api-Token: 2437d1dc0ea2921dcd1a0a27fcf0debb
   
   Body
    {
        "title": "This is a random post",
        "body": ""Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua." 
    }
   
   Response
    {
        "id": "85e9ff5a-b601-4e4c-9946-d1050d1c1c89",
        "user": {
            "id": "a114298d-14c9-3f06-bd51-282d1e985c6e",
            "name": "Mara Stark",
            "username": "Teagan Sipes",
            "email": "johndeere@gmail.com",
            "address": {
                "street": "5355 Schuster Stream",
                "suite": "Josefina Lowe V",
                "city": "Camrenside",
                "zipCode": "20982",
                "geoLocation": {
                    "latitude": -49.310189,
                    "longitude": 155.665459
                }
            },
            "phone": "1-574-438-9850",
            "website": "http://moen.com/",
            "company": {
                "name": "Miss Gia Stamm",
                "catchphrase": "Dolores sed quia ipsum voluptas dignissimos et quisquam.",
                "bs": "Ab fugit rem explicabo eos porro eius."
            }
        },
        "title": "This is a random post",
        "body": "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
        "posted_at": "2023-03-18T20:24:44+00:00"
    }
```

You can also retrieve published posts (no matter who the author is):

```http
   ### Expect code 200 and info about the requested user
   POST /api/posts/85e9ff5a-b601-4e4c-9946-d1050d1c1c89
   Content-Type: application/json
   Api-Token: 2437d1dc0ea2921dcd1a0a27fcf0debb
   
   Response
    {
        "id": "85e9ff5a-b601-4e4c-9946-d1050d1c1c89",
        "user": {
            "id": "a114298d-14c9-3f06-bd51-282d1e985c6e",
            "name": "Mara Stark",
            "username": "Teagan Sipes",
            "email": "johndeere@gmail.com",
            "address": {
                "street": "5355 Schuster Stream",
                "suite": "Josefina Lowe V",
                "city": "Camrenside",
                "zipCode": "20982",
                "geoLocation": {
                    "latitude": -49.310189,
                    "longitude": 155.665459
                }
            },
            "phone": "1-574-438-9850",
            "website": "http://moen.com/",
            "company": {
                "name": "Miss Gia Stamm",
                "catchphrase": "Dolores sed quia ipsum voluptas dignissimos et quisquam.",
                "bs": "Ab fugit rem explicabo eos porro eius."
            }
        },
        "title": "This is a random post",
        "body": "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
        "posted_at": "2023-03-18T20:24:44+00:00"
    }
```

As a legacy feature, you can still get remote JsonPlaceholderApi posts, but the id's of the 
post and the user and the posted_at field will be mocked.

You will see the next example matches with [JsonPlaceholderApi resource](https://jsonplaceholder.typicode.com/posts/12)
and that the user information corresponds to You will see the next example matches with [JsonPlaceholderApi resource](https://jsonplaceholder.typicode.com/users/2):

```http
   ### Expect code 200 and info about the requested user
   POST /api/posts/12
   Content-Type: application/json
   Api-Token: 2437d1dc0ea2921dcd1a0a27fcf0debb
   
   Response
    {
        "id": "88e0e8c7-74f0-48d9-9199-1339a98c035d",
        "user": {
            "id": "d2d3ff36-b0cc-44c3-bdef-2a8e9befd42f",
            "name": "Ervin Howell",
            "username": "Antonette",
            "email": "Shanna@melissa.tv",
            "address": {
                "street": "Victor Plains",
                "suite": "Suite 879",
                "city": "Wisokyburgh",
                "zipCode": "90566-7771",
                "geoLocation": {
                    "latitude": -43.9509,
                    "longitude": -34.4618
                }
            },
            "phone": "010-692-6593 x09125",
            "website": "https://anastasia.net",
            "company": {
                "name": "Deckow-Crist",
                "catchphrase": "Proactive didactic contingency",
                "bs": "synergize scalable supply-chains"
            }
        },
        "title": "in quibusdam tempore odit est dolorem",
        "body": "itaque id aut magnam\npraesentium quia et ea odit et ea voluptas et\nsapiente quia nihil amet occaecati quia id voluptatem\nincidunt ea est distinctio odio",
        "posted_at": "2023-03-18T20:33:30+00:00"
    }
```


