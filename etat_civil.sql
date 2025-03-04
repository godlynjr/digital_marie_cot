CREATE TABLE `Commune` (
  `id_commune` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL
);

CREATE TABLE `Arrondissement` (
  `id_arrondissement` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `commune_id` INT NOT NULL
);

CREATE TABLE `Person` (
  `id_person` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(50) NOT NULL,
  `last_name` VARCHAR(50) NOT NULL,
  `other_names` VARCHAR(100),
  `date_of_birth` DATE,
  `place_of_birth` VARCHAR(100),
  `sex` CHAR(1),
  `nationality` VARCHAR(50),
  `profession` VARCHAR(100),
  `phone` VARCHAR(20),
  `email` VARCHAR(100),
  `address` VARCHAR(200),
  `father_id` INT,
  `mother_id` INT
);

CREATE TABLE `Role` (
  `id_role` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `role_name` VARCHAR(50) NOT NULL
);

CREATE TABLE `Utilisateur` (
  `id_user` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `hashed_password` VARCHAR(255) NOT NULL,
  `role_id` INT NOT NULL,
  `person_id` INT
);

CREATE TABLE `Birth_Act` (
  `id_birth_act` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `act_number` VARCHAR(50) NOT NULL,
  `date_declaration` DATE NOT NULL,
  `date_of_birth` DATE NOT NULL,
  `time_of_birth` TIME,
  `place_of_birth` VARCHAR(100),
  `sex` CHAR(1),
  `child_id` INT NOT NULL,
  `father_id` INT,
  `mother_id` INT,
  `officier_id` INT,
  `arrondissement_id` INT NOT NULL,
  `father_profession` VARCHAR(100),
  `mother_profession` VARCHAR(100),
  `father_nationality` VARCHAR(50),
  `mother_nationality` VARCHAR(50)
);

CREATE TABLE `Marriage_Act` (
  `id_marriage_act` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `act_number` VARCHAR(50) NOT NULL,
  `date_of_marriage` DATE NOT NULL,
  `place_of_marriage` VARCHAR(100) NOT NULL,
  `spouse1_id` INT NOT NULL,
  `spouse2_id` INT NOT NULL,
  `officier_id` INT,
  `arrondissement_id` INT NOT NULL,
  `regime_matrimonial` VARCHAR(100)
);

CREATE TABLE `Death_Act` (
  `id_death_act` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `act_number` VARCHAR(50) NOT NULL,
  `date_of_death` DATE NOT NULL,
  `time_of_death` TIME,
  `place_of_death` VARCHAR(100),
  `cause_of_death` VARCHAR(255),
  `deceased_id` INT NOT NULL,
  `declarant_id` INT,
  `officier_id` INT,
  `arrondissement_id` INT NOT NULL
);

CREATE TABLE `Witness` (
  `id_witness` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `person_id` INT NOT NULL,
  `act_type` VARCHAR(20) NOT NULL,
  `act_id` INT NOT NULL
);

CREATE UNIQUE INDEX `unique_role` ON `Role` (`role_name`);

CREATE UNIQUE INDEX `unique_username` ON `Utilisateur` (`username`);

ALTER TABLE `Arrondissement` ADD CONSTRAINT `fk_commune` FOREIGN KEY (`commune_id`) REFERENCES `Commune` (`id_commune`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `Person` ADD CONSTRAINT `fk_father_person` FOREIGN KEY (`father_id`) REFERENCES `Person` (`id_person`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `Person` ADD CONSTRAINT `fk_mother_person` FOREIGN KEY (`mother_id`) REFERENCES `Person` (`id_person`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `Utilisateur` ADD CONSTRAINT `fk_role` FOREIGN KEY (`role_id`) REFERENCES `Role` (`id_role`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `Utilisateur` ADD CONSTRAINT `fk_person_user` FOREIGN KEY (`person_id`) REFERENCES `Person` (`id_person`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `Birth_Act` ADD CONSTRAINT `fk_child` FOREIGN KEY (`child_id`) REFERENCES `Person` (`id_person`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `Birth_Act` ADD CONSTRAINT `fk_father_birth` FOREIGN KEY (`father_id`) REFERENCES `Person` (`id_person`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `Birth_Act` ADD CONSTRAINT `fk_mother_birth` FOREIGN KEY (`mother_id`) REFERENCES `Person` (`id_person`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `Birth_Act` ADD CONSTRAINT `fk_officier_birth` FOREIGN KEY (`officier_id`) REFERENCES `Person` (`id_person`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `Birth_Act` ADD CONSTRAINT `fk_arrondissement_birth` FOREIGN KEY (`arrondissement_id`) REFERENCES `Arrondissement` (`id_arrondissement`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `Marriage_Act` ADD CONSTRAINT `fk_spouse1` FOREIGN KEY (`spouse1_id`) REFERENCES `Person` (`id_person`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `Marriage_Act` ADD CONSTRAINT `fk_spouse2` FOREIGN KEY (`spouse2_id`) REFERENCES `Person` (`id_person`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `Marriage_Act` ADD CONSTRAINT `fk_officier_mariage` FOREIGN KEY (`officier_id`) REFERENCES `Person` (`id_person`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `Marriage_Act` ADD CONSTRAINT `fk_arrondissement_marriage` FOREIGN KEY (`arrondissement_id`) REFERENCES `Arrondissement` (`id_arrondissement`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `Death_Act` ADD CONSTRAINT `fk_deceased` FOREIGN KEY (`deceased_id`) REFERENCES `Person` (`id_person`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `Death_Act` ADD CONSTRAINT `fk_declarant` FOREIGN KEY (`declarant_id`) REFERENCES `Person` (`id_person`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `Death_Act` ADD CONSTRAINT `fk_officier_death` FOREIGN KEY (`officier_id`) REFERENCES `Person` (`id_person`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `Death_Act` ADD CONSTRAINT `fk_arrondissement_death` FOREIGN KEY (`arrondissement_id`) REFERENCES `Arrondissement` (`id_arrondissement`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `Witness` ADD CONSTRAINT `fk_witness_person` FOREIGN KEY (`person_id`) REFERENCES `Person` (`id_person`) ON DELETE RESTRICT ON UPDATE CASCADE;
