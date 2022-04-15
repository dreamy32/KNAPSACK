/*
+--------------------------------------------+----------+
|            Membres de l'Équipe             |  Projet  |
+--------------------------------------------+----------+
| Amélie Bouchard | Antony Collin-Desrochers | KNAPSACK |
| David Bérubé    | Samy Tétrault            |          |
+--------------------------------------------+----s------+

+---+------------+---+
| | |  Triggers  | | |
+---+------------+---+
*/



#Trigger Supprimer ===================================================================================================================
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
# ====================================================================================================================================



#Trigger qui s'assure qu'on achete pas une quantite plus grande que le stock =========================================================
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
# ====================================================================================================================================



#Trigger qui s'assure qu'on ne mette pas dans le panier une quantite plus grande que le stock #modifier pour le poids et dex =========
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
# ====================================================================================================================================



#Trigger qui update le stock quand un item est acheter  #modifier pour le poids et dex ===============================================
delimiter |
create trigger UpdateStockTrigger
after insert on Inventaire
for each row
begin
            UPDATE Items
            SET quantite = quantite - new.quantite
            WHERE IdItems = new.Items_IdItems;
end |
# ====================================================================================================================================
