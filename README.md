

## Installation

git clone https://github.com/sstankevicius/address-book.git

Change env.example to .env

docker-compose up -d

Composer install

docker-compose exec app php artisan migrate

docker-compose exec app php artisan passport:install

## Tests

docker-compose exec app vendor/bin/phpunit tests/Feature/ContactsTest.php

docker-compose exec app vendor/bin/phpunit tests/Unit/ContactTest.php


## API

#### Registration

POST /api/register

requires
{
“name”, “email”, “password”
}

returns Bearer Token

#### Login

POST /api/login

requires
{
"email","password"
}

#### Get all contacts

GET /api/contacts

returns 
yourContacts[] = User contacts
sharedContacts[] = Contacts shared by other users
sharing[] = Contacts which user shares with other users

#### Post contact

POST /api/contacts

required
{
"name","phone"
}

if success returns
created contact

#### Get 1 contact

GET /api/contacts/{id}

returns contact

#### Update contact

PUT /api/contacts/{id}

required
{
"name","phone"
}

returns success message

#### Delete contact

DELETE /api/contacts/{id}

returns success

#### Share contact

/api/share

requires
{
"contact","user"
}
"contact" = contact_id
"user" = user_id

Validation: cannot share with same person twice, cannot share with himself, checks if user has that contact, checks if user exists

returns
if success true otherwise error message

#### Stop sharing contact

/api/stopShare

requires
{
"contact","user"
}
"contact" = contact_id
"user" = user_id

Validation: checks if user wants to stop sharing with shared contact_user, checks if user has that contact and it's shared, checks if user exists

returns message and which contact was stopped sharing


## Improvements

Move share and stopShare logic to the model or new Service class, logic in the controller is not the best practise.

Better validation for phone number

API GET /contacts returns: user contacts, what contacts are being shared and which contacts user is sharing. All these could have separate calls.

