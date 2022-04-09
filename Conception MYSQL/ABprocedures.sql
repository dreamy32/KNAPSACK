use KNAPSACKDB;


call AfficherPanier(13);
call AjouterItemPanier("madzcandy", 2, 38);
call AjouterItemPanier("madzcandy", 1, 41);
call AjouterJoueur("coco23", "1234", 'pop', "corn", "popCocorn", 23, 37, 100);



# Procédure qui ajoute un item au panier =============================================================================================
DELIMITER $$
CREATE PROCEDURE AjouterItemPanier (pAlias VARCHAR(30), pQte int, pIdItem int)
BEGIN
	IF(TRIM(pAlias) != '' AND pQte > 0 AND pIdItem > 0)
	THEN
		SELECT prixUnitaire INTO @prixUnitaire FROM Items WHERE IdItems = pIdItem;
        SELECT quantite INTO @qteItem FROM Items WHERE IdItems = pIdItem;
        SELECT solde INTO @soldeJoueur FROM Joueurs WHERE alias = pAlias;
        SELECT idJoueur INTO @idJoueur FROM Joueurs WHERE alias = pAlias;
        SELECT COUNT(Items_IdItems) INTO @nbItem FROM Panier WHERE Items_IdItems = pIdItem AND Joueurs_IdJoueur = @idJoueur;
		SET @montantPanier = (SELECT MontantTotalPanier(@idJoueur));
		
		IF((@qteItem - pQte)< 0) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Impossible d''ajouter cet item car il manque de cet item dans l''inventaire.';
		END IF;
     
		IF(@soldeJoueur < @montantPanier + (@prixUnitaire * pQte)) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Impossible d''ajouter cet item car le solde du joueur est insuffisant pour couvrir le montant total du panier.';
		END IF;
         
		IF(@nbItem = 0) THEN
            INSERT into Panier (Joueurs_IdJoueur, Items_IdItems, qteAchat) values(@idJoueur, pIdItem, pQte);
		ELSE			
			UPDATE Panier SET qteAchat = qteAchat + pQte WHERE Items_IdItems = pIdItem AND Joueurs_IdJoueur = @idJoueur;
		END IF;
		
		UPDATE Items SET quantite = (quantite - pQte) WHERE IdItems = pIdItem;
		COMMIT;
    END IF;
END $$
/* TESTS */
/*
#call AjouterJoueur (alias varchar(30), mdp varchar(50), nom varchar(60), prenom varchar(40), courriel varchar(100), solde DECIMAL, dexterite FLOAT, poidsMax FLOAT
call AjouterJoueur ("madzcandy", "candy", "PINK", "Candy",  "amelie.dance23@gmail.com", 100, 50, 30);

#call AjouterMunitions (nom VARCHAR(45),quantite int(11), type char(1), prixUnitaire decimal(10,0), poids float, description varchar(120), estEnVente bit(1), calibre float)
call AjouterMunitions ("balleCoco" , 30, "M", 2, 2.2, "j'aime", 1, 5.3); #id = 36

# call AjouterArmes (nom VARCHAR(45),quantite int(11), type char(1), prixUnitaire decimal(10,0), poids float, description varchar(120), estEnVente bit(1), efficacite int(11), genre varchar(45), Munitions_Items_IdItems int(11))
call AjouterArmes ("coconut", 3, "W", 15, 12.2, "belle coconut", 1, 7, "roar", 36);
#enlever le type en parma ajouterArme

	###SELECT concat('8888888888888', qte);
	###SELECT concat('8888888888888', idItem);
        
SELECT prixUnitaire  FROM Items WHERE IdItems = 37;
call AjouterItemPanier("madzcandy", 2, 37);
call AjouterItemPanier("madzcandy", 1, 36);

SELECT count(*) FROM Items WHERE IdItems = 37;

select @prixUnitaire
#call MontantTotalPanier(IdJoueur int(11))
SELECT MontantTotalPanier(13);

UPDATE Items SET quantite = 100 WHERE IdItems = 37;
*/
# ====================================================================================================================================




# Procédure qui modifie la quantite d'items dans le panier ============================================================================
DELIMITER $$
CREATE PROCEDURE modifierItemPanier (pAlias VARCHAR(30), pQte int, pNumItem int)
BEGIN 
	IF(TRIM(pAlias) != '' AND pQte >= 0 AND pNumItem > 0) THEN
		start TRANSACTION;
			SELECT quantite INTO @qteInventaire FROM Items WHERE IdItems = pNumItem;
			SELECT idJoueur INTO @idJoueur FROM Joueurs WHERE alias = pAlias;
			SELECT COUNT(items_idItems) INTO @nbItem FROM Panier WHERE items_idItems = pNumItem AND Joueurs_IdJoueur = @idJoueur;
			SELECT (qteAchat - pQte) INTO @qteModifiee FROM Panier WHERE items_idItems = pNumItem AND Joueurs_IdJoueur = @idJoueur;
            
			IF(@qteInventaire + @qteModifiee  < 0) THEN
					SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Impossible de modifier la quantit�e de cet item car il manque de cet item dans l''inventaire.';
			END IF;
			IF(@nbItem < 1) THEN
					SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Impossible de modifier cet item car il n''existe pas dans le panier.';
			END IF;
            
			UPDATE Panier SET qteAchat = pQte WHERE items_idItems = pNumItem AND Joueurs_IdJoueur = @idJoueur;
			UPDATE Items SET quantite = quantite + @qteModifiee  WHERE idItems = pNumItem;
		COMMIT;
	ELSE
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'L''item numero n''a pas pu être modifié car les parametres sont invalides.';
	END IF;
END$$
/* Test */
/*
Call modifierItemPanier("madzcandy", 1, 37);
call AjouterItemPanier("madzcandy", 2, 37);

Call modifierItemPanier("madzcandy", 120, 37);
Call modifierItemPanier("madzcandy", 2, 537);
call AjouterItemPanier("", -5, 37); #tester trim 
*/
# ====================================================================================================================================




# Procédure qui supprime un item dans le panier =======================================================================================
DELIMITER $$
CREATE PROCEDURE supprimerItemPanier (pAlias VARCHAR(30), pNumItem int)
BEGIN
	IF(TRIM(pAlias) != '' AND pNumItem > 0) THEN
		START TRANSACTION;
			set @idJoueur = 0;
			SELECT idJoueur INTO @idJoueur FROM Joueurs WHERE alias = pAlias;
			SELECT qteAchat INTO @qteAchat FROM Panier WHERE Items_IdItems = pNumItem AND Joueurs_IdJoueur = @idJoueur;
			SELECT COUNT(Items_IdItems) INTO @nbItem FROM Panier WHERE Items_IdItems = pNumItem AND Joueurs_IdJoueur = @idJoueur;
			
            IF(@idJoueur = 0)THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Impossible de supprimer cet item car ce joueur n''existe pas.';
            END IF;
            
			IF(@nbItem = 0) THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Impossible de supprimer cet item car il n''existe pas dans le panier.';
			END IF;

			SELECT @idJoueur;

			DELETE FROM Panier WHERE Items_IdItems = pNumItem AND Joueurs_IdJoueur = @idJoueur;
			UPDATE Items SET quantite = quantite + @qteAchat  WHERE IdItems = pNumItem;
		COMMIT;
	ELSE
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'L''item n''a pas pu être supprimé car les parametres sont invalides.';
	END IF;
END$$
/* Test */
/*
call AjouterItemPanier("madzcandy", 1, 36);
#mauvais id:
call supprimerItemPanier("madzcandy", 30);
#mauvais alias:
call supprimerItemPanier("zz", 36);
*/
# ====================================================================================================================================




# Procédure qui supprime le panier d'un joueur =======================================================================================
DELIMITER $$
CREATE PROCEDURE SupprimerPanier (pAlias VARCHAR(30))
BEGIN	
	DECLARE finished INTEGER DEFAULT 0;
	DECLARE pNumItem INTEGER DEFAULT 0;
    DECLARE pQteAchat INTEGER DEFAULT 0;
	DEClARE cur_panier CURSOR FOR SELECT Items_IdItems FROM Panier WHERE Joueurs_IdJoueur = (SELECT idJoueur FROM Joueurs WHERE alias = pAlias);
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET finished = 1;

	IF(TRIM(pAlias) != '') THEN
		SELECT idJoueur INTO @idJoueur FROM Joueurs WHERE alias = pAlias;
		OPEN cur_panier;

		getPanier: LOOP #boucle qui traite chacun des items du panier
			FETCH cur_panier INTO pNumItem;
			IF finished = 1 THEN 
				LEAVE getPanier;
			END IF;			
			SELECT qteAchat INTO pQteAchat FROM Panier WHERE Joueurs_IdJoueur = @idJoueur AND Items_IdItems = pNumItem;
			UPDATE Items SET quantite = quantite + pQteAchat  WHERE IdItems = pNumItem;
		END LOOP getPanier;
		
		DELETE FROM Panier where @idJoueur = Joueurs_IdJoueur;
		COMMIT;
		CLOSE cur_panier;
	END IF;
END;
END$$
/*TESTS*/
/*
call AjouterItemPanier("madzcandy", 10, 36);
call AjouterItemPanier("madzcandy", 10, 37);
#SET SQL_SAFE_UPDATES = 1;
UPDATE Joueurs SET Solde=1000 WHERE alias='madzcandy';
call SupprimerPanier ('madzcandy');
*/
# ====================================================================================================================================




# Procédure qui retourne le solde d'un joueur =========================================================================================
DELIMITER $$
CREATE FUNCTION SoldeCaps (paramAlias VARCHAR(30))
RETURNS DECIMAL
BEGIN
	SELECT solde INTO @solde 
    FROM Joueurs 
    WHERE alias = paramAlias;
    RETURN @solde;
END $$	
/* TESTS
SELECT (SoldeCaps('madzcandy'));
*/
# ====================================================================================================================================




# Procédure qui retourne le stock restant après un achat ==============================================================================
DELIMITER $$
CREATE FUNCTION StockApresAchat (pIdItems INT)
RETURNS INT
BEGIN
	SELECT quantite INTO @quantite
    FROM Items  
    WHERE pIdItems = IdItems;
    RETURN @quantite;
END $$	
/* TESTS*/
/*
SELECT (StockApresAchat(36));
SELECT (StockApresAchat(37));
*/
# ====================================================================================================================================




# Procédure qui retourne le poids du sac d'un joueur ==================================================================================
DELIMITER $$
CREATE FUNCTION PoidsSac (pAlias VARCHAR(30))
RETURNS FLOAT
BEGIN
	SET @totalPoids = 0;
	SELECT SUM(Items.poids * Inventaire.quantite) INTO @totalPoids
    FROM Joueurs
    INNER JOIN Inventaire ON Joueurs.IdJoueur = Inventaire.Joueurs_IdJoueur
	INNER JOIN Items ON Inventaire.Items_IdItems = Items.IdItems
	WHERE alias = pAlias
    GROUP BY poids;
    RETURN @totalPoids;
END $$	
/* TESTS*/
/*
call AjouterItemPanier("madzcandy", 10, 36);
call AjouterItemInventaire(1, 36, 13);
SELECT (PoidsSac('madzcandy'));
SELECT (PoidsSac('Samy'));
*/
# ====================================================================================================================================




# Procédure qui retourne la dextérité perdue d'un joueur ==============================================================================
DELIMITER $$
CREATE FUNCTION DexteritePerdue (pAlias VARCHAR(30))
RETURNS FLOAT
BEGIN
	SET @dexterite = 0;
	SELECT PoidsSac(pAlias) INTO @poidsSac;
    
    SELECT poidsMaxTransport INTO @poidsMax FROM Joueurs WHERE alias = pAlias;
    SELECT dexterite INTO @dexterite FROM Joueurs WHERE alias = pAlias;
    
    if(@dexterite = 0) then
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Erreur. Le joueur n''existe pas.';
    end if;
    
    SET @dexteritePerdue = @dexterite * (1 - (@poidsSac - @poidsMax) / @poidsMax);
    RETURN @dexteritePerdue;
END$$
/* TESTS*/
/*
SELECT (DexteritePerdue('madzcandy'));
SELECT (DexteritePerdue('y'));
*/
# ====================================================================================================================================




# Procédure qui retourne le nombre d'évaluations reçues pour un item du shop ==========================================================
DELIMITER $$
CREATE FUNCTION NombreEvaluations (pIdItems INT)
RETURNS FLOAT
BEGIN
	SELECT COUNT(idEvaluations) INTO @nbEvaluations
    FROM Evaluations
	WHERE Items_IdItems = pIdItems;
    RETURN @nbEvaluations;
END$$
/*TESTS*/
/*
call AjouterÉvaluation(36, 13, "salut toi", 4);
call AjouterÉvaluation(36, 13, "tres bon", 5);
SELECT NombreEvaluations(36);
*/
# ====================================================================================================================================




# Procédure qui retourne la moyenne des évaluations reçues pour un item du shop ======================================================
DELIMITER $$
CREATE FUNCTION MoyenneEvaluations (pIdItems INT)
RETURNS FLOAT
BEGIN
	SET @nbEtoiles = 0;
	SELECT coalesce(AVG(nbEtoiles),0) INTO @nbEtoiles
    FROM Evaluations
	WHERE Items_IdItems = pIdItems;
    RETURN @nbEtoiles;
END$$
/*TESTS*/
/*
SELECT MoyenneEvaluations(36);
SELECT MoyenneEvaluations(9);
*/
# ====================================================================================================================================











# Insertion BD  ======================================================
#(nom VARCHAR(45),quantite int(11), type char(1), prixUnitaire decimal(10,0), poids float, description varchar(120), estEnVente bit(1), effet varchar(45), dureeEffet float)
call AjouterMedicament("Potion d'invisibilité", 40, "D", 5, 20, "Cette potion vous permet d'être invisible pendant 60 secondes.", 1, "invisible", 60.0);
call AjouterMedicament("Potion Poison", 40, "D", 5, 20, "Cette potion vous permet d'être empoisonné pendant 60 secondes.", 1, "poison", 60.0);
call AjouterMedicament("Potion Regeneration", 20, "D", 5, 20,"Cette potion vous permet de regagner vos vie graduellement.", 1, "regeneration", 10.0);
call AjouterMedicament("Potion Force", 50, "D", 5, 20, "Cette potion vous permet d'être fort pendant 60 secondes.", 1, "Force", 60.0);
call AjouterMedicament("Potion Lent", 5, "D", 5, 20, "Cette potion vous permet d'être lent pendant 60 secondes.", 1, "lent", 60.0);


#nom VARCHAR(45),quantite int(11), type char(1), prixUnitaire decimal(10,0), poids float, description varchar(120), estEnVente bit(1), pointDeVie int(11)
call AjouterNourriture("pomme", 24, "D", 1, 0.5, "Une pomme rouge.", 1, 3);
call AjouterNourriture("pain", 100, "D", 1, 0.5, "Une pain de blé.", 1, 2);
call AjouterNourriture("melon", 20, "D", 1, 0.5, "Un melon juteux.", 1, 3);
call AjouterNourriture("oeuf", 15, "D", 1, 0.5, "Un oeuf de poule.", 1, 3);
call AjouterNourriture("porc cru", 12, "D", 1, 0.5, "Du porc cru.", 1, 4);

#delete from Nourriture where items_iditems = 57;
#call SupprimerItem(57)




#nom VARCHAR(45),quantite int(11), type char(1), prixUnitaire decimal(10,0), poids float, description varchar(120), estEnVente bit(1), matiere varchar(30), taille int(11)
call AjouterArmure("Casque en fer", 23, "A", 15, 23, "Un casque de fer.", 1, "fer", 23);
call AjouterArmure("Bottes en or", 3, "A", 15, 28, "Des bottes en or.", 1, "or", 23);
call AjouterArmure("Bottes en diamant", 2, "A", 15, 23, "Des bottes en diamant.", 1, "diamant", 23);
call AjouterArmure("Casque en diamant", 23, "A", 15, 16, "Un casque en diamant.", 1, "diamant", 23);
call AjouterArmure("Casque en or", 23, "A", 15, 20, "Un casque en or", 1, "or", 23);





#=================================== FONCTIONS PAS TESTES
/*
DELIMITER $$
CREATE FUNCTION afficherPanier(pAlias VARCHAR(30)) 
RETURNS TABLE
RETURN(
	SELECT Items.nom, typeItem =
			CASE typeItem
			WHEN 'D' THEN 'Medicament' 
			WHEN 'A' THEN 'Armure' 
			WHEN 'W' THEN 'Arme' 
            WHEN 'N' THEN 'Nourriture' 
            WHEN 'M' THEN 'Munition' 
			END, qteAchat, prix
	FROM Items
	INNER JOIN Paniers ON Items.numItem = Paniers.numItem
	INNER JOIN Joueurs ON Paniers.idJoueur = Joueurs.idJoueur
	WHERE alias = @alias
);
END$$
*/







DELIMITER $$
CREATE PROCEDURE payerPanier (pAlias VARCHAR(30))
BEGIN
	DECLARE finished INTEGER DEFAULT 0;
    DECLARE pNumItem INTEGER DEFAULT 0;
	DECLARE cur_panier CURSOR FOR SELECT items_IdItems FROM Panier WHERE Joueurs_IdJoueur = (SELECT idJoueur FROM Joueurs WHERE alias = pAlias); 
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET finished = 1;
	SELECT idJoueur INTO @IdJoueur FROM Joueurs WHERE alias = pAlias;
	SET @totalPanier = (SELECT MontantTotalPanier(@idJoueur));
	
	if(@totalPanier = 0) THEN
		 SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Impossible de payer car le panier est vide.';
	end if;

	IF(TRIM(pAlias) != '') THEN
		UPDATE Joueurs SET solde = solde - @totalPanier WHERE alias = pAlias;
		OPEN cur_panier;
		getPanier: LOOP #boucle qui traite chacun des items du panier
			FETCH cur_panier INTO pNumItem;
			IF finished = 1 THEN 
				LEAVE getPanier;
			END IF;			
			SELECT qteAchat INTO @qteAchat FROM Panier WHERE Joueurs_IdJoueur = @idJoueur AND Items_IdItems = pNumItem;
			call AjouterItemInventaire(@qteAchat, pNumItem, @idJoueur);
		END LOOP getPanier;
		
		DELETE FROM Panier where @idJoueur = Joueurs_IdJoueur;
		COMMIT;
        SELECT DexteritePerdue(pAlias) into @newDexterite;
		UPDATE Joueurs SET dexterite = @newDexterite WHERE alias = pAlias;
        COMMIT;
		CLOSE cur_panier;
	ELSE
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT =  'L''alias du joueur est invalide.';
	END IF;
END;
END$$

/* test */
SET SQL_SAFE_UPDATES = 0;
call AjouterItemPanier("2", 2, 57);
call AjouterItemPanier("2", 2, 56);
call payerPanier("2");