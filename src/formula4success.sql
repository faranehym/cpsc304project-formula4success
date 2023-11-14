drop table Sponsor
drop table Constructor
drop table TeamMember
drop table Car
drop table Partner_Ref
drop table Partner_2
drop table GrandPrix_Ref
drop table GrandPrix_2

create table Sponsor(
    companyName varchar(40) PRIMARY KEY, 
	industry    varchar(40)
);

grant select on Sponsor to public;

create table Constructor (
	constructorName		varchar(40)		PRIMARY KEY, 
	nationality			varchar(40)		NOT NULL, 
	numberOfWins		int			DEFAULT 0 NOT NULL			
);

grant select on Constructor to public;

create table TeamMember (
	employeeId			int 			PRIMARY KEY, 
	firstName			varchar(40)		NOT NULL, 
	lastName			varchar(40)		NOT NULL, 
	nationality			varchar(40)		NOT NULL, 
	dateOfBirth			date			NOT NULL, 
	salary				int, 
	job				    varchar(40)		NOT NULL			
); 

grant select on TeamMember to public;

create table Car (
	Model				varchar(40)		PRIMARY KEY, 
	engine				varchar(40), 
	constructorName		varchar(40)		NOT NULL, 
	FOREIGN KEY (constructorName) REFERENCES Constructor(constructorName)
);

grant select on Car to public;

create table Partner_Ref (
	partnerId		 	int			    PRIMARY KEY, 
	partnerName		 	varchar(40)		NOT NULL, 
	instagramHandle	 	varchar(40)	    UNIQUE
);

grant select on Partner_Ref to public;

create table Partner_2 (
	instagramHandle	 	varchar(40)	PRIMARY KEY,
	instagramFollowers	int,
	FOREIGN KEY (instagramHandle) REFERENCES Partner_Ref(instagramHandle)
		ON DELETE CASCADE
);

grant select on Partner_2 to public;

create table GrandPrix_Ref (
	year				int, 
	circuitName			varchar(40), 
	viewership			int,
	PRIMARY KEY (year, circuitName),
	FOREIGN KEY (circuitName) REFERENCES Circuit_2(circuitName)
);

grant select on GrandPrix_Ref to public;

create table GrandPrix_2 (
	circuitName			varchar(40) 	PRIMARY KEY, 
	city				varchar(40)		NOT NULL,
	FOREIGN KEY (circuitName) REFERENCES GrandPrix_Ref(circuitName)
); 

grant select on GrandPrix_2 to public;

insert into Sponsor (companyName, industry) values ('Oracle', 'Tech');
insert into Sponsor (companyName, industry) values ('Zoom', 'Tech');
insert into Sponsor (companyName, industry) values ('Tommy Hilfiger', 'Apparel');
insert into Sponsor (companyName, industry) values ('Monster Energy', 'Beverage');
insert into Sponsor (companyName, industry) values ('Ray-Ban', 'Apparel');
insert into Sponsor (companyName, industry) values ('Shell', 'Oil and Gas');
insert into Sponsor (companyName, industry) values ('Chrome', 'Tech');
insert into Sponsor (companyName, industry) values ('Michelob Ultra', 'Beverage');
insert into Sponsor (companyName, industry) values ('Tik Tok', 'Tech');

insert into Constructor (constructorName, nationality, numberOfWins) values ('Red Bull Racing', 'Austria', '6');
insert into Constructor (constructorName, nationality, numberOfWins) values ('Mercedes', 'Germany', '8');
insert into Constructor (constructorName, nationality, numberOfWins) values ('Ferrari', 'Italy', '15');
insert into Constructor (constructorName, nationality, numberOfWins) values ('Aston Martin', 'England', '0');
insert into Constructor (constructorName, nationality, numberOfWins) values ('McLaren', 'England', '20');
insert into Constructor (constructorName, nationality, numberOfWins) values ('Alpine', 'France', '0');
insert into Constructor (constructorName, nationality, numberOfWins) values ('Williams', 'England', '9');
insert into Constructor (constructorName, nationality, numberOfWins) values ('Alfa Romeo', 'Italy', '5');
insert into Constructor (constructorName, nationality, numberOfWins) values ('Haas', 'United States', '0');
insert into Constructor (constructorName, nationality, numberOfWins) values ('AlphaTauri', 'Italy', '0');

insert into TeamMember (employeeId, firstName, lastName, nationality, dateOfBirth, salary, job) values ('1', 'Toto', 'Wolff', 'Austria', to_date('1972-01-12', 'YYYY-MM-DD'), '26000000', 'Team Principal');
insert into TeamMember (employeeId, firstName, lastName, nationality, dateOfBirth, salary, job) values ('2', 'Christian', 'Horner', 'England', to_date('1973-11-16', 'YYYY-MM-DD'), '10000000', 'Team Principal');
insert into TeamMember (employeeId, firstName, lastName, nationality, dateOfBirth, salary, job) values ('3', 'Zak', 'Brown', 'United States', to_date('1971-11-07', 'YYYY-MM-DD'), '5000000', 'CEO');
insert into TeamMember (employeeId, firstName, lastName, nationality, dateOfBirth, salary, job) values ('4', 'Hannah', 'Schmitz', 'England', to_date('1985-05-01', 'YYYY-MM-DD'), '154000', 'Principal Strategy Engineer');
insert into TeamMember (employeeId, firstName, lastName, nationality, dateOfBirth, salary, job) values ('5', 'Peter', 'Bonnington', 'England', to_date('1975-02-12', 'YYYY-MM-DD'), '450000', 'Senior Race Engineer');
insert into TeamMember (employeeId, firstName, lastName, nationality, dateOfBirth, salary, job) values ('6', 'Oscar', 'Piastri', 'Australia', to_date('2001-04-06', 'YYYY-MM-DD'), '2000000', 'Driver');
insert into TeamMember (employeeId, firstName, lastName, nationality, dateOfBirth, salary, job) values ('7', 'Logan', 'Sargeant', 'United States of America', to_date('2000-12-31', 'YYYY-MM-DD'), '1000000', 'Driver');
insert into TeamMember (employeeId, firstName, lastName, nationality, dateOfBirth, salary, job) values ('8', 'Yuki', 'Tsunoda', 'Japan', to_date('2000-05-11', 'YYYY-MM-DD'), '1000000', 'Driver');
insert into TeamMember (employeeId, firstName, lastName, nationality, dateOfBirth, salary, job) values ('9', 'Lando', 'Norris', 'England', to_date('1999-11-13', 'YYYY-MM-DD'), '20000000', 'Driver');
insert into TeamMember (employeeId, firstName, lastName, nationality, dateOfBirth, salary, job) values ('10', 'Zhou', 'Guanyu', 'China', to_date('1999-05-30', 'YYYY-MM-DD'), '2000000', 'Driver');
insert into TeamMember (employeeId, firstName, lastName, nationality, dateOfBirth, salary, job) values ('11', 'Lance', 'Stroll', 'Canada', to_date('1998-10-29', 'YYYY-MM-DD'), '2800000', 'Driver');
insert into TeamMember (employeeId, firstName, lastName, nationality, dateOfBirth, salary, job) values ('12', 'George', 'Russell', 'England', to_date('1998-02-15', 'YYYY-MM-DD'), '8000000', 'Driver');
insert into TeamMember (employeeId, firstName, lastName, nationality, dateOfBirth, salary, job) values ('13', 'Charles', 'Leclerc', 'Monaco', to_date('1997-10-16', 'YYYY-MM-DD'), '24000000', 'Driver');
insert into TeamMember (employeeId, firstName, lastName, nationality, dateOfBirth, salary, job) values ('14', 'Esteban', 'Ocon', 'France', to_date('1996-09-17', 'YYYY-MM-DD'), '6000000', 'Driver');
insert into TeamMember (employeeId, firstName, lastName, nationality, dateOfBirth, salary, job) values ('15', 'Alex', 'Albon', 'Thailand', to_date('1996-03-23', 'YYYY-MM-DD'), '3000000', 'Driver');
insert into TeamMember (employeeId, firstName, lastName, nationality, dateOfBirth, salary, job) values ('16', 'Pierre', 'Gasly', 'France', to_date('1996-02-07', 'YYYY-MM-DD'), '5000000', 'Driver');
insert into TeamMember (employeeId, firstName, lastName, nationality, dateOfBirth, salary, job) values ('17', 'Carlos', 'Sainz', 'Spain', to_date('1994-09-01', 'YYYY-MM-DD'), '12000000', 'Driver');
insert into TeamMember (employeeId, firstName, lastName, nationality, dateOfBirth, salary, job) values ('18', 'Kevin', 'Magnussen', 'Denmark', to_date('1992-10-05', 'YYYY-MM-DD'), '5000000', 'Driver');
insert into TeamMember (employeeId, firstName, lastName, nationality, dateOfBirth, salary, job) values ('19', 'Sergio', 'Perez', 'Mexico', to_date('1990-01-26', 'YYYY-MM-DD'), '10000000', 'Driver');
insert into TeamMember (employeeId, firstName, lastName, nationality, dateOfBirth, salary, job) values ('20', 'Valtteri', 'Bottas', 'Finland', to_date('1989-08-28', 'YYYY-MM-DD'), '10000000', 'Driver');
insert into TeamMember (employeeId, firstName, lastName, nationality, dateOfBirth, salary, job) values ('21', 'Nico', 'Hulkenberg', 'Germany', to_date('1987-08-19', 'YYYY-MM-DD'), '2000000', 'Driver');
insert into TeamMember (employeeId, firstName, lastName, nationality, dateOfBirth, salary, job) values ('22', 'Lewis', 'Hamilton', 'England', to_date('1985-01-07', 'YYYY-MM-DD'), '37000000', 'Driver');
insert into TeamMember (employeeId, firstName, lastName, nationality, dateOfBirth, salary, job) values ('23', 'Max', 'Verstappen', 'Netherlands', to_date('1997-09-30', 'YYYY-MM-DD'), '50000000', 'Driver');
insert into TeamMember (employeeId, firstName, lastName, nationality, dateOfBirth, salary, job) values ('24', 'Fernando', 'Alonso', 'Spain', to_date('1981-07-29', 'YYYY-MM-DD'), '20000000', 'Driver');

insert into Car (model, engine, constructorName) values ('RB19', 'Red Bull Powertrains - Honda', 'Red Bull Racing');
insert into Car (model, engine, constructorName) values ('SF-23', 'Ferrari', 'Ferrari');
insert into Car (model, engine, constructorName) values ('W14', 'Mercedes', 'Mercedes');
insert into Car (model, engine, constructorName) values ('A523', 'Renault', 'Alpine');
insert into Car (model, engine, constructorName) values ('MCL60', 'Mercedes', 'McLaren');
insert into Car (model, engine, constructorName) values ('C43', 'Ferrari', 'Alfa Romeo');
insert into Car (model, engine, constructorName) values ('AMR23', 'Mercedes', 'Aston Martin');
insert into Car (model, engine, constructorName) values ('VF-23', 'Ferrari', 'Haas');
insert into Car (model, engine, constructorName) values ('AT04', 'Red Bull Powertrains - Honda', 'AlphaTauri');
insert into Car (model, engine, constructorName) values ('FW45', 'Mercedes', 'Williams');

insert into Partner_Ref (partnerId, partnerName, instagramHandle) values ('1', 'Kelly Piquet', 'kellypiquet');
insert into Partner_Ref (partnerId, partnerName, instagramHandle) values ('2', 'Kika Gomes', 'francisca.cgomes');
insert into Partner_Ref (partnerId, partnerName, instagramHandle) values ('3', 'Carmen Montero Mundt', 'carmenmmundt');
insert into Partner_Ref (partnerId, partnerName, instagramHandle) values ('4', 'Lily Zneimer', 'lilyzneimer');
insert into Partner_Ref (partnerId, partnerName, instagramHandle) values ('5', 'Tiffany Cromwell', 'tiffanycromwell');
insert into Partner_Ref (partnerId, partnerName, instagramHandle) values ('6', 'Lily Muni He', 'lilymhe');

insert into Partner_2 (instagramHandle, instagramFollowers) values ('kellypiquet', '1300000');
insert into Partner_2 (instagramHandle, instagramFollowers) values ('francisca.cgomes', '537000');
insert into Partner_2 (instagramHandle, instagramFollowers) values ('carmenmmundt', '309000');
insert into Partner_2 (instagramHandle, instagramFollowers) values ('lilyzneimer', '800');
insert into Partner_2 (instagramHandle, instagramFollowers) values ('tiffanycromwell', '195000');
insert into Partner_2 (instagramHandle, instagramFollowers) values ('lilymhe', '688000');

insert into GrandPrix_Ref (year, circuitName, viewership) values ('2023', 'Bahrain International Circuit', '1300000');
insert into GrandPrix_Ref (year, circuitName, viewership) values ('2023', 'Albert Park Circuit', '2950000');
insert into GrandPrix_Ref (year, circuitName, viewership) values ('2023', 'Monaco', '1790000');
insert into GrandPrix_Ref (year, circuitName, viewership) values ('2023', 'Silverstone Circuit', '2350000');
insert into GrandPrix_Ref (year, circuitName, viewership) values ('2023', 'Marina Bay Street Circuit', '1300000');

insert into GrandPrix_2 (circuitName, city) values ('Bahrain International Circuit', 'Sakhir');
insert into GrandPrix_2 (circuitName, city) values ('Albert Park Circuit', 'Melbourne')
insert into GrandPrix_2 (circuitName, city) values ('Monaco', 'Monte Carlo');
insert into GrandPrix_2 (circuitName, city) values ('Silverstone Circuit', 'Towcester');
insert into GrandPrix_2 (circuitName, city) values ('Marina Bay Street Circuit', 'Marina Bay');
