#Adding two field in table ezcontentobject_tree

alter table ezcontentobject_tree add sort_field int default 1 after path_identification_string;
alter table ezcontentobject_tree add sort_order int(1) default 1 after path_identification_string;


#Adding two field in table eznode_assignment
alter table eznode_assignment add sort_field int default 1 after main;
alter table eznode_assignment add sort_order int(1) default 1 after main;

# Change the length of enum fields
alter table ezenumvalue change enumelement enumelement varchar(255);
alter table ezenumvalue change enumvalue enumvalue varchar(255);

alter table ezenumobjectvalue change enumelement enumelement varchar(255);
alter table ezenumobjectvalue change enumvalue enumvalue varchar(255);

# Adding two field in table ezsearch_object_word_link
alter table ezsearch_object_word_link add published int not null after contentclass_attribute_id;
alter table ezsearch_object_word_link add section_id int not null after published;

# Sorting priority
alter table ezcontentobject_tree add priority int not null default '0';

# storing info about module and function of limitation
# you should run that query only for kernel_clean db
update ezpolicy_limitation set function_name='read', module_name='content', identifier='Class' where policy_id=306;
