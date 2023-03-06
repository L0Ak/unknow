<?php

/**
 *  AGORA
 * 	©  Logma, 2019
 * @package default
 * @author MD
 * @version    1.0
 * @link       http://www.php.net/manual/fr/book.pdo.php
 * 
 * Classe d'accès aux données. 
 * Utilise les services de la classe PDO
 * pour l'application AGORA
 * Les attributs sont tous statiques,
 * $monPdo de type PDO 
 * $monPdoJeux qui contiendra l'unique instance de la classe
 */
class PdoJeux {

    private static $monPdo;
    private static $monPdoJeux = null;

    /**
     * Constructeur privé, crée l'instance de PDO qui sera sollicitée
     * pour toutes les méthodes de la classe
     */
    private function __construct() {
		// A) >>>>>>>>>>>>>>>   Connexion au serveur et à la base
		try {   
			// encodage
			$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''); 
			// Crée une instance (un objet) PDO qui représente une connexion à la base
			PdoJeux::$monPdo = new PDO(DSN,DB_USER,DB_PWD, $options);
			// configure l'attribut ATTR_ERRMODE pour définir le mode de rapport d'erreurs 
			// PDO::ERRMODE_EXCEPTION: émet une exception 
			PdoJeux::$monPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			// configure l'attribut ATTR_DEFAULT_FETCH_MODE pour définir le mode de récupération par défaut 
			// PDO::FETCH_OBJ: retourne un objet anonyme avec les noms de propriétés 
			//     qui correspondent aux noms des colonnes retournés dans le jeu de résultats
			PdoJeux::$monPdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
		}
		catch (PDOException $e)	{	// $e est un objet de la classe PDOException, il expose la description du problème
			die('<section id="main-content"><section class="wrapper"><div class = "erreur">Erreur de connexion à la base de données !<p>'
				.$e->getmessage().'</p></div></section></section>');
		}
    }

    //==============================================================================
    //
    // METHODES POUR LA GESTION DES MEMBRES
    //
    //==============================================================================
    /**

     * @param string $compte le compte de l'utilisateur
     * @param string $mdp le mot de passe de l'utilisateur
     * @return ?object l'objet ou null si ce membre n'existe pas
     */

    public function getUnMembre(string $loginMembre, string $mdpMembre): ?object {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare(
                'SELECT idMembre, prenomMembre, nomMembre, mdpMembre, selMembre
                FROM membre
                WHERE loginMembre = ceLoginMembre');
            $requete_prepare->bindParam(':ceLoginMemebre', $loginMembre, PDO::PARAM_STR);

            $requete_prepare->execute();

            if ($utilisateur=$requete_prepare->fetch()) {
                $mdpHash=hash('SHA512', $mdpMembre.$utilisateur->selMembre);
                if ($mdpHash == $utilisateur->mdpMembre) {
                    return $utilisateur;
                } else {
                    return NULL;
                }
            }                        
        }
        catch(PDOException $e) {
                die('div class = "erreur">Erreur dans la requête !<p>'.$e->getmessage().'</p></div>');
            }
    }

    // Destructeur, supprime l'instance de PDO  
    public function _destruct() {
        PdoJeux::$monPdo = null;
    }

    /**
     * Fonction statique qui crée l'unique instance de la classe
     * Appel : $instancePdoJeux = PdoJeux::getPdoJeux();
     * 
     * @return l'unique objet de la classe PdoJeux
     */
    public static function getPdoJeux() {
        if (PdoJeux::$monPdoJeux == null) {
            PdoJeux::$monPdoJeux = new PdoJeux();
        }
        return PdoJeux::$monPdoJeux;
    }

	//==============================================================================
	//
	//	METHODES POUR LA GESTION DES GENRES
	//
	//==============================================================================
	
    /**
     * Retourne tous les genres sous forme d'un tableau d'objets 
     * 
     * @return array le tableau d'objets  (Genre)
     */
    public function getLesGenres(): array {
  		$requete =  'SELECT idGenre as identifiant, libGenre as libelle 
						FROM genre 
						ORDER BY libGenre';
		try	{	 
			$resultat = PdoJeux::$monPdo->query($requete);
			$tbGenres  = $resultat->fetchAll();	
			return $tbGenres;		
		}
		catch (PDOException $e)	{  
			die('<div class = "erreur">Erreur dans la requête !<p>'
				.$e->getmessage().'</p></div>');
		}
    }

	
	/**
	 * Ajoute un nouveau genre avec le libellé donné en paramètre
	 * 
	 * @param string $libGenre : le libelle du genre à ajouter
	 * @return int l'identifiant du genre crée
	 */
    public function ajouterGenre(string $libGenre): int {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("INSERT INTO genre "
                    . "(idGenre, libGenre) "
                    . "VALUES (0, :unLibGenre) ");
            $requete_prepare->bindParam(':unLibGenre', $libGenre, PDO::PARAM_STR);
            $requete_prepare->execute();
			// récupérer l'identifiant crée
			return PdoJeux::$monPdo->lastInsertId(); 
        } catch (Exception $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
				.$e->getmessage().'</p></div>');
        }
    }
	
	
	 /**
     * Modifie le libellé du genre donné en paramètre
     * 
     * @param int $idGenre : l'identifiant du genre à modifier  
     * @param string $libGenre : le libellé modifié
     */
    public function modifierGenre(int $idGenre, string $libGenre): void {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("UPDATE genre "
                    . "SET libGenre = :unLibGenre "
                    . "WHERE genre.idGenre = :unIdGenre");
            $requete_prepare->bindParam(':unIdGenre', $idGenre, PDO::PARAM_INT);
            $requete_prepare->bindParam(':unLibGenre', $libGenre, PDO::PARAM_STR);
            $requete_prepare->execute();
        } catch (Exception $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
				.$e->getmessage().'</p></div>');
        }
    }
	
	
	/**
     * Supprime le genre donné en paramètre
     * 
     * @param int $idGenre :l'identifiant du genre à supprimer 
     */
    public function supprimerGenre(int $idGenre): void {
       try {
            $requete_prepare = PdoJeux::$monPdo->prepare("DELETE FROM genre "
                    . "WHERE genre.idGenre = :unIdGenre");
            $requete_prepare->bindParam(':unIdGenre', $idGenre, PDO::PARAM_INT);
            $requete_prepare->execute();
        } catch (Exception $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
				.$e->getmessage().'</p></div>');
        }
    }
	
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//----------------------------MARQUES------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    public function getLesMarques(): array {
        $requete =  'SELECT idMarque as identifiant, nomMarque as nom 
                      FROM marque 
                      ORDER BY nomMarque';
      try	{	 
          $resultat = PdoJeux::$monPdo->query($requete);
          $tbMarque  = $resultat->fetchAll();	
          return $tbMarque;		
      }
      catch (PDOException $e)	{  
          die('<div class = "erreur">Erreur dans la requête !<p>'
              .$e->getmessage().'</p></div>');
      }
  }

  

  public function ajouterMarques(string $nomMarque): int {
      try {
          $requete_prepare = PdoJeux::$monPdo->prepare("INSERT INTO marque "
                  . "(idMarque, nomMarque) "
                  . "VALUES (0, :unLibMarque) ");
          $requete_prepare->bindParam(':unLibMarque', $nomMarque, PDO::PARAM_STR);
          $requete_prepare->execute();
          // récupérer l'identifiant crée
          return PdoJeux::$monPdo->lastInsertId(); 
      } catch (Exception $e) {
          die('<div class = "erreur">Erreur dans la requête !<p>'
              .$e->getmessage().'</p></div>');
      }
  }
  
  
   /**
   * Modifie le libellé du genre donné en paramètre
   * 
   * @param int $idMarque : l'identifiant du genre à modifier  
   * @param string $nomMarque : le libellé modifié
   */
  public function modifierMarque(int $idMarque, string $nomMarque): void {
      try {
          $requete_prepare = PdoJeux::$monPdo->prepare("UPDATE marque "
                  . "SET nomMarque = :unLibMarque "
                  . "WHERE marque.idMarque = :unIdMarque");
          $requete_prepare->bindParam(':unIdMarque', $idMarque, PDO::PARAM_INT);
          $requete_prepare->bindParam(':unLibMarque', $nomMarque, PDO::PARAM_STR);
          $requete_prepare->execute();
      } catch (Exception $e) {
          die('<div class = "erreur">Erreur dans la requête !<p>'
              .$e->getmessage().'</p></div>');
      }
  }
  
  
  /**
   * Supprime le genre donné en paramètre
   * 
   * @param int $idMarque :l'identifiant du genre à supprimer 
   */
  public function supprimerMarque(int $idMarque): void {
     try {
          $requete_prepare = PdoJeux::$monPdo->prepare("DELETE FROM marque "
                  . "WHERE marque.idMarque = :unIdMarque");
          $requete_prepare->bindParam(':unIdMarque', $idMarque, PDO::PARAM_INT);
          $requete_prepare->execute();
      } catch (Exception $e) {
          die('<div class = "erreur">Erreur dans la requête !<p>'
              .$e->getmessage().'</p></div>');
      }
  }
  

//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//----------------------------Plateformes------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------------
public function getLesPlateformes(): array {
    $requete =  'SELECT idPlateforme as identifiant, libPlateforme as libelle 
                  FROM plateforme 
                  ORDER BY libPlateforme';
  try	{	 
      $resultat = PdoJeux::$monPdo->query($requete);
      $tbPlateforme  = $resultat->fetchAll();	
      return $tbPlateforme;		
  }
  catch (PDOException $e)	{  
      die('<div class = "erreur">Erreur dans la requête !<p>'
          .$e->getmessage().'</p></div>');
  }
}



public function ajouterPlateforme(string $libPlateforme): int {
  try {
      $requete_prepare = PdoJeux::$monPdo->prepare("INSERT INTO plateforme "
              . "(idPlateforme, libPlateforme) "
              . "VALUES (0, :unLibPlateforme) ");
      $requete_prepare->bindParam(':unLibPlateforme', $libPlateforme, PDO::PARAM_STR);
      $requete_prepare->execute();
      // récupérer l'identifiant crée
      return PdoJeux::$monPdo->lastInsertId(); 
  } catch (Exception $e) {
      die('<div class = "erreur">Erreur dans la requête !<p>'
          .$e->getmessage().'</p></div>');
  }
}


/**
* Modifie le libellé du genre donné en paramètre
* 
* @param int $idPlateforme: l'identifiant du genre à modifier  
* @param string $libPlateforme : le libellé modifié
*/
public function modifierPlateforme(int $idPlateforme, string $libPlateforme): void {
  try {
      $requete_prepare = PdoJeux::$monPdo->prepare("UPDATE plateforme "
              . "SET libPlateforme = :unLibPlateforme "
              . "WHERE plateforme.idPlateforme = :unIdPlateforme");
      $requete_prepare->bindParam(':unIdPlateforme', $idPlateforme, PDO::PARAM_INT);
      $requete_prepare->bindParam(':unLibPlateforme', $libPlateforme, PDO::PARAM_STR);
      $requete_prepare->execute();
  } catch (Exception $e) {
      die('<div class = "erreur">Erreur dans la requête !<p>'
          .$e->getmessage().'</p></div>');
  }
}


/**
* Supprime le genre donné en paramètre
* 
* @param int $idPlateforme :l'identifiant du genre à supprimer 
*/
public function supprimerPlateforme(int $idPlateforme): void {
 try {
      $requete_prepare = PdoJeux::$monPdo->prepare("DELETE FROM Plateforme "
              . "WHERE plateforme.idPlateforme = :unIdPlateforme");
      $requete_prepare->bindParam(':unIdPlateforme', $idPlateforme, PDO::PARAM_INT);
      $requete_prepare->execute();
  } catch (Exception $e) {
      die('<div class = "erreur">Erreur dans la requête !<p>'
          .$e->getmessage().'</p></div>');
  }
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//----------------------------Jeux------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------------

public function getLesJeux(): array {
    $requete =  'SELECT idPlateforme, nom, dateParution, prix, idPegi, idGenre, idMarque, refJeu
                  FROM jeu_video 
                  ORDER BY refJeu';
  try	{	 
      $resultat = PdoJeux::$monPdo->query($requete);
      $tbJeux  = $resultat->fetchAll();	
      return $tbJeux;		
  }
  catch (PDOException $e)	{  
      die('<div class = "erreur">Erreur dans la requête !<p>'
          .$e->getmessage().'</p></div>');
  }
}



public function ajouterJeu(string $refJeu): int {
  try {
      $requete_prepare = PdoJeux::$monPdo->prepare("INSERT INTO jeu_video "
              . "(refJeu, idPlateforme, idPegi, idGenre, idMarque, nom, prix, dateParution) "
              . "VALUES (0, :unRefJeu) "
              . "VALUES (0, :unIdPlateforme) "
              . "VALUES (0, :unIdPegi) "
              . "VALUES (0, :unIdGenre) "
              . "VALUES (0, :unIdMarque) "
              . "VALUES (0, :unNom) "
              . "VALUES (0, :unPrix) "
              . "VALUES (0, :unDateParution) ");
      $requete_prepare->bindParam(':unRefJeu', $refJeu, PDO::PARAM_STR);
      $requete_prepare->bindParam(':unIdPlateforme', $IdPlateforme, PDO::PARAM_STR);
      $requete_prepare->bindParam(':unIdPegi', $idPegi, PDO::PARAM_STR);
      $requete_prepare->bindParam(':unIdGenre', $idGenre, PDO::PARAM_STR);
      $requete_prepare->bindParam(':unIdMarque', $idMarque, PDO::PARAM_STR);
      $requete_prepare->bindParam(':unNom', $nom, PDO::PARAM_STR);
      $requete_prepare->bindParam(':unPrix', $prix, PDO::PARAM_STR);
      $requete_prepare->bindParam(':unDateParution', $dateParution, PDO::PARAM_STR);
      $requete_prepare->execute();
      // récupérer l'identifiant crée
      return PdoJeux::$monPdo->lastInsertId(); 
  } catch (Exception $e) {
      die('<div class = "erreur">Erreur dans la requête !<p>'
          .$e->getmessage().'</p></div>');
  }
}


/**
* Modifie le libellé du genre donné en paramètre
* 
* @param int $idPlateforme: l'identifiant du genre à modifier  
* @param string $refJeu
* @param int $idPlateforme
* @param int $idPegi
* @param int $idGenre
* @param int $idMarque
* @param string $nom
* @param float $prix
* @param string $dateParution
*/
public function modifierJeu(int $idJeu, string $libJeu): void {
  try {
      $requete_prepare = PdoJeux::$monPdo->prepare("UPDATE jeux");
            $requete_prepare->bindParam(':unRefJeu', $refJeu, PDO::PARAM_STR);
            $requete_prepare->bindParam(':unIdPlateforme', $IdPlateforme, PDO::PARAM_STR);
            $requete_prepare->bindParam(':unIdPegi', $idPegi, PDO::PARAM_STR);
            $requete_prepare->bindParam(':unIdGenre', $idGenre, PDO::PARAM_STR);
            $requete_prepare->bindParam(':unIdMarque', $idMarque, PDO::PARAM_STR);
            $requete_prepare->bindParam(':unNom', $nom, PDO::PARAM_STR);
            $requete_prepare->bindParam(':unPrix', $prix, PDO::PARAM_STR);
            $requete_prepare->bindParam(':unDateParution', $dateParution, PDO::PARAM_STR);
            $requete_prepare->execute();
  } catch (Exception $e) {
      die('<div class = "erreur">Erreur dans la requête !<p>'
          .$e->getmessage().'</p></div>');
  }
}


/**
* Supprime le genre donné en paramètre
* 
* @param int $refJeu :l'identifiant du genre à supprimer 
*/
public function supprimerJeu(int $idJeu): void {
 try {
      $requete_prepare = PdoJeux::$monPdo->prepare("DELETE FROM jeux ");
            $requete_prepare->bindParam(':unRefJeu', $refJeu, PDO::PARAM_STR);
            $requete_prepare->bindParam(':unIdPlateforme', $IdPlateforme, PDO::PARAM_STR);
            $requete_prepare->bindParam(':unIdPegi', $idPegi, PDO::PARAM_STR);
            $requete_prepare->bindParam(':unIdGenre', $idGenre, PDO::PARAM_STR);
            $requete_prepare->bindParam(':unIdMarque', $idMarque, PDO::PARAM_STR);
            $requete_prepare->bindParam(':unNom', $nom, PDO::PARAM_STR);
            $requete_prepare->bindParam(':unPrix', $prix, PDO::PARAM_STR);
            $requete_prepare->bindParam(':unDateParution', $dateParution, PDO::PARAM_STR);
            $requete_prepare->execute();
  } catch (Exception $e) {
      die('<div class = "erreur">Erreur dans la requête !<p>'
          .$e->getmessage().'</p></div>');
  }
}


}





?>