alter table ezsubtree_notification_rule add user_id int not null;

create table tmp_notification_rule ( id int primary key auto_increment,
                                     use_digest int default 0,
                                     node_id int not null,
                                     user_id int not null );

insert into tmp_notification_rule ( use_digest, node_id, user_id ) select rule.use_digest, rule.node_id, ezuser.contentobject_id as user_id 
      from ezsubtree_notification_rule as rule, ezuser 
      where rule.address=ezuser.email;

drop table ezsubtree_notification_rule;
alter table tmp_notification_rule rename ezsubtree_notification_rule;

create index ezsubtree_notification_rule_id on ezsubtree_notification_rule(id);
create index ezsubtree_notification_rule_user_id on ezsubtree_notification_rule(user_id);

