drop table Sponsor cascade constraints; 
drop table Constructor cascade constraints;  
drop table TeamMember cascade constraints; 
drop table Car cascade constraints; 
drop table Partner_Ref cascade constraints; 
drop table Partner_2 cascade constraints; 
drop table GrandPrix_Ref cascade constraints; 
drop table GrandPrix_2 cascade constraints; 

-- does this need cascade constraints? 
drop table GrandPrix_DriverStanding_2 cascade constraints; 
drop table Driver cascade constraints;  
drop table Sponsors cascade constraints;  
drop table WorksWith cascade constraints; 
drop table Drives cascade constraints; 
drop table InRelationshipWith cascade constraints; 
drop table ConstructorHolds cascade constraints; 
drop table DriverHolds cascade constraints; 

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

-- Statements 17-24
create table GrandPrix_DriverStanding_2(
    racePosition		int, 
	gpName				varchar(40), 
	year				int, 
	qualifyingPosition	int, 
	PRIMARY KEY (racePosition, gpName, year), 
	FOREIGN KEY (gpName, year) REFERENCES GrandPrix_5(gpName, year), 
	FOREIGN KEY (racePosition) REFERENCES GrandPrix_DriverStanding_Ref(racePosition)
);

create table Driver (
	employeeId			int				PRIMARY KEY, 
	#OfPodiums			int				DEFAULT 0 NOT NULL, 
	#OfWins 			int				DEFAULT 0 NOT NULL, 
	driverNumber		int 			NOT NULL, 
	#OfPolePositions 	int				DEFAULT 0 NOT NULL, 
	FOREIGN KEY (employeeId) REFERENCES TeamMember(employeeId)
);

create table Sponsors (
	companyName			varchar(40), 
	constructorName		varchar(40), 
	sponsorshipAmount	int, 
	PRIMARY KEY (companyName, constructorName), 
	FOREIGN KEY (companyName) REFERENCES Sponsor(companyName), 
	FOREIGN KEY (constructorName) REFERENCES Constructor(constructorName)
); 

create table WorksWith (
	constructorName 	varchar(40), 
	employeeId			int, 
	since				date, 
	PRIMARY KEY (constructorName, employeeId), 
	FOREIGN KEY (constructorName) REFERENCES Constructor(constructorName), 
	FOREIGN KEY (employeeId) REFERENCES TeamMember(employeeId)
);

create table Drives (
	model 				varchar(40), 
	employeeId			int, 
	PRIMARY KEY (model, employeeId), 
	FOREIGN KEY (model) REFERENCES Car(model), 
	FOREIGN KEY (employeeId) REFERENCES Driver(employeeId)
);

create table InRelationshipWith (
	partnerId			int, 
	employeeId			int, 
	since				date, 
	instagramHandle 	varchar(40), 
	instagramFollowers	int, 
	PRIMARY KEY (partnerId, employeeId), 
	FOREIGN KEY (partnerId) REFERENCES Partner_Ref(partnerId), 
	FOREIGN KEY (employeeId) REFERENCES Driver(employeeId)
);

create table ConstructorHolds (
	position 			int, 
	gpName				varchar(40), 
	year				int, 
	constructorName		varchar(40), 
	PRIMARY KEY (position, gpName, year, constructorName), 
	FOREIGN KEY (gpName, year, position) REFERENCES GrandPrix_ConstructorStanding(gpName, year, position)
		ON DELETE CASCADE, 
	FOREIGN KEY (constructorName) REFERENCES Constructor(constructorName)
		ON DELETE CASCADE
); 

create table DriverHolds (
	racePosition		int, 
	gpName				varchar(40), 
	year				int, 
	employeeId			varchar(40),
	PRIMARY KEY (racePosition, gpName, year, employeeId), 
	FOREIGN KEY (gpName, year, racePosition) REFERENCES GrandPrix_DriverStanding_2(gpName, year, racePosition)
		ON DELETE CASCADE, 
	FOREIGN KEY (employeeId) REFERENCES Driver(employeeId)
		ON DELETE CASCADE
); 

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

-- Statements 17-24
insert into GrandPrix_DriverStanding_2 (racePosition, gpName, year, qualifyingPosition) values ('1', 'Singapore Grand Prix', '2023', '1'); 
insert into GrandPrix_DriverStanding_2 (racePosition, gpName, year, qualifyingPosition) values ('4', 'Singapore Grand Prix', '2023', '3'); 
insert into GrandPrix_DriverStanding_2 (racePosition, gpName, year, qualifyingPosition) values ('2', 'British Grand Prix', '2023', '2'); 
insert into GrandPrix_DriverStanding_2 (racePosition, gpName, year, qualifyingPosition) values ('4', 'British Grand Prix', '2023', '3'); 
insert into GrandPrix_DriverStanding_2 (racePosition, gpName, year, qualifyingPosition) values ('3', 'British Grand Prix', '2023', '6'); 
insert into GrandPrix_DriverStanding_2 (racePosition, gpName, year, qualifyingPosition) values ('4', 'British Grand Prix', '2023', '7'); 
insert into GrandPrix_DriverStanding_2 (racePosition, gpName, year, qualifyingPosition) values ('1', 'Monaco Grand Prix', '2023', '1'); 
insert into GrandPrix_DriverStanding_2 (racePosition, gpName, year, qualifyingPosition) values ('16', 'Monaco Grand Prix', '2023', '20'); 
insert into GrandPrix_DriverStanding_2 (racePosition, gpName, year, qualifyingPosition) values ('3', 'Australian Grand Prix', '2023', '4'); 
insert into GrandPrix_DriverStanding_2 (racePosition, gpName, year, qualifyingPosition) values ('4', 'Australian Grand Prix', '2023', '6'); 

insert into Driver (employeeId, #OfPodiums, #OfWins, driverNumber, #OfPolePositions) values ('6', '2', '0', '81', '1'); 
insert into Driver (employeeId, #OfPodiums, #OfWins, driverNumber, #OfPolePositions) values ('7', '0', '0', '2', '0'); 
insert into Driver (employeeId, #OfPodiums, #OfWins, driverNumber, #OfPolePositions) values ('8', '0', '0', '22', '0'); 
insert into Driver (employeeId, #OfPodiums, #OfWins, driverNumber, #OfPolePositions) values ('9', '11', '0', '4', '1'); 
insert into Driver (employeeId, #OfPodiums, #OfWins, driverNumber, #OfPolePositions) values ('10', '0', '0', '24', '0'); 
insert into Driver (employeeId, #OfPodiums, #OfWins, driverNumber, #OfPolePositions) values ('11', '3', '0', '18', '1'); 
insert into Driver (employeeId, #OfPodiums, #OfWins, driverNumber, #OfPolePositions) values ('12', '10', '1', '63', '1'); 
insert into Driver (employeeId, #OfPodiums, #OfWins, driverNumber, #OfPolePositions) values ('13', '27', '5', '16', '19'); 
insert into Driver (employeeId, #OfPodiums, #OfWins, driverNumber, #OfPolePositions) values ('14', '3', '1', '31', '0'); 
insert into Driver (employeeId, #OfPodiums, #OfWins, driverNumber, #OfPolePositions) values ('15', '2', '0', '23', '0'); 
insert into Driver (employeeId, #OfPodiums, #OfWins, driverNumber, #OfPolePositions) values ('16', '4', '1', '10', '0'); 
insert into Driver (employeeId, #OfPodiums, #OfWins, driverNumber, #OfPolePositions) values ('17', '17', '2', '55', '5'); 
insert into Driver (employeeId, #OfPodiums, #OfWins, driverNumber, #OfPolePositions) values ('18', '1', '0', '20', '1'); 
insert into Driver (employeeId, #OfPodiums, #OfWins, driverNumber, #OfPolePositions) values ('19', '34', '6', '11', '3'); 
insert into Driver (employeeId, #OfPodiums, #OfWins, driverNumber, #OfPolePositions) values ('20', '67', '10', '77', '20'); 
insert into Driver (employeeId, #OfPodiums, #OfWins, driverNumber, #OfPolePositions) values ('21', '0', '0', '27', '1'); 
insert into Driver (employeeId, #OfPodiums, #OfWins, driverNumber, #OfPolePositions) values ('22', '196', '103', '44', '104'); 
insert into Driver (employeeId, #OfPodiums, #OfWins, driverNumber, #OfPolePositions) values ('23', '93', '49', '1', '30'); 
insert into Driver (employeeId, #OfPodiums, #OfWins, driverNumber, #OfPolePositions) values ('24', '105', '32', '14', '22'); 

insert into Sponsors (companyName, constructorName, sponsorshipAmount) values ('Oracle', 'Red Bull Racing', '500000000');
insert into Sponsors (companyName, constructorName, sponsorshipAmount) values ('Zoom', 'Red Bull Racing', '150000000');
insert into Sponsors (companyName, constructorName, sponsorshipAmount) values ('Tommy Hilfiger', 'Mercedes', '50000000');
insert into Sponsors (companyName, constructorName, sponsorshipAmount) values ('Monster Energy', 'Mercedes', '85000000');
insert into Sponsors (companyName, constructorName, sponsorshipAmount) values ('Ray-Ban', 'Ferrari', '290000000');
insert into Sponsors (companyName, constructorName, sponsorshipAmount) values ('Shell', 'Ferrari', '350000000');
insert into Sponsors (companyName, constructorName, sponsorshipAmount) values ('Chrome', 'McLaren', '420000000');
insert into Sponsors (companyName, constructorName, sponsorshipAmount) values ('Michelob Ultra', 'Williams', '41000000');
insert into Sponsors (companyName, constructorName, sponsorshipAmount) values ('Tik Tok', 'Aston Martin', NULL);

insert into WorksWith (constructorName, employeeId, since) values ('Red Bull Racing', '2', '2005-01-01'); 
insert into WorksWith (constructorName, employeeId, since) values ('Mercedes', '1', '2013-01-01');
insert into WorksWith (constructorName, employeeId, since) values ('McLaren', '3', '2016-11-01'); 
insert into WorksWith (constructorName, employeeId, since) values ('Red Bull Racing', '4', '2009-11-01'); 
insert into WorksWith (constructorName, employeeId, since) values ('Mercedes', '5', '2011-09-01'); 
insert into WorksWith (constructorName, employeeId, since) values ('McLaren', '6', '2023-03-05'); 
insert into WorksWith (constructorName, employeeId, since) values ('Williams', '7', '2023-03-05'); 
insert into WorksWith (constructorName, employeeId, since) values ('AlphaTauri', '8', '2021-03-28'); 
insert into WorksWith (constructorName, employeeId, since) values ('McLaren', '9', '2019-03-17'); 
insert into WorksWith (constructorName, employeeId, since) values ('Alfa Romeo', '10', '2022-03-20'); 
insert into WorksWith (constructorName, employeeId, since) values ('Aston Martin', '11', '2017-03-26'); 
insert into WorksWith (constructorName, employeeId, since) values ('Mercedes', '12', '2019-03-17'); 
insert into WorksWith (constructorName, employeeId, since) values ('Ferrari', '13', '2018-03-25'); 
insert into WorksWith (constructorName, employeeId, since) values ('Alpine', '14', '2016-08-28'); 
insert into WorksWith (constructorName, employeeId, since) values ('Williams', '15', '2019-03-17'); 
insert into WorksWith (constructorName, employeeId, since) values ('Alpine', '16', '2017-10-01'); 
insert into WorksWith (constructorName, employeeId, since) values ('Ferrari', '17', '2015-03-15'); 
insert into WorksWith (constructorName, employeeId, since) values ('Haas', '18', '2014-03-16'); 
insert into WorksWith (constructorName, employeeId, since) values ('Red Bull Racing', '19', '2011-03-27'); 
insert into WorksWith (constructorName, employeeId, since) values ('Alfa Romeo', '20', '2013-03-17'); 
insert into WorksWith (constructorName, employeeId, since) values ('Haas', '21', '2010-03-14'); 
insert into WorksWith (constructorName, employeeId, since) values ('Mercedes', '22', '2007-03-28'); 
insert into WorksWith (constructorName, employeeId, since) values ('Red Bull Racing', '23', '2015-03-15'); 
insert into WorksWith (constructorName, employeeId, since) values ('Aston Martin', '24', '2001-03-04'); 

insert into Drives (model, employeeId) values ('W14', '1'); 
insert into Drives (model, employeeId) values ('W14', '22'); 
insert into Drives (model, employeeId) values ('RB19', '19'); 
insert into Drives (model, employeeId) values ('RB19', '23'); 
insert into Drives (model, employeeId) values ('SF-23', '17'); 
insert into Drives (model, employeeId) values ('SF-23', '13'); 
insert into Drives (model, employeeId) values ('A523', '14'); 
insert into Drives (model, employeeId) values ('A523', '16'); 
insert into Drives (model, employeeId) values ('MCL60', '3'); 
insert into Drives (model, employeeId) values ('MCL60', '6'); 
insert into Drives (model, employeeId) values ('C43', '10'); 
insert into Drives (model, employeeId) values ('C43', '20'); 
insert into Drives (model, employeeId) values ('AMR23', '24'); 
insert into Drives (model, employeeId) values ('AMR23', '11'); 
insert into Drives (model, employeeId) values ('WF-23', '18'); 
insert into Drives (model, employeeId) values ('WF-23', '21'); 
insert into Drives (model, employeeId) values ('AT04', '8'); 
insert into Drives (model, employeeId) values ('FW45', '15'); 
insert into Drives (model, employeeId) values ('FW45', '7'); 

insert into InRelationshipWith (partnerId, employeeId, since, instagramFollowers) values ('1', '23', '2020-03-05', 'kellypiquet', '1300000'); 
insert into InRelationshipWith (partnerId, employeeId, since, instagramFollowers) values ('2', '16', '2022-09-13', 'francisca.cgomes', '537000'); 
insert into InRelationshipWith (partnerId, employeeId, since, instagramFollowers) values ('3', '12', '2020-06-20', 'carmenmmundt', '309000'); 
insert into InRelationshipWith (partnerId, employeeId, since, instagramFollowers) values ('4', '6', '2019-01-16', 'lilyzneimer', '880'); 
insert into InRelationshipWith (partnerId, employeeId, since, instagramFollowers) values ('5', '20', '2020-03-29', 'tiffanycromwell', '195000'); 
insert into InRelationshipWith (partnerId, employeeId, since, instagramFollowers) values ('6', '15', '2019-04-12', 'lilymhe', '688000'); 

insert into ConstructorHolds (position, gpName, year, constructorName) values ('1', 'Singapore Grand Prix', '2023', 'Ferrari'); 
insert into ConstructorHolds (position, gpName, year, constructorName) values ('2', 'British Grand Prix', '2023', 'McLaren'); 
insert into ConstructorHolds (position, gpName, year, constructorName) values ('3', 'British Grand Prix', '2023', 'Mercedes'); 
insert into ConstructorHolds (position, gpName, year, constructorName) values ('1', 'Monaco Grand Prix', '2023', 'Red Bull Racing'); 
insert into ConstructorHolds (position, gpName, year, constructorName) values ('2', 'Aston Martin', 'Australian Grand Prix', '2023', 'Aston Martin'); 

insert into DriverHolds (racePosition, gpName, year, employeeId) values ('1', 'Singapore Grand Prix', '2023', '17'); 
insert into DriverHolds (racePosition, gpName, year, employeeId) values ('4', 'Singapore Grand Prix', '2023', '13'); 
insert into DriverHolds (racePosition, gpName, year, employeeId) values ('2', 'British Grand Prix', '2023', '9'); 
insert into DriverHolds (racePosition, gpName, year, employeeId) values ('4', 'British Grand Prix', '2023', '6'); 
insert into DriverHolds (racePosition, gpName, year, employeeId) values ('3', 'British Grand Prix', '2023', '22'); 
insert into DriverHolds (racePosition, gpName, year, employeeId) values ('4', 'British Grand Prix', '2023', '12'); 
insert into DriverHolds (racePosition, gpName, year, employeeId) values ('1', 'Monaco Grand Prix', '2023', '23'); 
insert into DriverHolds (racePosition, gpName, year, employeeId) values ('16', 'Monaco Grand Prix', '2023', '19'); 
insert into DriverHolds (racePosition, gpName, year, employeeId) values ('3', 'Australian Grand Prix', '2023', '24'); 
insert into DriverHolds (racePosition, gpName, year, employeeId) values ('4', 'Australian Grand Prix', '2023', '11'); 


