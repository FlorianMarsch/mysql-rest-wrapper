# mysql-rest-wrapper
This is a small mysql rest wrapper written in php.
To make it work make sure your tables primary key is defined like -> `id` INT NOT NULL AUTO_INCREMENT

It accept Resource requests and wrap it into SQL to execute :
Post + Put request parameters has to be passed inside the RequestBody as JSON data
:domain = selected database


get /:domain/:entity
-> Select * from :entity

get /:domain/:entity/:id
-> Select * from :entity where id = :id

get /:domain/:entity/search/:attribute/:value
->Select * from :entity where :attribute = :value

post /:domain/:entity
-> Insert into :entity

put /:domain/:entity/:id
-> Update :entity

delete /:domain/:entity/:id
-> Delete from :entity  where id = :id
