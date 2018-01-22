CREATE database carawayportal;
USE carawayportal;

/* This is subjected to change upon any issues we will have encountered with backend. Use at your own risk */

/* format for role_id is 000 (3 digits) */

CREATE table role(
	role_id int(3) primary key auto_increment,
	role varchar(10)
	);

	/* format for user_id is 00000 (5 digits) */
CREATE table users(
	user_id int(10) primary key auto_increment,
	username varchar(20) not null unique,
	encrypted_password varchar(80) not null,
    	role_id int(3),
    	INDEX (role_id),
    	FOREIGN KEY (role_id)
      	REFERENCES role(role_id)
);

	/* format for family_id is 0000 (4 digits) */

CREATE table family(
	family_id int(5) primary key auto_increment,
	user_id int(10),
	INDEX (user_id),
	FOREIGN KEY (user_id)
	REFERENCES users(user_id)
);

	/* format for teacher_id is 1000 - 9999 (3 digits) */

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
	class_color varchar(20) not null unique,
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

CREATE table facilitator(
	facilitator_id int(8) primary key auto_increment,
	family_id int(5),
	first_name varchar(40),
	last_name varchar(40),
	email varchar(60),
	address varchar(80),
	phone_number varchar(20),
	INDEX (family_id),
	FOREIGN KEY (family_id)
	REFERENCES family(family_id)
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

CREATE table notifications(
	noti_id int(4) primary key auto_increment,
	user_id int(4),
	noti_type varchar(20),
	noti_info varchar(60),
	noti_date date,
	noti_notes varchar(80),
	INDEX (user_id),
	FOREIGN KEY (user_id)
	REFERENCES users(user_id)
);


CREATE table facilitating(
	slot_id int(8) primary key, 
	facilitator_id int(7),
	approved int(3), /* 1 for requested, 2 for pending, 3 for approved */
	notes varchar(40),
	INDEX (facilitator_id),
	FOREIGN KEY (facilitator_id)
	REFERENCES facilitator(slot_id)
);

CREATE table facilitation_times(
	slot_id int(7),
	INDEX (slot_id),
	FOREIGN KEY (slot_id)
	REFERENCES facilitating(slot_id),
	classroom_id int(2),
	date_scheduled date,
	time_start datetime,
	time_end datetime,
	facilitators_needed int(3),
	INDEX (classroom_id),
	FOREIGN KEY (classroom_id)
	REFERENCES classroom(classroom_id)
);

CREATE table actions(
	action_id int(7),
	action_description varchar(20)
);

CREATE table history(
	family_id int(5),
	completed_hours int(5),
	required_hours int(5),
	start_date date,
	end_date date,
	INDEX (family_id),
	FOREIGN KEY (family_id)
	REFERENCES family(family_id)
);

CREATE table activity_log(
	family_id int(5),
	action_id int(7),
	slot_id int(7),
	time_punched datetime,
	date_punched date,
	INDEX (family_id),
	FOREIGN KEY (family_id)
	REFERENCES family(family_id)
);	

CREATE table field_trips(
	ft_id int(5) primary key auto_increment,
	time_start datetime,
	family_id int(5),
	time_end datetime,
	location varchar(60),
	fee int(6),
	INDEX (family_id),
	FOREIGN KEY (family_id)
	REFERENCES family(family_id)
);
