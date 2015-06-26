<?php 
if (isset($_POST['message'])) {
    
    $nom = strip_tags(trim($_POST['nom']));
    $mail = strip_tags(trim($_POST['lemail']));
    $texte = strip_tags(trim($_POST['message']));
    // votre mail
    $moi = "nadlam2611@gmail.com";
    $entete = 'Expéditeur : '.$nom."\r\n". 'Email : ' .$mail . "\r\n" ."\r\n". 'Message : ' ."\r\n"."\r\n". $texte . "\r\n" ;
    mail($moi, $texte, $entete);
 
  
}
?>

                

<?php if (!isset($_POST['message'])) {
                   
echo "    <titre_contact>Contactez Nous</titre_contact>";
}    
        ?>
                   

<?php if (isset($_POST['message'])) {
    echo "<titre_contact>Votre Message a bien été envoyé</titre_contact>";
    
}
?>


                <h1>Télépro-photos.fr</h1> 
		<h1>contact</h1>
                <form name="monform" method="post">
   <input name="lenom" type="text" placeholder=" Nom" required />
    <input name="leprenom" type="text" placeholder="Prénom" />
    
   
    <input type="radio" value="Mr" name="titre" id="mr"><label for="mr">Mr</label>
    <input type="radio" value="Mme" name="titre" id="mme"><label for="mme">Mme</label>
    <input type="radio" value="Melle" name="titre" id="melle"><label for="melle">Melle</label>
   <p><br /></p>
                    <input name="letitre" type="text" placeholder="Objet de votre message" required />
                    <p><br /></p>

                    <input name="lemail" type="email" placeholder="Votre adresse e-mail" required />
                    <p><br /></p>

                    <textarea maxlength="500" name="lemessage" placeholder="Votre demande" required></textarea>

                    <p><br /></p>
                    <input type="submit" value="Envoyer" />
                </form>
		<?php		
		
		?>
                 <div id="content">
            <div>
                            <nav>
                    <ul>
                        <li><a href="index.php">Accueil</a></li>
                        <li tabindex="0" class="menu">
                            <ul class="onclick-menu-content" >
                                <li><a href="">Animaux</a></li>
                                <li><a href="">Architectures</a></li>
                                <li><a href="">Artistiques</a></li>
                                <li><a href="">Personnes</a></li>
                                <li><a href="">Paysages</a></li>
                                <li><a href="">Sports</a></li>
                                <li><a href="">Technologies</a></li>
                                <li><a href="">Transports</a></li>
                                <li><a href="">Divers</a></li>
                            </ul>
	
              
    </div>   
    </body>
</html>
