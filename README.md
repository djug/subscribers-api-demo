
Subscribers API (demo)
---
This is a demo about how a subscribers API (for a newsletter for instance) could be implemented.
This demo allows managing subscribers (**C**reate, **R**ead, **U**pdate and **D**elete) with or without additional fields. each subscriber can have 0 or multiple fields, but these fields and their types should be created by the user (the owner of the newsletter) beforehand, otherwise any *non accepted* field will be ignored.
In addition, this API allows searching/updating/deleting subscribers either by their ID or by their email addresses.

## How to install

   - Clone this repo: `git clone https://github.com/djug/subscribers-api-demo.git`
   - `cd subscribers-api-demo/`
   - Install the dependencies: `composer install`
   - Create a new database and create/update your `.env` file accordingly
   -  run the migrations `php artisan migrate`
   - generate an encryption  key `php artisan key:generate`
   - Lunch the built-in PHP webserver `php artisan serve`
   - Visit http://127.0.0.1:8000/ to see the demo homepage
   
## How to get the API Key
- create a new account and login
- you'll get redirected to the dashboard page that contains your API key

![enter image description here](http://youghourta.com/wp-content/uploads/2018/04/api-key.png)


- Use this API Key to send the request to the API (next section)

## How to test
### Create a field
```
curl -X POST http://127.0.0.1:8000/api/fields/ -d '{"title": "job", "type" : "string"}' -H "Content-Type: application/json" -H "X-MailerLite-ApiKey: yYsZaEJOgm7E0zK9VpbUZV87wokj82o3wJBND1LmhgZI3axsyrIxsiCz3ics"
```
Result:
``` 
{"title":"job","type":"string","updated_at":"2018-04-03 17:16:21","created_at":"2018-04-03 17:16:21","id":1}
```

### Update a field
```
curl -X PUT http://127.0.0.1:8000/api/fields/1 -d '{"title": "Job Title", "type" : "string"}' -H "Content-Type: application/json" -H "X-MailerLite-ApiKey: yYsZaEJOgm7E0zK9VpbUZV87wokj82o3wJBND1LmhgZI3axsyrIxsiCz3ics"
```
Result:
```
{"id":1,"title":"Job Title","type":"string","created_at":"2018-04-03 17:16:21","updated_at":"2018-04-03 17:18:02"}
```


### Create a new subscriber
```
curl -X POST http://127.0.0.1:8000/api/subscribers -d '{"email":"contact@youghourta.com", "name": "Youghourta", "fields": {"Job Title":"developer", "company": "djugprog"}}' -H "Content-Type: application/json" -H "X-MailerLite-ApiKey: yYsZaEJOgm7E0zK9VpbUZV87wokj82o3wJBND1LmhgZI3axsyrIxsiCz3ics"
```

Result:
```
{"email":"contact@youghourta.com","name":"Youghourta","updated_at":"2018-04-03 17:20:03","created_at":"2018-04-03 17:20:0  
3","id":1,"fields":[{"title":"Job Title","value":"developer","type":"STRING"}]}
```
Notice that the `company` field was ignored, since it is not an "accepted" field (i.e it wasn't added to the list of accepted fields)

### Update an existing subscriber:
```
curl -X PUT http://127.0.0.1:8000/api/subscribers/1 -d '{"state":"unsubscribed"}' -H "Content-Type: application/json" -H "X-MailerLite-ApiKey: yYsZaEJOgm7E0zK9VpbUZV87wokj82o3wJBND1LmhgZI3axsyrIxsiCz3ics"
```
we can also use the email address instead of the ID in the previous request
```
curl -X PUT http://127.0.0.1:8000/api/subscribers/contact@youghourta.com -d '{"state":"unsubscribed"}' -H "Content-Type: application/json" -H "X-MailerLite-ApiKey: yYsZaEJOgm7E0zK9VpbUZV87wokj82o3wJBND1LmhgZI3axsyrIxsiCz3ics"
```

result:
```
{"id":1,"email":"contact@youghourta.com","name":"Youghourta","state":"unsubscribed","created_at":"2018-04-03 17:20:03","u  
pdated_at":"2018-04-03 17:25:36","fields":[{"title":"Job Title","value":"developer","type":"STRING"}]}
```
we can also update the fields:
```
curl -X PUT http://127.0.0.1:8000/api/subscribers/1 -d '{"fields": {"Job Title":"Back-end Developer"}}' -H "Content-Type: application/json" -H "X-MailerLite-ApiKey: yYsZaEJOgm7E0zK9VpbUZV87wokj82o3wJBND1LmhgZI3axsyrIxsiCz3ics"
```

result:
```
{"id":1,"email":"contact@youghourta.com","name":"Youghourta","state":"unsubscribed","created_at":"2018-04-03 17:20:03","u  
pdated_at":"2018-04-03 17:25:36","fields":[{"title":"Job Title","value":"Back-end Developer","type":"STRING"}]}
```

# Test
this project includes some tests that you can run locally.
in order to run the tests we need the following steps:
- Create a new testing database (let's name it `test_api_db`)
-  run the migration on it `DB_DATABASE=test_api_db php  artisan migrate` (we are passing the name of the test DB as an environment variable to override temporarily the   `DB_DATABASE` environment variable)
- Update the `phpunit.xml`
    we need to add the following lines to the `phpunit.xml` file between ` <php>` and ` </php>`  tags, so we can run tests using our test database:
    ```
     <env name="DB_DATABASE" value="mailer_test"/>
        <env name="DB_USERNAME" value="bdd"/>
        <env name="DB_PASSWORD" value="123"/>
        <env name="DB_PERSISTENT" value="false"/>
    ```
- run `vendor/bin/phpunit`

## To do
- validate / cast the fields before adding them (useful especially for `date` fields)
- write an SDK and its documentation
