#Changer Binary à Varbinary de Joueurs
ALTER TABLE Joueurs MODIFY motDePasse VARBINARY(256);

#AjouterJoueur
DELIMITER $$
CREATE PROCEDURE AjouterJoueur (alias varchar(30), mdp varchar(50), nom varchar(60), prenom varchar(40), courriel varchar(100), solde DECIMAL, dexterite FLOAT, poidsMax FLOAT)
BEGIN
	SET @mdpChiffre = SHA2(mdp, 512);
    INSERT INTO Joueurs(alias, motDePasse, nom, prenom, courriel, Solde, dexterite, poidsMaxTransport)
		VALUES(alias, @mdpChiffre, nom, prenom, courriel, solde, dexterite, poidsMax);
END $$

#Modifier Mot de passe
#CREATE PROCEDURE ModifierMotDePasse (alias varchar(30), )
#BEGIN
#	SET @mdpChiffre = SHA2(mdp, 512);
#END $$

#Modifications des foreign key pour le ON DELETE CASCADE
#SupprimerCompte
DELIMITER $$
CREATE TRIGGER `deleteJoueurOnCascade` AFTER DELETE ON `Joueurs` 
FOR EACH ROW 
	SET @idJoueur = old.IdJoueur;
    #DeleteOnCascade
    DELETE FROM Panier WHERE Joueurs_IdJoueur = @idJoueur;
    DELETE FROM Inventaire WHERE Joueurs_IdJoueur = @idJoueur;
    DELETE FROM HistoriqueEnigme WHERE Joueurs_IdJoueur = @idJoueur;
    DELETE FROM Evaluations WHERE Joueurs_IdJoueur = @idJoueur; 
END $$