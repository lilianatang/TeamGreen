CREATE database carawayportal;
USE carawayportal;

/* This is subjected to change upon any issues we will have encountered with backend. Use at your own risk */

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

CREATE table family(
	family_id int(5) primary key auto_increment,
	user_id int(10),
	INDEX (user_id),
	FOREIGN KEY (user_id)
	REFERENCES users(user_id)
);


CREATE table teachers(
	teacher_id int(6) primary key auto_increment,
	first_name varchar(40),
	last_name varchar(40),
	user_id int(10),
	INDEX (user_id),
	FOREIGN KEY (user_id)
	REFERENCES users(user_id)
);


CREATE table classroom(
	classroom_id int(2) primary key auto_increment,
	class_color varchar((20) not null unique,
	teacher_id int(6),
	INDEX (teacher_id),
	FOREIGN KEY (teacher_id)
	REFERENCES teachers(teacher_id)
);

CREATE table students(
	student_id int(8) primary key auto_increment,
	first_name varchar(40),
	last_name varchar(40),
	classroom_id int(2),
	INDEX (classroom_id),
	FOREIGN KEY (classroom_id)
	REFERENCES classroom(classroom_id)
);


CREATE table facilitation_times(
	slot_id int(7) primary key auto_increment,
	classroom_id int(2) primary key auto_increment,
	date_scheduled date,
	time_start datetime,
	time_end datetime,
	facilitators_needed int(3),
	INDEX (classroom_id),
	FOREIGN KEY (classroom_id)
	REFERENCES classroom(classroom_id)
);

CREATE table current_week(
	required_hours int(3),
	completed_hours int(3),
	family_id int(5),
	INDEX (family_id),
	FOREIGN KEY (family_id)
	REFERENCES family(family_id)
);

CREATE table punching(
	time_start datetime,
	family_id int(5),
	time_end datetime,
	date_punched date,
	INDEX (family_id),
	FOREIGN KEY (family_id)
	REFERENCES family(family_id)
);


