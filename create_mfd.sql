drop database if exists mfd;
create database mfd;
use mfd;

create table date (
   date_id int not null auto_increment,
   day int not null,
   month int not null,
   year int not null,
   primary key (date_id)
);

create table address (
   address_id int not null auto_increment,
   line1 varchar(64),
   line2 varchar(64),
   city varchar(32),
   state varchar(2),
   zip varchar(16),
   gmap_link varchar(64),
   gmap_embed varchar(512),
   primary key (address_id)
);

create table family (
   family_id int not null auto_increment,
   name varchar(64) not null,
   address_id int,
   phone varchar(16),
   anniversary_id int,
   primary key (family_id),
   unique (name),
   foreign key (address_id) references address(address_id),
   foreign key (anniversary_id) references date(date_id)
);

create table person (
   person_id int not null auto_increment,
   first_name varchar(32) not null,
   last_name varchar(32) not null,
   family_id int not null,
   birthday_id int,
   show_age bool,
   primary key (person_id),
   foreign key (family_id) references family(family_id),
   foreign key (birthday_id) references date(date_id)
);

create table event (
   event_id int not null auto_increment,
   date_id int not null,
   family_id int not null,
   start_time varchar(4),
   primary key (event_id),
   foreign key (family_id) references family(family_id),
   foreign key (date_id) references date(date_id)
);

create table user (
   user_id int not null auto_increment,
   username varchar(64) not null,
   passcode varchar(255) not null,
   family_id int not null,
   primary key (user_id),
   foreign key (family_id) references family(family_id)
);

-- Sarah
insert into address(address_id,line1,line2,city,state,zip) values (1,'1181 Ivy Hill Drive','','Mendota Heights', 'MN', '55118');
-- Kevin
insert into address(address_id,line1,line2,city,state,zip) values (2,'78 10th St. E #2810','','St. Paul', 'MN', '55101');
-- Corry
insert into address(address_id,line1,line2,city,state,zip) values (3,'8086 Somerset Knolls','','Woodbury', 'MN', '55125');
-- Eide
insert into address(address_id,line1,line2,city,state,zip) values (4,'13875 Shannon Parkway','','Rosemount', 'MN', '55068');
-- Galbari
insert into address(address_id,line1,line2,city,state,zip) values (5,'648 Superior Court','','Eagan', 'MN', '55123');
-- Martin
insert into address(address_id,line1,line2,city,state,zip) values (6,'1170 St. Clair Ave.','','St. Paul', 'MN', '55105');
-- Martin(orono)
insert into address(address_id,line1,line2,city,state,zip,gmap_link,gmap_embed) values (7,'2695 Pheasant Rd','','Orono', 'MN', '55331','https://goo.gl/maps/1vDoRby74AB2','<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2824.7069014204376!2d-93.6041456846077!3d44.92929567699617!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x87f60295fd981ca1%3A0xd36b49eb96b36018!2s2695+Pheasant+Rd%2C+Excelsior%2C+MN+55331!5e0!3m2!1sen!2sus!4v1462386611825" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>');
-- Martin(Jefferson)
insert into address(address_id,line1,line2,city,state,zip) values (8,'1881 Jefferson Ave.','','St. Paul', 'MN', '55105');
-- Dave Martin
insert into address(address_id,line1,line2,city,state,zip) values (9,'1685 Boardwalk','','Eagan', 'MN', '55122');
-- Sheppard
insert into address(address_id,line1,line2,city,state,zip) values (10,'3579 Lemieux Cir.','','Eagan', 'MN', '55122');
-- Mo
insert into address(address_id,line1,line2,city,state,zip) values (11,'22 Inner Drive','Apt. M24','St. Paul', 'MN', '55116');
-- Adie
insert into address(address_id,line1,line2,city,state,zip) values (12,'1300 Wittman Park Lane','','Menasha', 'WI', '54952');

insert into date(date_id,day,month,year) values (1,26,12,1947); -- Sarah
insert into date(date_id,day,month,year) values (2,14,2,1973); -- Kevin
insert into date(date_id,day,month,year) values (3,26,3,1900); -- Megan
insert into date(date_id,day,month,year) values (4,25,12,1975); -- Jesse
insert into date(date_id,day,month,year) values (5,1,4,1976); -- Robin
insert into date(date_id,day,month,year) values (6,2,3,1900); -- Mike
insert into date(date_id,day,month,year) values (7,19,12,1900); -- Kate
insert into date(date_id,day,month,year) values (8,1,1,1900); -- Scott
insert into date(date_id,day,month,year) values (9,18,7,1900); -- Meagan
insert into date(date_id,day,month,year) values (10,10,12,1945); -- Bill
insert into date(date_id,day,month,year) values (11,12,3,1949); -- Maripat
insert into date(date_id,day,month,year) values (12,24,2,1972); -- Rob
insert into date(date_id,day,month,year) values (13,2,12,1972); -- Steph
insert into date(date_id,day,month,year) values (14,24,11,1973); -- PT
insert into date(date_id,day,month,year) values (15,9,11,1976); -- Rebecca
insert into date(date_id,day,month,year) values (16,20,7,1976); -- Dave
insert into date(date_id,day,month,year) values (17,24,7,1978); -- Michelle
insert into date(date_id,day,month,year) values (18,28,6,1900); -- Pete
insert into date(date_id,day,month,year) values (19,22,3,1900); -- Sharon
insert into date(date_id,day,month,year) values (20,26,5,1983); -- Mo
insert into date(date_id,day,month,year) values (21,11,3,1944); -- Adie
insert into date(date_id,day,month,year) values (22,15,7,2010); -- Stevie
insert into date(date_id,day,month,year) values (23,18,7,2013); -- Bobby
insert into date(date_id,day,month,year) values (24,18,7,2013); -- Teddy
insert into date(date_id,day,month,year) values (25,19,5,2007); -- Martinopoulos
insert into date(date_id,day,month,year) values (26,29,5,2016); -- Martinopoulos 5/16 dinner

-- Sarah
insert into family(family_id,name,address_id,phone,anniversary_id) values (1,'Arendt(Mendota Hts)',1,NULL,NULL);
-- Kevin
insert into family(family_id,name,address_id,phone,anniversary_id) values (2,'Arendt(St. Paul)',2,NULL,NULL);
-- Corry
insert into family(family_id,name,address_id,phone,anniversary_id) values (3,'Corry',3,NULL,NULL);
-- Eide
insert into family(family_id,name,address_id,phone,anniversary_id) values (4,'Eide',4,NULL,NULL);
-- Sarah
insert into family(family_id,name,address_id,phone,anniversary_id) values (5,'Galbari',5,NULL,NULL);
-- Kevin
insert into family(family_id,name,address_id,phone,anniversary_id) values (6,'Martin(St. Clair)',6,NULL,NULL);
-- Corry
insert into family(family_id,name,address_id,phone,anniversary_id) values (7,'Martinopolous',7,'952.848.2310',25);
-- Eide
insert into family(family_id,name,address_id,phone,anniversary_id) values (8,'Martin(Jefferson)',8,NULL,NULL);
-- Sarah
insert into family(family_id,name,address_id,phone,anniversary_id) values (9,'Martin(Eagan)',9,NULL,NULL);
-- Kevin
insert into family(family_id,name,address_id,phone,anniversary_id) values (10,'Sheppard',10,NULL,NULL);
-- Corry
insert into family(family_id,name,address_id,phone,anniversary_id) values (11,'Martin(Mo)',11,NULL,NULL);
-- Eide
insert into family(family_id,name,address_id,phone,anniversary_id) values (12,'Martin(WI)',12,NULL,NULL);

insert into person(person_id,first_name,last_name,family_id,birthday_id,show_age) values (1,'Sarah','Arendt',1,1,true);
insert into person(person_id,first_name,last_name,family_id,birthday_id,show_age) values (2,'Kevin','Arendt',2,2,true);
insert into person(person_id,first_name,last_name,family_id,birthday_id,show_age) values (3,'Megan','Flynn',2,3,false);
insert into person(person_id,first_name,last_name,family_id,birthday_id,show_age) values (4,'Jesse','Corry',3,4,true);
insert into person(person_id,first_name,last_name,family_id,birthday_id,show_age) values (5,'Robin','Corry',3,5,true);
insert into person(person_id,first_name,last_name,family_id,birthday_id,show_age) values (6,'Mike','Eide',4,6,false);
insert into person(person_id,first_name,last_name,family_id,birthday_id,show_age) values (7,'Kate','Eide',4,7,false);
insert into person(person_id,first_name,last_name,family_id,birthday_id,show_age) values (8,'Scott','Galbari',5,8,false);
insert into person(person_id,first_name,last_name,family_id,birthday_id,show_age) values (9,'Meagan','Galbari',5,9,false);
insert into person(person_id,first_name,last_name,family_id,birthday_id,show_age) values (10,'Bill','Martin',6,10,true);
insert into person(person_id,first_name,last_name,family_id,birthday_id,show_age) values (11,'Maripat','Martin',6,11,true);
insert into person(person_id,first_name,last_name,family_id,birthday_id,show_age) values (12,'Rob','Martin',7,12,true);
insert into person(person_id,first_name,last_name,family_id,birthday_id,show_age) values (13,'Steph','Sarantopoulos',7,13,true);
insert into person(person_id,first_name,last_name,family_id,birthday_id,show_age) values (14,'Patrick','Martin',8,14,true);
insert into person(person_id,first_name,last_name,family_id,birthday_id,show_age) values (15,'Rebecca','Martin',8,15,true);
insert into person(person_id,first_name,last_name,family_id,birthday_id,show_age) values (16,'Dave','Martin',9,16,true);
insert into person(person_id,first_name,last_name,family_id,birthday_id,show_age) values (17,'Michelle','Martin',9,17,true);
insert into person(person_id,first_name,last_name,family_id,birthday_id,show_age) values (18,'Pete','Sheppard',10,18,false);
insert into person(person_id,first_name,last_name,family_id,birthday_id,show_age) values (19,'Sharon','Sheppard',10,19,false);
insert into person(person_id,first_name,last_name,family_id,birthday_id,show_age) values (20,'Mo','Martin',11,20,true);
insert into person(person_id,first_name,last_name,family_id,birthday_id,show_age) values (21,'Adie','Martin',12,21,true);
insert into person(person_id,first_name,last_name,family_id,birthday_id,show_age) values (22,'Stevie','Martin',7,22,true);
insert into person(person_id,first_name,last_name,family_id,birthday_id,show_age) values (23,'Teddy','Martin',7,23,true);
insert into person(person_id,first_name,last_name,family_id,birthday_id,show_age) values (24,'Bobby','Martin',7,24,true);

insert into user(user_id,username,passcode,family_id) values (1,'rfkmartin@gmail.com','$2y$10$PH2Oebi7uTmTA/yssOvZyeH1hO/8Tvb/zji08snvnHFU0pUnzgBOS',7);

insert into event(event_id,date_id,family_id) values (1,26,7);