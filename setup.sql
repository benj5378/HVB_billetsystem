CREATE TABLE hvb_admin_users ( 
	admin_user_username  varchar(255)  NOT NULL    PRIMARY KEY,
	admin_user_password  varchar(255)  NOT NULL    
 ) engine=InnoDB;

CREATE TABLE hvb_events ( 
	event_id             int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	event_type           enum('særtog', 'plantog')  NOT NULL    ,
	event_date           date      ,
	event_description    text      
 ) engine=InnoDB;

CREATE TABLE hvb_payments ( 
	payment_id           int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	payment_id_dibs      int      ,
	payment_datetime     datetime  NOT NULL    ,
	payment_email_address varchar(255)      ,
	payment_email_sent   datetime      
 ) engine=InnoDB;

CREATE TABLE hvb_trains ( 
	train_id             int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	train_seats          tinyint  NOT NULL DEFAULT 1   ,
	train_locomotive     enum('motor', 'damp')  NOT NULL    ,
	train_compartments   tinyint  NOT NULL    ,
	events__event_id      int  NOT NULL    
 ) engine=InnoDB;

CREATE TABLE hvb_admin_privileges ( 
	admin_privilege_id   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	admin_privilege_name varchar(255)  NOT NULL    ,
	admin_users__admin_user_username varchar(255)  NOT NULL    
 ) engine=InnoDB;

CREATE TABLE hvb_departures ( 
	departure_id         int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	departure_type       enum('outbond', 'homebond')  NOT NULL    ,
	trains__train_id      int  NOT NULL    
 ) engine=InnoDB;

CREATE TABLE hvb_stops ( 
	stop_id              int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	stop_name            varchar(255)  NOT NULL    ,
	stop_departure_time  datetime  NOT NULL    ,
	departures__departure_id int  NOT NULL    
 ) engine=InnoDB;

CREATE TABLE hvb_ticket ( 
	ticket_id            int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	ticket_qr            varchar(255)  NOT NULL    ,
	ticket_valid         boolean  NOT NULL    ,
	ticket_reserved_compartments tinyint      ,
	ticket_start__stops__stop_id int  NOT NULL    ,
	ticket_end__stops__stop_id int  NOT NULL    ,
	payments__payment_id  int  NOT NULL    
 ) engine=InnoDB;

CREATE TABLE hvb_passengers ( 
	passenger_id         int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	ticket_type          enum('voksen 12+ år', 'barn 3-11 år', 'barn 0-2 år')  NOT NULL    ,
	tickets__ticket_id    int  NOT NULL    
 ) engine=InnoDB;

CREATE TABLE hvb_scans ( 
	scan_id              int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	scan_datetime        datetime      ,
	tickets__ticket_id    int  NOT NULL    
 ) engine=InnoDB;

ALTER TABLE hvb_admin_privileges ADD CONSTRAINT fk_hvb_admin_privileges_hvb_admin_users FOREIGN KEY ( admin_users__admin_user_username ) REFERENCES hvb_admin_users( admin_user_username ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE hvb_departures ADD CONSTRAINT fk_hvb_departures_hvb_trains FOREIGN KEY ( trains__train_id ) REFERENCES hvb_trains( train_id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE hvb_passengers ADD CONSTRAINT fk_hvb_passengers_hvb_ticket FOREIGN KEY ( tickets__ticket_id ) REFERENCES hvb_ticket( ticket_id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE hvb_scans ADD CONSTRAINT fk_hvb_scans_hvb_ticket FOREIGN KEY ( tickets__ticket_id ) REFERENCES hvb_ticket( ticket_id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE hvb_stops ADD CONSTRAINT fk_hvb_stops_hvb_departures FOREIGN KEY ( departures__departure_id ) REFERENCES hvb_departures( departure_id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE hvb_ticket ADD CONSTRAINT fk_hvb_ticket_hvb_stops FOREIGN KEY ( ticket_start__stops__stop_id ) REFERENCES hvb_stops( stop_id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE hvb_ticket ADD CONSTRAINT fk_hvb_ticket_hvb_stops_0 FOREIGN KEY ( ticket_end__stops__stop_id ) REFERENCES hvb_stops( stop_id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE hvb_ticket ADD CONSTRAINT fk_hvb_ticket_hvb_payments FOREIGN KEY ( payments__payment_id ) REFERENCES hvb_payments( payment_id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE hvb_trains ADD CONSTRAINT fk_hvb_trains_hvb_events FOREIGN KEY ( events__event_id ) REFERENCES hvb_events( event_id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- Set PLANTOG row
INSERT INTO `hvb_events` (`event_id`, `event_type`, `event_date`, `event_description`) VALUES ('1', 'plantog', NULL, NULL);
