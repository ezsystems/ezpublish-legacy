the quick brown fox has the best chicken wings in the world
1    2    3     4   5    1   6    7       8    9  1   10


id  |  position  | next |  word
--------------------------------
1   |  1         |  2   |  the
1   |  6         |  6   |  the
1   |  11        |  10  |  the
2   |  2         |  3   | quick
10  |  12        |      | world


drop table if exists word_index;
create table word_index (
id int not null auto_increment,
word varchar(50) not null,
primary key(id) );

insert into word_index (id,word) values(1,'the');
insert into word_index (id,word) values(2,'quick');
insert into word_index (id,word) values(3,'brown');
insert into word_index (id,word) values(4,'fox');
insert into word_index (id,word) values(5,'has');
insert into word_index (id,word) values(6,'best');
insert into word_index (id,word) values(7,'chicken');
insert into word_index (id,word) values(8,'wings');
insert into word_index (id,word) values(9,'in');
insert into word_index (id,word) values(10,'world');

insert into word_index (id,word) values(11,'is');
insert into word_index (id,word) values(12,'a');
insert into word_index (id,word) values(13,'round');
insert into word_index (id,word) values(14,'place');
insert into word_index (id,word) values(15,'and');

drop table if exists object;
create table object (
id int not null auto_increment,
name varchar(50) not null,
primary key(id) );

insert into object (id,name) values(1,'article1');
insert into object (id,name) values(2,'folder1');
insert into object (id,name) values(3,'folder2');


drop table if exists word_placement;
create table word_placement (
id int not null auto_increment,
word_id int not null,
object int not null,
placement int not null,
prev_word_id int,
next_word_id int,
primary key(id) );

# the quick brown fox has the best chicken wings in the world
insert into word_placement (word_id, object, placement, next_word_id) values(1,1,1,2);
insert into word_placement (word_id, object, placement, prev_word_id, next_word_id) values(1,1,6,5,6);
insert into word_placement (word_id, object, placement, prev_word_id, next_word_id) values(1,1,11,9,10);
insert into word_placement (word_id, object, placement, prev_word_id, next_word_id) values(2,1,2,1,3);
insert into word_placement (word_id, object, placement, prev_word_id, next_word_id) values(3,1,3,2,4);
insert into word_placement (word_id, object, placement, prev_word_id, next_word_id) values(4,1,4,3,5);
insert into word_placement (word_id, object, placement, prev_word_id, next_word_id) values(5,1,5,4,1);
insert into word_placement (word_id, object, placement, prev_word_id, next_word_id) values(6,1,7,1,7);
insert into word_placement (word_id, object, placement, prev_word_id, next_word_id) values(7,1,8,6,8);
insert into word_placement (word_id, object, placement, prev_word_id, next_word_id) values(8,1,9,7,9);
insert into word_placement (word_id, object, placement, prev_word_id, next_word_id) values(9,1,11,8,1);
insert into word_placement (word_id, object, placement, prev_word_id ) values(10,1,12,1);
	
# the world
insert into word_placement (word_id, object, placement, next_word_id) values(1,2,1,10);
insert into word_placement (word_id, object, placement, prev_word_id ) values(10,2,2,1);

# the world is a round place and has the best wings
insert into word_placement (word_id, object, placement, next_word_id) values(1,3,1,10);
insert into word_placement (word_id, object, placement, next_word_id, prev_word_id ) values(10,3,2,11,1);
insert into word_placement (word_id, object, placement, next_word_id, prev_word_id ) values(11,3,2,12,10);
insert into word_placement (word_id, object, placement, next_word_id, prev_word_id ) values(12,3,2,13,11);
insert into word_placement (word_id, object, placement, next_word_id, prev_word_id ) values(13,3,2,14,12);
insert into word_placement (word_id, object, placement, next_word_id, prev_word_id ) values(14,3,2,15,13);
insert into word_placement (word_id, object, placement, next_word_id, prev_word_id ) values(15,3,2,5,14);
insert into word_placement (word_id, object, placement, next_word_id, prev_word_id ) values(5,3,2,1,15);
insert into word_placement (word_id, object, placement, next_word_id, prev_word_id ) values(1,3,2,6,5);
insert into word_placement (word_id, object, placement, next_word_id, prev_word_id ) values(6,3,2,8,1);
insert into word_placement (word_id, object, placement, prev_word_id ) values(8,3,2,6);

search for 'the world'
            1   10

select * from word_placement as place, word_index as word where place.word_id=word.id and ( (place.word_id=1 and place.next_word_id=10) or (place.word_id=10 and place.prev_word_id=1) ) ORDER BY  place.object asc, place.placement asc;

search for 'the chicken'
            1   7

select * from word_placement as place, word_index as word where place.word_id=word.id and ( (place.word_id=1 and place.next_word_id=7) or (place.word_id=7 and place.prev_word_id=1) ) ORDER BY place.placement asc;


search for 'the best chicken'
            1   6      7

select * from word_placement as place, word_index as word where place.word_id=word.id and ( (place.word_id=1 and place.next_word_id=6) or (place.word_id=6 and place.prev_word_id=1 and place.next_word_id=7) or (place.word_id=7 and place.prev_word_id=6) ) ORDER BY place.placement asc;

search for 'the best chicken wings'
            1   6      7      8

select * from word_placement as place, word_index as word where place.word_id=word.id and ( (place.word_id=1 and place.next_word_id=6) or (place.word_id=6 and place.prev_word_id=1 and place.next_word_id=7) or (place.word_id=7 and place.prev_word_id=6 and place.next_word_id=8) or (place.word_id=8 and place.prev_word_id=7) ) ORDER BY place.placement asc;


search for 'the world' and 'chicken wings'
            1   10          7    8

select * from word_placement as place, word_index as word where place.word_id=word.id and ( ( (place.word_id=1 and place.next_word_id=10) or (place.word_id=10 and place.prev_word_id=1) ) or ( (place.word_id=7 and place.next_word_id=8) or (place.word_id=8 and place.prev_word_id=7) ) ) ORDER BY place.placement asc;



search for 'the world' minus 'chicken'
            1   10            7
select count(place.object) AS cnt, place.*, word.* from word_placement as place, word_index as word where place.word_id=word.id and ( (place.word_id=1 and place.next_word_id=10) or (place.word_id=10 and place.prev_word_id=1) or (place.word_id=7) ) GROUP BY place.object ORDER BY place.object, place.placement asc;


search for 'the world' and 'the' minus 'chicken'
            1   10          1             7
create temporary table word_tmp(id int not null auto_increment, word_id int not null, object int not null, primary key(id));

insert into word_tmp(id,word_id,object) select place.id, place.word_id, place.object from word_placement as place, word_index as word where place.word_id=word.id and ( (place.word_id=1 and place.next_word_id=10) or (place.word_id=10 and place.prev_word_id=1) or (place.word_id=1) or (place.word_id=7) ) GROUP BY place.object, word.id ORDER BY place.object, place.placement asc;

select count(id) as cnt, object from word_tmp group by object having cnt=2;

drop table word_tmp;

#select count(place.object) AS cnt, place.*, word.* from word_placement as place, word_index as word where place.word_id=word.id and ( (place.word_id=1 and place.next_word_id=10) or (place.word_id=10 and place.prev_word_id=1) or (place.word_id=1) or (place.word_id=7) ) GROUP BY place.object ORDER BY place.object, place.placement asc;

search for 'the' and 'has' minus 'round'
            1          5            13
create temporary table word_tmp(id int not null auto_increment, word_id int not null, object int not null, primary key(id));

insert into word_tmp(id,word_id,object) select place.id, place.word_id, place.object from word_placement as place, word_index as word where place.word_id=word.id and ( (place.word_id=1) or (place.word_id=5) or (place.word_id=13)) GROUP BY place.object, word.id ORDER BY place.object, place.placement asc;

select count(id) as cnt, object from word_tmp group by object having cnt=2;

drop table word_tmp;
