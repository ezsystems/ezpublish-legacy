/*
permission scheme and example

Anonimous

Content:Sitemap: ClassID in (2), ObjectID in (1,3,17,45,46)
Search:Search: ClassID in (2), ObjectID in (1,3,17,45,46)

Admin

Class:*
Content:*
Search:*



editor

Class:List:*
Class:Edit:*
Content:Sitemap:*
Content:Delete:*
Content:Edit:*
Content:View:*
Search:*

'advanced editor'

Class:List:*
Class:Edit:*
Class:Delete:*
Content:*
Search:*



select ezrole.id 

from 

    ezrole, 
    ezuser_role,
    ezmodule,
    ezmodule_policy,
    ezfunction,
    ezfunction_policy,
    ezfunction_policy_set,
    ezpolicy_set_value

where 
    ezuser_role.contentobject_id = 53 AND
    ezrole.id =  ezuser_role.role_id AND
    ezmodule.name = 'class' AND
    (	( ezmodule_policy.module_id = ezmodule.id AND 
	ezmodule_policy.role_id = ezrole.id AND 
	ezmodule_policy.value = '*' ) 
    OR	( ezfunction.name = 'edit' AND
          ezfunction.module_id = ezmodule.id AND
          ezfunction_policy.function_id = ezfunction.id AND
          ezfunction_policy.role_id = ezrole.id  AND   
          ezfunction_policy.value = '*' 
	   ) 
    OR	( ezfunction.name = 'edit' AND
          ezfunction.module_id = ezmodule.id AND
          ezfunction_policy.function_id = ezfunction.id AND
          ezfunction_policy.role_id = ezrole.id  AND   
          ezfunction_policy_set.function_policy_id = ezfunction_policy.id AND
	  ezfunction_policy_set.name = 'ClassID' AND
          ezpolicy_set_value.set_id = ezfunction_policy_set.id AND
          ezpolicy_set_value.value = '2'
     ));  


--------------------------------------------------------------------------------------------------------------------------------------
**************************************************************************************************************************************
--------------------------------------------------------------------------------------------------------------------------------------
select ezrole.id 
from 
    ezrole, 
    ezuser_role,
where 
    ezuser_role.contentobject_id = 50 AND
    ezrole.id =  ezuser_role.role_id AND
    ezrole.value = '*';


--------------------------------------------------------------------------------------------------------------------------------------
**************************************************************************************************************************************
--------------------------------------------------------------------------------------------------------------------------------------

select ezrole.id 
from 
    ezrole, 
    ezuser_role,
    ezmodule,
    ezmodule_policy
where 
    ezuser_role.contentobject_id = 50 AND
    ezrole.id =  ezuser_role.role_id AND
    ezmodule.name = 'class' AND
    ezmodule_policy.module_id = ezmodule.id AND 
    ezmodule_policy.role_id = ezrole.id AND 
    ezmodule_policy.value = '*'; 

--------------------------------------------------------------------------------------------------------------------------------------

select ezrole.id 
from 
    ezrole, 
    ezuser_role,
    ezmodule_policy
where 
    ezuser_role.contentobject_id = 50 AND
    ezrole.id =  ezuser_role.role_id AND
    ezmodule_policy.module_name = 'class' AND 
    ezmodule_policy.role_id = ezrole.id AND 
    ezmodule_policy.value = '*'; 





--------------------------------------------------------------------------------------------------------------------------------------
**************************************************************************************************************************************
--------------------------------------------------------------------------------------------------------------------------------------

select ezrole.id,ezrole.name, ezuser_role.id, ezmodule.name, ezmodule.id, ezfunction.name, ezfunction.id, ezfunction_policy.role_id,ezfunction_policy.value  
from 
    ezrole, 
    ezuser_role,
    ezmodule,
    ezfunction,
    ezfunction_policy
where
    ezuser_role.contentobject_id = 53 AND
    ezrole.id =  ezuser_role.role_id AND
    ezmodule.name = 'class' AND
    ezfunction.name = 'edit' AND
    ezfunction.module_id = ezmodule.id AND
    ezfunction_policy.function_id = ezfunction.id AND
    ezfunction_policy.role_id = ezrole.id  AND   
    ezfunction_policy.value = '*' ;

--------------------------------------------------------------------------------------------------------------------------------------

select ezrole.id,ezrole.name, ezuser_role.id,  ezfunction_policy.role_id,ezfunction_policy.value  
from 
    ezrole, 
    ezuser_role,
    ezfunction_policy
where
    ezuser_role.contentobject_id = 53 AND
    ezrole.id =  ezuser_role.role_id AND
    ezfunction_policy.module_name = 'class' AND
    ezfunction_policy.function_name = 'edit' AND
    ezfunction_policy.role_id = ezrole.id  AND   
    ezfunction_policy.value = '*' ;




--------------------------------------------------------------------------------------------------------------------------------------
**************************************************************************************************************************************
--------------------------------------------------------------------------------------------------------------------------------------
	 
select ezrole.id 
from 
    ezrole, 
    ezuser_role,
    ezmodule,
    ezfunction,
    ezfunction_policy,
    ezfunction_policy_set,
    ezpolicy_set_value
where 
    ezuser_role.contentobject_id = 49 AND
    ezrole.id =  ezuser_role.role_id AND
    ezmodule.name = 'content' AND
    ezfunction.name = 'sitemap' AND
    ezfunction.module_id = ezmodule.id AND
    ezfunction_policy.function_id = ezfunction.id AND
    ezfunction_policy.role_id = ezrole.id  AND   
    ezfunction_policy_set.function_policy_id = ezfunction_policy.id AND
    ezfunction_policy_set.name = 'ClassID' AND
    ezpolicy_set_value.set_id = ezfunction_policy_set.id AND
    ezpolicy_set_value.value = '2';  

--------------------------------------------------------------------------------------------------------------------------------------

select ezrole.id 
from 
    ezrole, 
    ezuser_role,
    ezfunction_policy_set,
    ezpolicy_set_value
where 
    ezuser_role.contentobject_id = 49 AND
    ezrole.id =  ezuser_role.role_id AND
    ezfunction_policy_set.module_name = 'content' AND
    ezfunction_policy_set.function_name = 'sitemap' AND
    ezfunction_policy_set.role_id = ezrole.id  AND   
    ezfunction_policy_set.name = 'ClassID' AND
    ezpolicy_set_value.set_id = ezfunction_policy_set.id AND
    ezpolicy_set_value.value = '2';  

--------------------------------------------------------------------------------------------------------------------------------------
**************************************************************************************************************************************
--------------------------------------------------------------------------------------------------------------------------------------

select ezrole.id 
from 
    ezrole, 
    ezuser_role,
    ezfunction_policy_set,
    ezpolicy_set_value
where 
    ezuser_role.contentobject_id = 49 AND
    ezrole.id =  ezuser_role.role_id AND
    ezfunction_policy_set.module_name = 'content' AND
    ezfunction_policy_set.function_name = 'sitemap' AND
    ezfunction_policy_set.role_id = ezrole.id  AND   
    ezfunction_policy_set.name = 'ClassID' AND
    ezpolicy_set_value.set_id = ezfunction_policy_set.id AND
    ezpolicy_set_value.value = '2';  





--------------------------------------------------------------------------------------------------------------------------------------
**************************************************************************************************************************************
--------------------------------------------------------------------------------------------------------------------------------------




*/
drop SEQUENCE ezrole_s;
CREATE SEQUENCE ezrole_s;
drop table ezrole;
create table ezrole(
id int not null primary key DEFAULT nextval('ezrole_s'),
version int DEFAULT '0',
name varchar not null,
value char(1)
);
insert into ezrole(name, value) values('Anonimous', '');  
insert into ezrole(name, value) values('Admin', '*');
insert into ezrole(name, value) values('editor', '');
insert into ezrole(name, value) values('advanced editor','');
  

drop SEQUENCE ezuser_role_s;
CREATE SEQUENCE ezuser_role_s;
drop table ezuser_role;
create table ezuser_role(
id int not null primary key DEFAULT nextval('ezuser_role_s'),
role_id int,
contentobject_id int
);
insert into ezuser_role( role_id, contentobject_id ) values(1,49);
insert into ezuser_role( role_id, contentobject_id ) values(2,50);
insert into ezuser_role( role_id, contentobject_id ) values(3,51);
insert into ezuser_role( role_id, contentobject_id ) values(3,53);
insert into ezuser_role( role_id, contentobject_id ) values(4,53);


drop SEQUENCE ezmodule_s;
CREATE SEQUENCE ezmodule_s;
drop table ezmodule;
create table ezmodule(
id int not null primary key DEFAULT nextval('ezmodule_s'),
name varchar not null
);
insert into ezmodule(name) values('class');
insert into ezmodule(name) values('content');
insert into ezmodule(name) values('search');

drop SEQUENCE ezmodule_policy_s;
CREATE SEQUENCE ezmodule_policy_s;
drop table ezmodule_policy;
create table ezmodule_policy(
id int not null primary key DEFAULT nextval('ezmodule_policy_s'),
module_id int,
module_name varchar,
role_id int,
value char(1)
);

insert into ezmodule_policy(module_id,module_name,role_id,value) values(2, 'content', 1,'');
insert into ezmodule_policy(module_id,module_name,role_id,value) values(3, 'search', 1,'');
insert into ezmodule_policy(module_id,module_name,role_id,value) values(1, 'class', 2,'*');
insert into ezmodule_policy(module_id,module_name,role_id,value) values(2, 'content', 2,'*');
insert into ezmodule_policy(module_id,module_name,role_id,value) values(3, 'search', 2,'*');
insert into ezmodule_policy(module_id,module_name,role_id,value) values(1, 'class', 3,'');
insert into ezmodule_policy(module_id,module_name,role_id,value) values(2, 'content', 3,'');
insert into ezmodule_policy(module_id,module_name,role_id,value) values(3, 'search', 3,'*');
insert into ezmodule_policy(module_id,module_name,role_id,value) values(1, 'class', 4,'');
insert into ezmodule_policy(module_id,module_name,role_id,value) values(2, 'content', 4,'*');
insert into ezmodule_policy(module_id,module_name,role_id,value) values(3, 'search', 4,'*');
 
drop SEQUENCE ezfunction_s;
CREATE SEQUENCE ezfunction_s;
drop table ezfunction;
create table ezfunction(
id int not null primary key DEFAULT nextval('ezfunction_s'),
module_id int,
name varchar not null,
module_name varchar
);
insert into ezfunction(module_id, name, module_name) values('1', 'list', 'class');
insert into ezfunction(module_id, name, module_name) values('1', 'edit', 'class');
insert into ezfunction(module_id, name, module_name) values('1', 'delete', 'class');
insert into ezfunction(module_id, name, module_name) values('2', 'sitemap', 'content');
insert into ezfunction(module_id, name, module_name) values('2', 'delete', 'content');
insert into ezfunction(module_id, name, module_name) values('2', 'edit', 'content');
insert into ezfunction(module_id, name, module_name) values('2', 'view', 'content');
insert into ezfunction(module_id, name, module_name) values('3', 'search','search');
insert into ezfunction(module_id, name, module_name) values('1', 'create', 'class');


drop SEQUENCE ezpolicy_s;
CREATE SEQUENCE ezpolicy_s;
drop table ezpolicy;
create table ezpolicy(
id int not null primary key DEFAULT nextval('ezpolicy_s'),
role_id int,
function_name varchar,
module_name varchar,
context char(1)
);
insert into ezpolicy(role_id,module_name,function_name,context) values(1, 'content', 'sitemap', '');
insert into ezpolicy(role_id,module_name,function_name,context) values(1, 'search' , 'search' , '');

insert into ezpolicy(role_id,module_name,function_name,context) values(2, '*'      , '*'      , '*' );
insert into ezpolicy(role_id,module_name,function_name,context) values(2, 'class'  ,  '*'     , '*');
insert into ezpolicy(role_id,module_name,function_name,context) values(2, 'content', '*'      , '*');
insert into ezpolicy(role_id,module_name,function_name,context) values(2, 'search' , '*'      , '*');

insert into ezpolicy(role_id,module_name,function_name,context) values(3, 'class'  , 'list'   , '*');
insert into ezpolicy(role_id,module_name,function_name,context) values(3, 'class'  , 'edit'   , '' );
insert into ezpolicy(role_id,module_name,function_name,context) values(3, 'content', 'sitemap', '*');
insert into ezpolicy(role_id,module_name,function_name,context) values(3, 'content', 'delete' , '*');
insert into ezpolicy(role_id,module_name,function_name,context) values(3, 'content', 'edit'   , '*');
insert into ezpolicy(role_id,module_name,function_name,context) values(3, 'content', 'view'   , '*');
insert into ezpolicy(role_id,module_name,function_name,context) values(3, 'search' , '*'      , '*');

insert into ezpolicy(role_id,module_name,function_name,context) values(4, 'content', 'sitemap', '*');
insert into ezpolicy(role_id,module_name,function_name,context) values(4, 'class'  , 'edit'   , '*');
insert into ezpolicy(role_id,module_name,function_name,context) values(4, 'search' , 'search' , '*');
insert into ezpolicy(role_id,module_name,function_name,context) values(4, 'content', '*'      , '*');
insert into ezpolicy(role_id,module_name,function_name,context) values(4, 'search' , '*'      , '*');

/*insert into ezpolicy(module_id,module_name,role_id,value) values(1, 'class', 2,'*');
*/

/*
insert into ezpolicy(module_id,module_name,role_id,value) values(2, 'content', 2,'*');
*/
/*
insert into ezpolicy(module_id,module_name,role_id,value) values(3, 'search', 2,'*');
*/
/*
insert into ezpolicy(module_id,module_name,role_id,value) values(3, 'search', 3,'*');
*/
/*insert into ezpolicy(module_id,module_name,role_id,value) values(2, 'content', 4,'*');
*/
/*
insert into ezpolicy(module_id,module_name,role_id,value) values(3, 'search', 4,'*');
*/

drop SEQUENCE ezpolicy_set_s;
CREATE SEQUENCE ezpolicy_set_s;
drop table ezpolicy_set;
create table ezpolicy_set(
id int not null primary key DEFAULT nextval('ezpolicy_set_s'),
policy_id int,
identifier varchar not null,
role_id int,
function_name varchar,
module_name varchar
);

insert into ezpolicy_set(policy_id,identifier,role_id,function_name,module_name) values(1,'ClassID', 1,  'content', 'sitemap');
insert into ezpolicy_set(policy_id,identifier,role_id,function_name,module_name) values(1,'ObjectID', 1,  'content', 'sitemap');
insert into ezpolicy_set(policy_id,identifier,role_id,function_name,module_name) values(2,'ClassID', 1, 'search', 'search');
insert into ezpolicy_set(policy_id,identifier,role_id,function_name,module_name) values(2,'ObjectID',1, 'search', 'search');
insert into ezpolicy_set(policy_id,identifier,role_id,function_name,module_name) values(8,'ClassID', 3, 'edit', 'class');


drop SEQUENCE ezpolicy_set_value_s;
CREATE SEQUENCE ezpolicy_set_value_s;
drop table ezpolicy_set_value;
create table ezpolicy_set_value(
id int not null primary key DEFAULT nextval('ezpolicy_set_value_s'),
set_id int,
value int
);

insert into ezpolicy_set_value(set_id,value) values(1,'2');
insert into ezpolicy_set_value(set_id,value) values(2,'1');
insert into ezpolicy_set_value(set_id,value) values(2,'17');
insert into ezpolicy_set_value(set_id,value) values(2,'45');
insert into ezpolicy_set_value(set_id,value) values(2,'3');
insert into ezpolicy_set_value(set_id,value) values(2,'46');
insert into ezpolicy_set_value(set_id,value) values(3,'2');

insert into ezpolicy_set_value(set_id,value) values(4,'1');
insert into ezpolicy_set_value(set_id,value) values(4,'17');
insert into ezpolicy_set_value(set_id,value) values(4,'45');
insert into ezpolicy_set_value(set_id,value) values(4,'3');
insert into ezpolicy_set_value(set_id,value) values(4,'46');
insert into ezpolicy_set_value(set_id,value) values(5,'2'); 


/*
drop SEQUENCE ezfunction_policy_set_s;
CREATE SEQUENCE ezfunction_policy_set_s;
drop table ezfunction_policy_set;
create table ezfunction_policy_set(
id int not null primary key DEFAULT nextval('ezfunction_policy_set_s'),
function_policy_id int,
name varchar not null,
role_id int,
function_name varchar,
module_name varchar
);

insert into ezfunction_policy_set(function_policy_id,name,role_id,function_name,module_name) values(1,'ClassID', 1, 'sitemap', 'content');
insert into ezfunction_policy_set(function_policy_id,name,role_id,function_name,module_name) values(1,'ObjectID', 1, 'sitemap', 'content');
insert into ezfunction_policy_set(function_policy_id,name,role_id,function_name,module_name) values(2,'ClassID', 1, 'search', 'search');
insert into ezfunction_policy_set(function_policy_id,name,role_id,function_name,module_name) values(2,'ObjectID',1, 'search', 'search');
insert into ezfunction_policy_set(function_policy_id,name,role_id,function_name,module_name) values(4,'ClassID', 3, 'edit', 'class');
*/
/*
drop SEQUENCE ezfunction_policy_s;
CREATE SEQUENCE ezfunction_policy_s;
drop table ezfunction_policy;
create table ezfunction_policy(
id int not null primary key DEFAULT nextval('ezfunction_policy_s'),
function_id int,
role_id int,
function_name varchar,
module_name varchar,
value char(1)
);

insert into ezfunction_policy(function_id,role_id,function_name,module_name,value) values(4, 1, 'sitemap', 'content', '');
insert into ezfunction_policy(function_id,role_id,function_name,module_name,value) values(8, 1, 'search', 'search', '');
insert into ezfunction_policy(function_id,role_id,function_name,module_name,value) values(1, 3, 'list', 'class', '*');
insert into ezfunction_policy(function_id,role_id,function_name,module_name,value) values(2, 3, 'edit', 'class','');
insert into ezfunction_policy(function_id,role_id,function_name,module_name,value) values(4, 3, 'sitemap', 'content', '*');
insert into ezfunction_policy(function_id,role_id,function_name,module_name,value) values(5, 3, 'delete', 'content', '*');
insert into ezfunction_policy(function_id,role_id,function_name,module_name,value) values(6, 3, 'edit', 'content', '*');
insert into ezfunction_policy(function_id,role_id,function_name,module_name,value) values(7, 3, 'view', 'content', '*');

insert into ezfunction_policy(function_id,role_id,function_name,module_name,value) values(1, 4, 'sitemap', 'content','*');
insert into ezfunction_policy(function_id,role_id,function_name,module_name,value) values(2, 4, 'edit', 'class','*');
insert into ezfunction_policy(function_id,role_id,function_name,module_name,value) values(3, 4, 'search','search','*');
*/



