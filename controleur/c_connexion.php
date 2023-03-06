<?php
if (!isset($_POST['cmdAction'])){
$action = 'demanderConnexion';
}
else {
// par défaut
$action = $_POST['cmdAction'];
}
switch ($action) {
    case 'demanderConnexion': {
    require
    break;
}
    case 'validerConnexion': {
// vérifier si l'utilisateur existe avec ce mot de passe
        $utilisateur = $db->getUnMembre($_POST['txtLogin'], $_POST['hdMdp']);
// si l'utilisateur n'existe pas
if ($utilisateur==NULL) {
    echo '<div class ="erreurCnx"><BR><BR>Mauvais mot de passe</div>';


// positionner le message d'erreur $erreur
// inclure la vue correspondant au formulaire d'authentification
    } else {
// créer trois variables de session pour id utilisateur, nom et prénom
        $_SESSION['idMembre'] = $utilisateur->idMembre;
        $_SESSION['nomMembre'] = $utilisateur->nomMembre;
        $_SESSION['prenomMembre'] = $utilisateur->prenomMembre;
// redirection du navigateur vers la page d'accueil
        header('Location: index.php');
        exit;
    }
break;
}
?>