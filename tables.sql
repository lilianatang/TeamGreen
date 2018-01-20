CREATE database carawayportal;
USE carawayportal;

CREATE table role(
	role_id int(3) primary key auto_increment,
	role varchar(10)
	);

CREATE table users(
	user_id int(10) primary key auto_increment,
	username varchar(20) not null unique,
	encrypted_password varchar(80) not null,
    	role_id int(3),
    	INDEX (role_id),
    	FOREIGN KEY (role_id)
      	REFERENCES role(role_id)
);


