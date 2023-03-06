	<?php
    // si le paramètre action n'est pas positionné alors
    //		si aucun bouton "action" n'a été envoyé alors par défaut on affiche les genres
    //		sinon l'action est celle indiquée par le bouton

    if (!isset($_POST['cmdAction'])) {
        $action = 'afficherJeu';
    } else {
        // par défaut
        $action = $_POST['cmdAction'];
    }

    $idJeuModif = -1;        // positionné si demande de modification
    $notification = 'rien';    // pour notifier la mise à jour dans la vue

    // selon l'action demandée on réalise l'action 
    switch ($action) {

        case 'ajouterNouveauJeu': {
                if (!empty($_POST['txtLibJeu'])) {
                    $idJeuNotif = $db->ajouterJeu($_POST['txtLibJeu']);
                    // $idGenreNotif est l'idGenre du genre ajouté
                    $notification = 'Ajouté';    // sert à afficher l'ajout réalisé dans la vue
                }
                break; 
            }
 
        case 'demanderModifierJeu': {
                $idJeuModif = $_POST['txtIdJeu']; // sert à créer un formulaire de modification pour ce genre
                break;
            }

        case 'validerModifierJeu': {
                $db->modifierJeu($_POST['txtIdJeu'], $_POST['txtLibJeu']);
                $idJeuNotif = $_POST['txtIdJeu']; // $idGenreNotif est l'idGenre du genre modifié
                $notification = 'Modifié';  // sert à afficher la modification réalisée dans la vue
                break;
            }

        case 'supprimerJeu': {
                $idJeu = $_POST['txtIdJeu'];
                $db->supprimerJeu($_POST['txtIdJeu']); //  à compléter, voir quelle méthode appeler dans le modèle
                break;
            }
    }

    // l' affichage des genres se fait dans tous les cas	
    $tbJeux  = $db->getLesJeux();
    require 'vue/v_lesJeux.php';

    ?>
