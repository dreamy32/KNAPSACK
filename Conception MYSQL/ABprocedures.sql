use KNAPSACKDB;

DELIMITER $$
CREATE PROCEDURE AjouterItemPanier (alias VARCHAR(30), qte int, numItem int)
BEGIN
	/*DECLARE @qteInventaire int, @soldeJoueur money, @montantPanier money, @niveau int, @typeItem CHAR(1), @idJoueur int, @nbItem int, @prixItem money;
	*/
	IF(TRIM(alias) != '' AND qte > 0 AND numItem > 0)
	THEN
		SELECT @qteInventaire = qteInventaire, @typeItem = typeItem, @prixItem = prix FROM Items WHERE numItem = @numItem;
		SELECT @soldeJoueur = solde, @idJoueur = idJoueur FROM Joueurs WHERE alias = @alias;
		SELECT @montantPanier = dbo.montantPanier(@alias);
		SELECT @nbItem = COUNT(numItem) INTO @nbItem FROM Paniers WHERE numItem = @numItem AND idJoueur = @idJoueur;
		
	
		IF(@qteInventaire < @qte) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Impossible d''ajouter cet item car il manque de cet item dans l''inventaire.';
		END IF;
        
		IF(@soldeJoueur < @montantPanier + (@prixItem * @qte)) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Impossible d''ajouter cet item car le solde du joueur est insuffisant pour couvrir le montant total du panier.';
		END IF;
         
		IF(@nbItem = 0) THEN
			INSERT into Paniers values(@idJoueur, @numItem, @qte);
		ELSE
			UPDATE Paniers SET qteAchat = qteAchat + @qte WHERE numItem = @numItem AND idJoueur = @idJoueur;
		END IF;
		
		UPDATE Items SET qteInventaire = qteInventaire - @qte WHERE numItem = @numItem;
		COMMIT;
			ELSE
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Les valeurs sont mauvaises';
    END IF;
END $$




DELIMITER $$
CREATE FUNCTION SoldeCaps (alias VARCHAR(30))
RETURNS DECIMAL
BEGIN
	SELECT solde INTO @solde 
    FROM Joueurs 
    WHERE alias = @alias;
    RETURN @solde;
END $$	



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