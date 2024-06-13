
USE clinic
CREATE TABLE accounts (
	account_ID int NOT NULL PRIMARY KEY IDENTITY(1,1),
	login varchar(50) NOT NULL unique,
	password varchar(25) NOT NULL,
	admin_permission int,
	doctor_ID int
)


CREATE TRIGGER doctors_uppercase ON doctors
AFTER INSERT
AS
BEGIN
UPDATE doctors
SET doctors.doctor_name = UPPER(LEFT(inserted.doctor_name,1)) + LOWER(SUBSTRING(inserted.doctor_name,2,LEN(inserted.doctor_name))),
doctors.doctor_surname = UPPER(LEFT(inserted.doctor_surname,1)) + LOWER(SUBSTRING(inserted.doctor_surname,2,LEN(inserted.doctor_surname))),
doctors.doctor_city = UPPER(LEFT(inserted.doctor_city,1)) + LOWER(SUBSTRING(inserted.doctor_city,2,LEN(inserted.doctor_city)))
FROM inserted
WHERE doctors.doctor_ID=inserted.doctor_ID
end

CREATE TRIGGER patients_uppercase ON patients
AFTER INSERT
AS
BEGIN
UPDATE patients
SET patients.patient_name = UPPER(LEFT(inserted.patient_name,1)) + LOWER(SUBSTRING(inserted.patient_name,2,LEN(inserted.patient_name))),
patients.patient_surname = UPPER(LEFT(inserted.patient_surname,1)) + LOWER(SUBSTRING(inserted.patient_surname,2,LEN(inserted.patient_surname))),
patients.patient_city = UPPER(LEFT(inserted.patient_city,1)) + LOWER(SUBSTRING(inserted.patient_city,2,LEN(inserted.patient_city)))
FROM inserted
WHERE patients.patient_ID=inserted.patient_ID
end

INSERT INTO accounts(login,password,admin_permission,doctor_ID) VALUES ('marikob','marikob123',1,1)
INSERT INTO accounts(login,password,admin_permission,doctor_ID) VALUES ('maciasp','maciasp123',0,5)


create PROCEDURE account_password_edit
	@account_ID int,
    @password varchar(25)
AS
BEGIN
    UPDATE accounts 
    SET 
	password = @password
    WHERE account_ID = @account_ID
END


create PROCEDURE account_add
	@login varchar(50),
    @password varchar(25),
	@admin_permission int,
	@doctor_ID int
AS
BEGIN
	INSERT INTO accounts(login,password,admin_permission,doctor_ID) VALUES (@login,@password,@admin_permission,@doctor_ID)
END