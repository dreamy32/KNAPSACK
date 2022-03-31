use KNAPSACKDB;

DELIMITER $$
CREATE PROCEDURE AjouterItemPanier (alias VARCHAR(30), qte int, idItem int)
BEGIN
	IF(TRIM(alias) != '' AND qte > 0 AND idItem > 0)
	THEN
		SELECT prixUnitaire INTO @prixUnitaire FROM Items WHERE IdItems = idItem;
        SELECT quantite INTO @qteItem FROM Items WHERE IdItems = idItem;
        SELECT solde INTO @soldeJoueur FROM Joueurs WHERE alias = alias;
        SELECT idJoueur INTO @idJoueur FROM Joueurs WHERE alias = alias;
        SELECT COUNT(Items_IdItems) INTO @nbItem FROM Panier WHERE Items_IdItems = idItem AND Joueurs_IdJoueur = @idJoueur;
		SET @montantPanier = (SELECT MontantTotalPanier(@idJoueur));
		
		IF((@qteItem - qte)< 0) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Impossible d''ajouter cet item car il manque de cet item dans l''inventaire.';
		END IF;
     
		IF(@soldeJoueur < @montantPanier + (@prixUnitaire * qte)) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Impossible d''ajouter cet item car le solde du joueur est insuffisant pour couvrir le montant total du panier.';
		END IF;
         
		IF(@nbItem = 0) THEN
            INSERT into Panier (Joueurs_IdJoueur, Items_IdItems, qteAchat) values(@idJoueur, idItem, qte);
		ELSE			
			UPDATE Panier SET qteAchat = qteAchat + qte WHERE Items_IdItems = idItem AND Joueurs_IdJoueur = @idJoueur;
		END IF;
		
		UPDATE Items SET quantite = (quantite - qte) WHERE IdItems = idItem;
		COMMIT;
    END IF;
END $$

/* TESTS
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



/* VERIFIER */
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
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'L''item numero n''a pas pu �tre modifi� car les parametres sont invalides.';
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








#=================================== FONCTIONS PAS TESTES

DELIMITER $$
CREATE FUNCTION StockApresAchat (IdItem INT)
RETURNS INT
BEGIN
	SELECT quantite INTO @quantite
    FROM Items  
    WHERE IdItems = @IdItems;
    RETURN @quantite;
END $$	


DELIMITER $$
CREATE FUNCTION PoidsSac (alias VARCHAR(30))
RETURNS FLOAT
BEGIN
	SELECT SUM(Items.poids * Items.quantite) INTO @totalPoids
    FROM Joueurs
    INNER JOIN Inventaire ON Joueurs.IdJoueur = Inventaire.Joueurs_IdJoueur
	INNER JOIN Items ON Inventaire.Items_IdItems = Items.IdItems
	WHERE alias = @alias
    GROUP BY poids;
    RETURN @totalPoids;
END $$	


/* VERIFIER */
DELIMITER $$
CREATE FUNCTION DexteritePerdue (alias VARCHAR(30))
RETURNS FLOAT
BEGIN
	SELECT dbo.PoidsSac(@alias) INTO @poidsSac;
    
    SELECT @poidsMax = poidsMaxTransport, @dexterite = dexterite 
    FROM Joueurs 
    WHERE alias = @alias;
    
    SET @dexteritePerdue = @dexterite * (1 - (@poidsSac - @poidsMax) / @poidsMax);
    RETURN @dexteritePerdue;
END$$



DELIMITER $$
CREATE FUNCTION NombreEvaluations (IdItems INT)
RETURNS FLOAT
BEGIN
	SELECT COUNT(idEvaluations) INTO @nbEvaluations
    FROM Evaluations
	WHERE Items_IdItems = @IdItems
    GROUP BY idEvaluations;
    RETURN @nbEvaluations;
END$$


DELIMITER $$
CREATE FUNCTION MoyenneEvaluations (IdItems INT)
RETURNS FLOAT
BEGIN
	SELECT AVG(nbEtoiles) INTO @nbEtoiles
    FROM Evaluations
	WHERE Items_IdItems = @IdItems
    GROUP BY nbEtoiles;
    RETURN @nbEtoiles;
END$$






/* VERIFIER */
DELIMITER $$
CREATE PROCEDURE supprimerItemPanier (alias VARCHAR(30), numItem int)
BEGIN
	IF(TRIM(@alias) != '' AND @numItem > 0) THEN
		/*BEGIN TRANSACTION */
			SELECT @idJoueur = idJoueur FROM Joueurs WHERE alias = @alias;
			SELECT @qteAchat = qteAchat FROM Paniers WHERE numItem = @numItem AND idJoueur = @idJoueur;
			SELECT @nbItem = COUNT(numItem) FROM Paniers WHERE numItem = @numItem AND idJoueur = @idJoueur;
			if(@nbItem = 0) THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Impossible de supprimer cet item car il n''existe pas dans le panier.';

			DELETE FROM Paniers WHERE numItem = @numItem AND idJoueur = @idJoueur;
			UPDATE Items SET qteInventaire = qteInventaire + @qteAchat  WHERE numItem = @numItem;
		COMMIT;
	ELSE
		SELECT 'L''item numero ' + CAST(@numItem AS CHAR(10)) + ' n''a pas pu �tre supprim� car les parametres sont invalides.';
END
END$$



/* Verifier return table 
DELIMITER $$
CREATE FUNCTION afficherPanier(alias VARCHAR(30)) 
RETURNS TABLE
RETURN(
	SELECT Items.nom, typeItem =
			CASE typeItem
			WHEN 'M' THEN 'Medicament' 
			WHEN 'A' THEN 'Armures' 
			WHEN 'P' THEN 'Armure' 
			END, qteAchat, prix
	FROM Items
	INNER JOIN Paniers ON Items.numItem = Paniers.numItem
	INNER JOIN Joueurs ON Paniers.idJoueur = Joueurs.idJoueur
	WHERE alias = @alias
);
END$$
*/



DELIMITER $$
CREATE PROCEDURE SupprimerPanier (alias VARCHAR(30))
BEGIN
	IF(TRIM(@alias) != '') THEN
			SELECT @idJoueur = idJoueur FROM Joueurs WHERE alias = @alias;
			DECLARE cur_panier CURSOR FOR SELECT numItem, qteAchat FROM Paniers WHERE @idJoueur = idJoueur 

				OPEN cur_panier;
				FETCH NEXT FROM cur_panier INTO @numItem, @qteAchat;
				WHILE @@FETCH_STATUS = 0 
						UPDATE Items SET qteInventaire = qteInventaire + @qteAchat  WHERE numItem = @numItem;
						FETCH NEXT FROM cur_panier INTO @numItem, @qteAchat;		
				CLOSE cur_panier;
				DEALLOCATE cur_panier;

			DELETE FROM Paniers where @idJoueur = idJoueur;
		COMMIT;
	ELSE
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'L''alias du joueur est invalide.';
END;
BEGIN 
	SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Une erreur est survenue lors de la supression du panier.';
	ROLLBACK;
		CLOSE cur_panier;
		DEALLOCATE cur_panier;
END;
END$$








