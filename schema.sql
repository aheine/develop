CREATE TABLE IF NOT EXISTS tblPerson (
  pmkUsername varchar(30) NOT NULL,  
  fldEmail varchar(65) NOT NULL,
  fnkHouseholdName varchar(30) NOT NULL,
  PRIMARY KEY (pmkUsername)
);

CREATE TABLE IF NOT EXISTS tblHousehold (
  pmkHouseholdName varchar(30) NOT NULL,  
  PRIMARY KEY (pmkHouseholdName)
);

CREATE TABLE IF NOT EXISTS tblBills (
  fldType char(30) NOT NULL,  
  fldPaid int(1) NOT NULL,
  fldLost int(1) NOT NULL,
  fldAmount int(50) NOT NULL,
  fldMonth char(50) NOT NULL,
  fnkHouseholdName varchar(30) NOT NULL, 
  fnkUsername varchar(30) NOT NULL,
);


