<!-- creation of tables -->
create database vsms;

create table customer
(cust_id int primary key,
name varchar (10),
phone_number int (10),
email varchar (20),
address varchar (15));

create table vehicle
(vehicle_id int primary key,
model varchar (10),
cust_id int,
foreign key (cust_id) references customer(cust_id));

create table appointment 
(apt_id int primary key,
cust_id int,
vehicle_id int,
date date,
service_type varchar (20),
foreign key (cust_id) references customer (cust_id),
foreign key (vehicle_id) references vehicle (vehicle_id));

create table technician
(tech_id int primary key,
name varchar (55),
phone_no int  (10), 
email varchar (20));

create table service
(service_id int primary key,
vehicle_id int,
tech_id int,
description text,
cost decimal (10,2),
date date,
foreign key (vehicle_id) references vehicle (vehicle_id),
foreign key (tech_id) references technician (tech_id));

create table payment
(payment_id int (11) primary key,
service_id int (11),
cost int (10),
cust_id int (11),
foreign key (service_id) references service (service_id),
foreign key (cust_id) references customer (cust_id));

create table rating_review
(customer_id int (11),
review varchar (255),
date date,
foreign key (customer_id) references customer (cust_id));