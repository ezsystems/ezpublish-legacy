alter table ezproductcollection add column created integer;

create table ezproductcollection_item_opt(
    id int NOT NULL auto_increment,
    item_id int not null,
    option_item_id int not null,
    name varchar(255) not null,
    value varchar(255) not null,
    price float not null default 0,
    PRIMARY KEY  (id)
    );

alter table ezproductcollection_item modify price double;
alter table ezproductcollection_item add is_vat_inc int;
alter table ezproductcollection_item add vat_value float;
alter table ezproductcollection_item add discount float;
alter table ezproductcollection_item_opt add object_attribute_id int;

alter table ezorder add data_text_2 text;
alter table ezorder add data_text_1 text;

alter table ezsection add navigation_part_identifier varchar(100) default 'ezcontentnavigationpart';
alter table ezorder add account_identifier varchar(100) not null default 'default';

drop table eztask;
drop table eztask_message;

alter table ezimage add alternative_text varchar(255) not null default "";
alter table ezcontentobject add remote_id varchar(100);


alter table ezorder add ignore_vat int not null default '0';

alter table ezorder_item drop vat_type_id;
alter table ezorder_item drop vat_is_included;
alter table ezorder_item add vat_value int not null default '0';

alter table ezcontentobject_tree add index ( md5_path );
alter table ezsession drop cache_mask_1;

create table ezforgot_password(
    id int NOT NULL auto_increment,
    user_id int not null,
    hash_key varchar(32) not null,
    time int not null,
    PRIMARY KEY  (id)
    );

create table ezuser_accountkey(
    id int NOT NULL auto_increment,
    user_id int not null,
    hash_key varchar(32) not null,
    time int not null,
    PRIMARY KEY  (id)
    );
