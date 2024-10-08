/*
+--------------------------------------------+----------+
|            Membres de l'Équipe             |  Projet  |
+--------------------------------------------+----------+
| Amélie Bouchard | Antony Collin-Desrochers | KNAPSACK |
| David Bérubé    | Samy Tétrault            |          |
+--------------------------------------------+----s------+


+---+------------+---+
| | | Procédures | | |
+---+------------+---+

+----------------------+
| Items et leurs types |
+----------------------+
*/
#Ajouter un Item à Inventaire
DELIMITER $$
CREATE PROCEDURE AjouterItemInventaire
(quantiteP int(11), Items_IdItemsP int(11), Joueurs_IdJoueurP int(11))
BEGIN
	IF quantiteP > 0 AND EXISTS (SELECT IdJoueur FROM Joueurs WHERE IdJoueur = Joueurs_IdJoueurP) AND EXISTS (SELECT IdItems FROM Items WHERE IdItems = Items_IdItemsP)
    THEN
        SELECT COUNT(Items_IdItems) INTO @nbItem FROM Inventaire WHERE Items_IdItems = Items_IdItemsP AND Joueurs_IdJoueur = Joueurs_IdJoueurP;
		IF(@nbItem = 0) THEN
			INSERT INTO Inventaire(quantite, date, Items_IdItems, Joueurs_IdJoueur) VALUES (quantiteP,NOW(),Items_IdItemsP,Joueurs_IdJoueurP);
        ELSE
        	UPDATE Inventaire SET quantite = quantite + quantiteP WHERE Items_IdItems = Items_IdItemsP AND Joueurs_IdJoueur = Joueurs_IdJoueurP;
		END IF;
		COMMIT;
        ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'valeurs mauvaises';
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
#Types d'items
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



#Afficher Panier
DELIMITER $$
CREATE PROCEDURE AfficherPanier (pAlias VARCHAR(30))
BEGIN
	IF EXISTS (SELECT IdJoueur FROM Joueurs WHERE alias = pAlias)
    THEN
			SELECT Items_IdItems, qteAchat, Items.nom
            FROM Panier 
            INNER JOIN Items ON Panier.Items_IdItems = Items.IdItems	
            INNER JOIN Joueurs ON Panier.Joueurs_IdJoueur = Joueurs.IdJoueur
            WHERE alias = pAlias;
        ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Le joueur existe pas';
	END IF;    
END $$
#call AfficherPanier('madzcandy');

#Afficher Inventaire
DELIMITER $$
CREATE PROCEDURE AfficherInventaire
(IdJoueurP int(11))
BEGIN
	IF EXISTS (SELECT IdJoueur FROM Joueurs WHERE IdJoueur = IdJoueurP)
    THEN
		SELECT Inventaire.quantite, date, Items_IdItems, nom
        FROM Inventaire 
        INNER JOIN Items ON Inventaire.Items_IdItems = IdItems	
        WHERE Joueurs_IdJoueur = IdJoueurP;        
	ELSE
	SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Le joueur existe pas';
	END IF;    
END $$



#Montant total panier
DELIMITER ;;
CREATE FUNCTION MontantTotalPanier (IdJoueur int(11)) RETURNS float
BEGIN
DECLARE total float;
	SET total = (SELECT SUM(prixUnitaire * qteAchat) FROM Items INNER JOIN Panier ON Items.IdItems = Panier.Items_IdItems WHERE Joueurs_IdJoueur = IdJoueur); # * nb items
    RETURN total;
END ;;

#Afficher toute infos joueur selon alias meme id
DELIMITER $$
CREATE PROCEDURE AfficherInfosJoueur
(aliasP varchar(30))
BEGIN
	IF EXISTS (SELECT IdJoueur FROM Joueurs WHERE alias = aliasP)
    THEN
			SELECT * FROM Joueurs WHERE alias = aliasP;
        ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Le joueur existe pas';
	END IF;    
END $$

#Trigger qui s'assure qu'on achete pas une quantite plus grande que le stock
delimiter |
create trigger QuantityControlTrigger
before insert on Inventaire
for each row
begin
            IF new.quantite > (SELECT quantite FROM Items WHERE IdItems = new.Items_IdItems)
            THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La quantité que vous tentez dacheter est plus eleve que le stock';
            END IF;
end |

#Trigger qui s'assure qu'on ne mette pas dans le panier une quantite plus grande que le stock #modifier pour le poids et dex
delimiter |
create trigger QuantityControlCartTrigger
before insert on Panier
for each row
begin
            IF new.qteAchat > (SELECT quantite FROM Items WHERE IdItems = new.Items_IdItems)
            THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La quantité que vous tentez de mettre dans le panier est plus eleve que le stock';
            END IF;
end |

#Trigger qui update le stock quand un item est acheter  #modifier pour le poids et dex
delimiter |
create trigger UpdateStockTrigger
after insert on Inventaire
for each row
begin
            UPDATE Items
            SET quantite = quantite - new.quantite
            WHERE IdItems = new.Items_IdItems;
end |



CALL AjouterItemInventaire(0, 36, 13);
CALL AfficherInventaire(13);
CALL AfficherInfosJoueur('madzcandy');
INSERT INTO Panier (Joueurs_IdJoueur, qteAchat , Items_IdItems) VALUES (13, 90, 37);
SELECT * FROM Panier;

select * from Items;
select * from Munitions;
select * from Joueurs;

TRUNCATE TABLE Evaluations;
TRUNCATE TABLE Panier;
TRUNCATE TABLE Inventaire;

DELETE FROM Munitions WHERE Items_IdItems > 0;
TRUNCATE TABLE Armes;
TRUNCATE TABLE Armures;
TRUNCATE TABLE Nourriture;
TRUNCATE TABLE Medicament;
TRUNCATE TABLE Munitions;
DELETE FROM Items WHERE IdItems > 0;
DELETE FROM Joueurs WHERE IdJoueur > 0;


