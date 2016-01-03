# mysql-rest-wrapper
This is a small mysql rest wrapper written in php.<br/>
To make it work make sure your tables primary key is defined like -> `id` INT NOT NULL AUTO_INCREMENT<br/>

It accept Resource requests and wrap it into SQL to execute :<br/>
Post + Put request parameters has to be passed inside the RequestBody as JSON data<br/>
:domain = selected database<br/>
<br/>
<br/>
get /:domain/:entity<br/>
-> Select * from :entity<br/>
<br/>
get /:domain/:entity/:id<br/>
-> Select * from :entity where id = :id<br/>
<br/>
get /:domain/:entity/search/:attribute/:value<br/>
->Select * from :entity where :attribute = :value<br/>
<br/>
post /:domain/:entity<br/>
-> Insert into :entity<br/>
<br/>
put /:domain/:entity/:id<br/>
-> Update :entity<br/>
<br/>
delete /:domain/:entity/:id<br/>
-> Delete from :entity  where id = :id<br/>
