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
<?php
// Couleur du texte des champs si erreur saisie utilisateur
$color_font_warn="#FF0000";
// Couleur de fond des champs si erreur saisie utilisateur
$color_form_warn="#FFCC66";
// Ne rien modifier ci-dessous si vous n’êtes pas certain de ce que vous faites !
if(isset($_POST['submit'])){
	$erreur="";
	// Nettoyage des entrées
	while(list($var,$val)=each($_POST)){
	if(!is_array($val)){
		$$var=strip_tags($val);
	}else{
		while(list($arvar,$arval)=each($val)){
				$$var[$arvar]=strip_tags($arval);
			}
		}
	}
	// Formatage des entrées
	$f_1=trim(ucwords(eregi_replace("[^a-zA-Z0-9éèàäö\ -]", "", $f_1)));
	$f_2=trim(ucwords(eregi_replace("[^a-zA-Z0-9éèàäö\ -]", "", $f_2)));
	$f_3=strip_tags(trim($f_3));
	// Verification des champs
	if(strlen($f_1)<2){
		$erreur.="<li><span class='txterror'>Le champ &laquo; Nom &raquo; est vide ou incomplet.</span>";
		$errf_1=1;
	}
	if(strlen($f_2)<2){
		$erreur.="<li><span class='txterror'>Le champ &laquo; Prénom &raquo; est vide ou incomplet.</span>";
		$errf_2=1;
	}
	if(strlen($f_3)<2){
		$erreur.="<li><span class='txterror'>Le champ &laquo; Email &raquo; est vide ou incomplet.</span>";
		$errf_3=1;
	}else{
		if(!ereg('^[-!#$%&\'*+\./0-9=?A-Z^_`a-z{|}~]+'.
		'@'.
		'[-!#$%&\'*+\/0-9=?A-Z^_`a-z{|}~]+\.'.
		'[-!#$%&\'*+\./0-9=?A-Z^_`a-z{|}~]+$',
		$f_3)){
			$erreur.="<li><span class='txterror'>La syntaxe de votre adresse e-mail n'est pas correcte.</span>";
			$errf_3=1;
		}
	}
	if($erreur==""){
		// Création du message
		$titre="Message de votre site";
		$tete="From:Site@Http://localhost/nad_testjuin/\n";
		$corps.="Nom : ".$f_1."\n";
		$corps.="Prénom : ".$f_2."\n";
		$corps.="Email : ".$f_3."\n";
		if(mail("kastatan@hotmail.fr", $titre, stripslashes($corps), $tete)){
			$ok_mail="true";
		}else{
			$erreur.="<li><span class='txterror'>Une erreur est survenue lors de l'envoi du message, veuillez refaire une tentative.</span>";
		}
	}
}
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
                        <li><a href="membre.php">Espace Clients</a></li>
                    </ul>
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
                        echo "<h3>Bonjour et bienvenue sur la page de contact</h3>";
                        ?>
                        <form id="menu-accordeon" action="" name="connection" method="POST">
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
       
    <center><div id="formul">
<a id="foxyform_embed_link_159098" href="http://fr.foxyform.com/"></a>
<script type="text/javascript">
(function(d, t){
   var g = d.createElement(t),
       s = d.getElementsByTagName(t)[0];
   g.src = "http://fr.foxyform.com/js.php?id=159098&sec_hash=1e95c60b0b9&width=350px";
   s.parentNode.insertBefore(g, s);
}(document, "script"));
</script>
<div></center>
<!-- Do not change the code! -->
    </body>
</html>
