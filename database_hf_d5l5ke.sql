drop database if exists hf_d5l5ke;
create database hf_d5l5ke
	DEFAULT CHARACTER SET utf8
	DEFAULT COLLATE utf8_general_ci;
    
use hf_d5l5ke;

create table Repterindul (
  id int primary key auto_increment,
  Varos nvarchar(45),
  Nev nvarchar(45)
);

insert into Repterindul (Varos, Nev) values ('Budapest', 'Liszt Ferenc Nemzetközi Repülőtér');
insert into Repterindul (Varos, Nev) values ('London', 'Heathrow');
insert into Repterindul (Varos, Nev) values ('Párizs', 'Charles de Gaulle repülőtér');
insert into Repterindul (Varos, Nev) values ('New York', 'JFK');
insert into Repterindul (Varos, Nev) values ('Frankfurt', 'Airport');

create table Reptererkezik (
  id int primary key auto_increment,
  Varos nvarchar(45),
  Nev nvarchar(45)
);

insert into Reptererkezik (Varos, Nev) values ('Budapest', 'Liszt Ferenc Nemzetközi Repülőtér');
insert into Reptererkezik (Varos, Nev) values ('London', 'Heathrow');
insert into Reptererkezik (Varos, Nev) values ('Párizs', 'Charles de Gaulle repülőtér');
insert into Reptererkezik (Varos, Nev) values ('New York', 'JFK');
insert into Reptererkezik (Varos, Nev) values ('Frankfurt', 'Airport');

create table Repulogep (
  id int primary key auto_increment,
  Gyarto nvarchar(45),
  Tipus nvarchar(45),
  Utasszallito bit,
  Teherszallito bit,
  Szervizben bit,
  Jaratban bit default 0,
  RepterindulId int not null,
  
  foreign key (RepterindulId) references Repterindul(id)
);

insert into Repulogep (Gyarto, Tipus, Utasszallito, Teherszallito, Szervizben, Jaratban, RepterindulId) values ('Airbus', 'A380-800', b'1', b'1', b'0', b'1', 1);
insert into Repulogep (Gyarto, Tipus, Utasszallito, Teherszallito, Szervizben, Jaratban, RepterindulId) values ('Airbus', 'A321neo', b'1', b'0', b'0', b'1', 1);
insert into Repulogep (Gyarto, Tipus, Utasszallito, Teherszallito, Szervizben, Jaratban, RepterindulId) values ('Airbus', 'A320ceo', b'1', b'0', b'1', b'0', 1);
insert into Repulogep (Gyarto, Tipus, Utasszallito, Teherszallito, Szervizben, Jaratban, RepterindulId) values ('Airbus', 'A340-600', b'1', b'1', b'0', b'0', 2);
insert into Repulogep (Gyarto, Tipus, Utasszallito, Teherszallito, Szervizben, Jaratban, RepterindulId) values ('Airbus', 'A350-900', b'1', b'1', b'0', b'1', 2);
insert into Repulogep (Gyarto, Tipus, Utasszallito, Teherszallito, Szervizben, Jaratban, RepterindulId) values ('Boeing', '737 MAX 9', b'1', b'0', b'0', b'1', 3);
insert into Repulogep (Gyarto, Tipus, Utasszallito, Teherszallito, Szervizben, Jaratban, RepterindulId) values ('Airbus', 'A330-300', b'1', b'1', b'1', b'0', 3);
insert into Repulogep (Gyarto, Tipus, Utasszallito, Teherszallito, Szervizben, Jaratban, RepterindulId) values ('Boeing', '777-9', b'1', b'1', b'0', b'1', 4);
insert into Repulogep (Gyarto, Tipus, Utasszallito, Teherszallito, Szervizben, Jaratban, RepterindulId) values ('Boeing', '737-800', b'1', b'0', b'1', b'0', 4);
insert into Repulogep (Gyarto, Tipus, Utasszallito, Teherszallito, Szervizben, Jaratban, RepterindulId) values ('Airbus', 'A380-800', b'1', b'1', b'1', b'0', 5);
insert into Repulogep (Gyarto, Tipus, Utasszallito, Teherszallito, Szervizben, Jaratban, RepterindulId) values ('Airbus', 'A320neo', b'1', b'0', b'0', b'1', 5); 

create table Jarat (
  id int primary key auto_increment,
  RepterindulId int not null,
  ReptererkezikId int not null,
  RepulogepId int not null,
  
  foreign key (RepterindulId) references Repterindul(id),
  foreign key (ReptererkezikId) references Reptererkezik(id),
  foreign key (RepulogepId) references Repulogep(id)
);

insert into Jarat (RepterindulId, ReptererkezikId, RepulogepId) values (1, 2, 2);
insert into Jarat (RepterindulId, ReptererkezikId, RepulogepId) values (1, 4, 1);
insert into Jarat (RepterindulId, ReptererkezikId, RepulogepId) values (2, 4, 5);
insert into Jarat (RepterindulId, ReptererkezikId, RepulogepId) values (3, 2, 6);
insert into Jarat (RepterindulId, ReptererkezikId, RepulogepId) values (4, 2, 8);
insert into Jarat (RepterindulId, ReptererkezikId, RepulogepId) values (5, 1, 11);
