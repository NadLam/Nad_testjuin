<?php
session_start();

require_once 'config.php';
require_once 'connect.php';
require_once 'fonctions.php';

// si tentative de connexion
if (isset($_POST['lelogin'])) {
    $lelogin = traite_chaine($_POST['lelogin']);
    $lepass = traite_chaine($_POST['lepass']);

    // vérification de l'utilisateur dans la db
    $sql = "SELECT  u.id, u.lemail, u.lenom AS nom_perm2, u.lenom,
		d.lenom AS nom_perm, d.lenom, d.laperm 
	FROM utilisateur u
		INNER JOIN droit d ON u.droit_id = d.id
    WHERE u.lelogin='$lelogin' AND u.lepass = '$lepass';";
    $requete = mysqli_query($mysqli, $sql)or die(mysqli_error($mysqli));
    $recup_user = mysqli_fetch_assoc($requete);

    // v�rifier si on a r�cup�r� un utilisateur
    if (mysqli_num_rows($requete)) { // vaut true si 1 résultat (ou plus), false si 0
        // si l'utilisateur est bien connecté
        $_SESSION = $recup_user; // transformation des résultats de la requête en variable de session
        $_SESSION['sid'] = session_id(); // récupération de la clef de session
        $_SESSION['lelogin'] = $lelogin; // récupération du login (du POST après traitement)
        // var_dump($_SESSION);
        // redirection vers la page d'accueil (pour éviter les doubles connexions par F5)
        header('location: ' . CHEMIN_RACINE);
    }
}

// récupérations des images dans la table photo
$sql = "SELECT p.lenom,p.lextension,p.letitre,p.ladesc, u.lelogin, 
    GROUP_CONCAT(r.id) AS rubid, 
    GROUP_CONCAT(r.lintitule SEPARATOR '~~') AS lintitule 
    FROM photo p
    INNER JOIN utilisateur u ON u.id = p.utilisateur_id
    LEFT JOIN photo_has_rubriques h ON h.photo_id = p.id
    LEFT JOIN rubriques r ON h.rubriques_id = r.id
    GROUP BY p.id
    ORDER BY p.id DESC; 
    ";
$recup_sql = mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));        
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="style.css" />
        <title>Accueil</title>
        <script src="monjs.js"></script>
        
    </head>
    <body>
        <div id="content">
            <div><h1>Bienvenue sur Télépro-photos.fr</h1> 
                            <nav>
                    <ul id="menu-accordeon">
                        <li><a href="">Accueil</a></li>
                       
                           <ul id="menu-accordeon">
	<li><a href="#">Catégories</a>
		<ul><?php
               
               
                       $rec= "SELECT * FROM rubriques";
                       $result = mysqli_query($mysqli,$rec);


    while($la = mysqli_fetch_assoc($result)){
   echo "<li><a href='categories.php?idsection=".$la['id']."'>".$la['lintitule']."</a></li>";
   }
                                       
?>
		</ul>
            <li><a href="contact.php">Contact</a></li>
                        
                        <?php if (!isset($_SESSION['sid']) || $_SESSION['sid'] != session_id()) {}else{switch ($_SESSION['nom_perm2']) {
                            // si on est l'admin
                            case 0 :
                                echo "<li><a href='modif.php'>Administration</a></li><li><a href='membre.php'>Espace client</a></li><li><a href='deconnect.php'>Déconnexion</a></li>";
                                break;
                            // si on est modérateur
                            case 1:
                                echo "<ul><li><a href='modere.php'>Modération</a></li><li><a href='membre.php'>Espace client</a></li><li><a href='deconnect.php'>Déconnexion</a></li></ul>";
                                break;
                            // si autre droit (ici simple utilisateur)
                            default :
                        echo "<ul><li><a href='membre.php'>Espace client</a></li><li><a href='deconnect.php'>Déconnexion</a></li></ul>";}} ?>
                    </ul>
                </nav>
                <div id="connect">
                     
                    <?php
// si on est pas (ou plus) connecté
                    if (!isset($_SESSION['sid']) || $_SESSION['sid'] != session_id()) {
                        ?>
                        <form action="" name="connection" method="POST">
                            <input type="text" name="lelogin" required />
                            <input type="password" name="lepass" required />
                            <input type="submit" value="Connexion" />
                        </form>
                        <a href="mdp.php">Mot de passe oublié?</a>
                        <a href="inscription.php">Inscription</a>
                        <?php
                        // sinon on est connecté
                    } else {

                        // texte d'accueil
                        echo "<h3>Bonjour " . $_SESSION['nom_perm'] . '</h3>';
                        echo "<p>Vous êtes connecté en tant que <span title='" . $_SESSION['lenom'] . "'>" . $_SESSION['nom_perm2'] . "</span></p>";
                      

                       
                    }
                    ?>
                </div>
            </div>
            <div id="milieu">
                <?php
// affichez les miniatures de chaques photos dans la db par id Desc, avec le titre au dessus et la description en dessous, et affichage de la grande photo dans une nouvelle fenêtre lors du clic, Bonus : afficher lelogin de l'auteur de l'image
                while ($ligne = mysqli_fetch_assoc($recup_sql)) {
                    echo "<div class='miniatures'>";
                    echo "<h4>" . $ligne['letitre'] . "</h4>";
                    echo "<a href='" . CHEMIN_RACINE . $dossier_gd . $ligne['lenom'] . ".jpg' target='_blank'><img src='" . CHEMIN_RACINE . $dossier_mini . $ligne['lenom'] . ".jpg' alt='' /></a><br/>";
                    $explose_rub = explode('~~', $ligne['lintitule']);
                    $explose_id = explode(',', $ligne['rubid']);
                    foreach ($explose_rub AS $clef => $valeur) {
                        echo "<a href='?section=" . $explose_id[$clef] . "'>";
                        echo $valeur . "</a><br/>";
                    }
                    echo "<p>" . $ligne['letitre'] . "<br /> par <strong>" . $ligne['lelogin'] . "</strong></p>";
                    echo "</div>";
                }
                ?> 
            </div>
            <div id="bas"></div>
        </div>
    </body>
</html>
