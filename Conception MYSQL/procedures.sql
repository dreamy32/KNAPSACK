/*
+--------------------------------------------+----------+
|            Membres de l'Équipe             |  Projet  |
+--------------------------------------------+----------+
| Amélie Bouchard | Antony Collin-Desrochers | KNAPSACK |
| David Bérubé    | Samy Tétrault            |          |
+--------------------------------------------+----------+
*/ 

#Ajouter Armure
DELIMITER $$
CREATE PROCEDURE AjouterArmure
(nom VARCHAR(45),quantite int(11), type char(1), prixUnitaire decimal(10,0), poids float, description varchar(120), estEnVente bit(1), matiere varchar(30), taille int(11))
BEGIN
	IF TRIM(nom) != '' AND quantite >= 0 AND TRIM(type) != '' AND prixUnitaire > 0 AND poids > 0 AND (estEnVente = 0 OR estEnVente = 1) AND TRIM(matiere) != '' AND taille > 0
    THEN
			INSERT INTO Items(nom, quantite, type, prixUnitaire, poids, description, estEnVente)
			VALUES(nom, quantite, type, prixUnitaire,poids, description, estEnVente);
			SET @IdItems = LAST_INSERT_ID();
			INSERT INTO Armures(Items_IdItems, taille, matiere)
			VALUES(@IdItems, taille, matiere);
	COMMIT;
        ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Les valeurs sont mauvaises';
	END IF;    
END $$

#Afficher objets en vente sans les détails de son type
DELIMITER $$
CREATE PROCEDURE AfficherItemsVente()
BEGIN
	SELECT nom, quantite, type, prixUnitaire, poids, description from Items where estEnVente = 1;
END $$

#Afficher les details selon le type d'un item
DELIMITER $$
CREATE PROCEDURE AfficherItemDetails(IdItemsP int(11))
BEGIN
DECLARE typeItem char(1);
	IF IdItemsP > 0 THEN
			SET typeItem = (SELECT type FROM Items WHERE IdItems = IdItemsP);

            IF typeItem = 'W' THEN
            SELECT efficacite, genre, calibre FROM Armes INNER JOIN Munitions 
            ON Armes.Munitions_Items_IdItems = Munitions.Items_IdItems WHERE Armes.Items_IdItems = IdItemsP;
            END IF;
            
            IF typeItem = 'D' THEN
            SELECT effet, dureeEffet FROM Medicament WHERE Items_IdItems = IdItemsP;
            END IF;
            
            IF typeItem = 'A' THEN
            SELECT matiere, taille FROM Armures WHERE Items_IdItems = IdItemsP;
            END IF;
            
            IF typeItem = 'N' THEN
            SELECT pointDeVie FROM Nourriture WHERE Items_IdItems = IdItemsP;
            END IF;

            IF typeItem = 'M' THEN
            SELECT calibre FROM Munitions WHERE Items_IdItems = IdItemsP;
            END IF;
    ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Le id item est introuvable';
	END IF;    
END $$

/*
#Supprimer Item    FAIRE LE TRIGGER AVANT
DELIMITER $$
CREATE PROCEDURE SupprimerItem(IdItemsP int(11))
BEGIN
DECLARE typeItem char(1);
	IF IdItemsP > 0 THEN
			SET typeItem = (SELECT type FROM Items WHERE IdItems = IdItemsP);

            IF typeItem = 'W' THEN
            DELETE FROM Armes WHERE Items_IdItems = IdItemsP;
            DELETE FROM Items WHERE IdItems = IdItemsP;
            END IF;
            
            IF typeItem = 'D' THEN
            DELETE FROM Armures WHERE Items_IdItems = IdItemsP;
            DELETE FROM Items WHERE IdItems = IdItemsP;
            END IF;
            
            IF typeItem = 'A' THEN
            DELETE FROM Nourriture WHERE Items_IdItems = IdItemsP;
            DELETE FROM Items WHERE IdItems = IdItemsP;
            END IF;
            
            IF typeItem = 'N' THEN
            DELETE FROM Munitions WHERE Items_IdItems = IdItemsP;
            DELETE FROM Items WHERE IdItems = IdItemsP;
            END IF;

            IF typeItem = 'M' THEN
            DELETE FROM Medicament WHERE Items_IdItems = IdItemsP;
            DELETE FROM Items WHERE IdItems = IdItemsP;
            END IF;
            COMMIT;
    ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Le id item est introuvable';
	END IF;    
END $$*/

#Ajouter Medicament
DELIMITER $$
CREATE PROCEDURE AjouterMedicament
(nom VARCHAR(45),quantite int(11), type char(1), prixUnitaire decimal(10,0), poids float, description varchar(120), estEnVente bit(1), effet varchar(45), dureeEffet float)
BEGIN
	IF TRIM(nom) != '' AND quantite >= 0 AND TRIM(type) != '' AND prixUnitaire > 0 AND poids > 0 AND (estEnVente = 0 OR estEnVente = 1) AND TRIM(effet) != '' AND dureeEffet > 0
    THEN
			INSERT INTO Items(nom, quantite, type, prixUnitaire, poids, description, estEnVente)
			VALUES(nom, quantite, type, prixUnitaire,poids, description, estEnVente);
			SET @IdItems = LAST_INSERT_ID();
			INSERT INTO Medicament(Items_IdItems, effet, dureeEffet)
			VALUES(@IdItems, effet, dureeEffet);
	COMMIT;
        ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Les valeurs sont mauvaises';
	END IF;    
END $$

#Ajouter Nourriture
DELIMITER $$
CREATE PROCEDURE AjouterNourriture
(nom VARCHAR(45),quantite int(11), type char(1), prixUnitaire decimal(10,0), poids float, description varchar(120), estEnVente bit(1), pointDeVie int(11))
BEGIN
	IF TRIM(nom) != '' AND quantite >= 0 AND TRIM(type) != '' AND prixUnitaire > 0 AND poids > 0 AND (estEnVente = 0 OR estEnVente = 1) AND pointDeVie > 0
    THEN
			INSERT INTO Items(nom, quantite, type, prixUnitaire, poids, description, estEnVente)
			VALUES(nom, quantite, type, prixUnitaire,poids, description, estEnVente);
			SET @IdItems = LAST_INSERT_ID();
			INSERT INTO Nourriture(Items_IdItems, pointDeVie)
			VALUES(@IdItems, pointDeVie);
	COMMIT;
        ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Les valeurs sont mauvaises';
	END IF;    
END $$

#Ajouter Munitions
DELIMITER $$
CREATE PROCEDURE AjouterMunitions
(nom VARCHAR(45),quantite int(11), type char(1), prixUnitaire decimal(10,0), poids float, description varchar(120), estEnVente bit(1), calibre float)
BEGIN
	IF TRIM(nom) != '' AND quantite >= 0 AND TRIM(type) != '' AND prixUnitaire > 0 AND poids > 0 AND (estEnVente = 0 OR estEnVente = 1) AND calibre > 0
    THEN
			INSERT INTO Items(nom, quantite, type, prixUnitaire, poids, description, estEnVente)
			VALUES(nom, quantite, type, prixUnitaire,poids, description, estEnVente);
			SET @IdItems = LAST_INSERT_ID();
			INSERT INTO Munitions(Items_IdItems, calibre)
			VALUES(@IdItems, calibre);
	COMMIT;
        ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Les valeurs sont mauvaises';
	END IF;    
END $$

#Ajouter Munitions
DELIMITER $$
CREATE PROCEDURE AjouterMunitions
(nom VARCHAR(45),quantite int(11), type char(1), prixUnitaire decimal(10,0), poids float, description varchar(120), estEnVente bit(1), calibre float)
BEGIN
	IF TRIM(nom) != '' AND quantite >= 0 AND TRIM(type) != '' AND prixUnitaire > 0 AND poids > 0 AND (estEnVente = 0 OR estEnVente = 1) AND calibre > 0
    THEN
			INSERT INTO Items(nom, quantite, type, prixUnitaire, poids, description, estEnVente)
			VALUES(nom, quantite, type, prixUnitaire,poids, description, estEnVente);
			SET @IdItems = LAST_INSERT_ID();
			INSERT INTO Munitions(Items_IdItems, calibre)
			VALUES(@IdItems, calibre);
	COMMIT;
        ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Les valeurs sont mauvaises';
	END IF;    
END $$

#Ajouter Armes
DELIMITER $$
CREATE PROCEDURE AjouterArmes
(nom VARCHAR(45),quantite int(11), type char(1), prixUnitaire decimal(10,0), poids float, description varchar(120), estEnVente bit(1), efficacite int(11), genre varchar(45), Munitions_Items_IdItems int(11))
BEGIN
	IF TRIM(nom) != '' AND quantite >= 0 AND TRIM(type) != '' AND prixUnitaire > 0 AND poids > 0 AND (estEnVente = 0 OR estEnVente = 1) AND efficacite > 0 AND TRIM(genre) != '' AND Munitions_Items_IdItems > 0
    THEN
			INSERT INTO Items(nom, quantite, type, prixUnitaire, poids, description, estEnVente)
			VALUES(nom, quantite, type, prixUnitaire,poids, description, estEnVente);
			SET @IdItems = LAST_INSERT_ID();
			INSERT INTO Armes(Items_IdItems, efficacite, genre, Munitions_Items_IdItems)
			VALUES(@IdItems, efficacite, genre, Munitions_Items_IdItems);
	COMMIT;
        ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Les valeurs sont mauvaises';
	END IF;    
END $$


/*
call AfficherItemsVente();
call AjouterArmure('Item6', 69, 'A', 69, 71, 'description basique', 1, 'antozMatiere', 69);
call AjouterMedicament('med', 10, 'D', 10, 71, 'description basique', 1, 'boost de vie', 10);
call AjouterNourriture('bouffe', 10, 'N', 10, 71, 'description basique', 1, 2);
call AjouterMunitions('munition', 10, 'N', 10, 71, 'description basique', 1, 5.56);
call AjouterArmes('arme', 10, 'W', 10, 71, 'description basique', 1, 5, 'lourd', 30);
call SupprimerItem(28);
        
select * from Items inner join Armes on Items.IdItems = Armes.Items_IdItems;

#select * from Items inner join Armures on Items.IdItems = Armures.Items_IdItems;
select * from Items inner join Medicament on Items.IdItems = Medicament.Items_IdItems;
select * from Items inner join Nourriture on Items.IdItems = Nourriture.Items_IdItems;
select * from Items inner join Munitions on Items.IdItems = Munitions.Items_IdItems;
select * from Items inner join Armes on Items.IdItems = Armes.Items_IdItems;
SELECT * FROM Medicament;
select * from Items;*/