/*
+--------------------------------------------+----------+
|            Membres de l'Équipe             |  Projet  |
+--------------------------------------------+----------+
| Amélie Bouchard | Antony Collin-Desrochers | KNAPSACK |
| David Bérubé    | Samy Tétrault            |          |
+--------------------------------------------+----s------+
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

#Supprimer Item
DELIMITER $$
CREATE PROCEDURE SupprimerItem(IdItemsP int(11))
BEGIN
DECLARE typeItem char(1);
	IF IdItemsP > 0 THEN
			DELETE FROM Items WHERE IdItems = IdItemsP;
    ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Le id item est invalide';
	END IF;    
END $$

#Trigger Supprimer
delimiter |
create trigger SupprimerItemTrigger
before delete on Items
for each row
begin
            IF old.type = 'W' THEN
            DELETE FROM Armes WHERE Items_IdItems = old.IdItems;
            END IF;
            
            IF old.type = 'A' THEN
            DELETE FROM Armures WHERE Items_IdItems = old.IdItems;
            END IF;
            
            IF old.type = 'N' THEN
            DELETE FROM Nourriture WHERE Items_IdItems = old.IdItems;
            END IF;
            
            IF old.type = 'M' THEN
            DELETE FROM Munitions WHERE Items_IdItems = old.IdItems;
            END IF;

            IF old.type = 'D' THEN
            DELETE FROM Medicament WHERE Items_IdItems = old.IdItems;
            END IF;
            
            IF EXISTS (SELECT Items_IdItems FROM Evaluations WHERE Items_IdItems = old.IdItems) THEN
            DELETE FROM Evaluations WHERE Items_IdItems = old.IdItems;
            END IF;
            
            IF EXISTS (SELECT Items_IdItems FROM Inventaire WHERE Items_IdItems = old.IdItems) THEN
            DELETE FROM Inventaire WHERE Items_IdItems = old.IdItems;
            END IF;
            
            IF EXISTS (SELECT Items_IdItems FROM Panier WHERE Items_IdItems = old.IdItems) THEN
            DELETE FROM Panier WHERE Items_IdItems = old.IdItems;
            END IF;
end |

#Ajouter Évaluation
DELIMITER $$
CREATE PROCEDURE AjouterÉvaluation
(Items_IdItemsP int(11),Joueurs_IdJoueurP int(11), commentaire varchar(300), nbEtoiles int(5))
BEGIN
	IF EXISTS (SELECT IdItems FROM Items WHERE IdItems = Items_IdItemsP) AND EXISTS (SELECT IdJoueur FROM Joueurs WHERE IdJoueur = Joueurs_IdJoueurP) AND TRIM(commentaire) != '' AND nbEtoiles >= 0 AND nbEtoiles <= 5 
    THEN
			INSERT INTO Evaluations(Items_IdItems,Joueurs_IdJoueur, commentaire, nbEtoiles)
			VALUES(Items_IdItemsP,Joueurs_IdJoueurP, commentaire, nbEtoiles);
	COMMIT;
        ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Les valeurs sont mauvaises';
	END IF;    
END $$

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
