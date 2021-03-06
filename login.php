<?php
    include_once(__DIR__ . '/config.php');

    include_once(APP_ROOT . "/menu.php");
    include_once(APP_ROOT . "/admin/connection-history/memberConnectionHandling.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="projet.css" media="all" type="text/css" /></head>
<body>
<form method="post" action="">

    <legend>Connexion au site</legend>

    <div class="form-group">
      <label class="col-lg-2 control-label">Login</label>
      <div class="col-lg-10">
        <input type="text" class="form-control" name="login" placeholder="Login">
      </div>
    </div><br/>

    <div class="form-group">
      <label class="col-lg-2 control-label">Mot de passe</label>
      <div class="col-lg-10">
        <input type="password" class="form-control" name="password" placeholder="Mot de passe">
      </div>
    </div>

<br/><br/><button type="submit" name="submit" class="btn btn-primary">Connexion</button>
</form>
</body>
<?php
  if(isset($_POST) && !empty($_POST['login']) && !empty($_POST['password'])) {
  extract($_POST);
  // on recup�re le password de la table qui correspond au login du visiteur
  $data = null;
  try
  {
      $bdd = getDatabase();
      $sql = "Select id, password, isAdmin, isBanned from membre where login = :login";
      $req = $bdd->prepare($sql);
      $req->execute(array(
          'login' => $login
      ));

      $data=$req->fetch();
  }
  catch(Exception $e)
  {
      die('Erreur : '.$e->getMessage());
  }

  if(!password_verify($_POST['password'], $data['password'])) {
    echo '<div class="alert alert-dismissable alert-danger">  <strong>Oh Non !</strong> Mauvais login / password. Merci de recommencer !</div>';
  } else if($data['isBanned']) {
    echo '<div class="alert alert-dismissable alert-danger">  <strong>Vous avez été banni !</strong></div>';
  } else {
    $_SESSION['login'] = $login;
    $_SESSION['membre_id'] = $data['id'];
    $_SESSION['isAdmin'] = $data['isAdmin'];
    addConnectionEntryInDatabase($data['id']);

    try
    {
      $bdd = getDatabase();
      $sql = "Select imageProfil from membre where login = :login";
      $req = $bdd->prepare($sql);
      $req->execute(array(
          'login' => $login
      ));

      $data=$req->fetch();
    }
    catch(Exception $e)
    {
      die('Erreur : '.$e->getMessage());
    }

    $image = $data['imageProfil'];

    // pas d'image de profil
   if (!isset($image)) {
    $_SESSION['imageProfil'] = './uploads/default.jpg';
    } else {
     $_SESSION['imageProfil'] = $image;
    }
    echo '<div class="alert alert-dismissable alert-success">
  <strong>Yes !</strong> Vous etes bien logu&eacute;, Redirection dans 5 secondes ! <meta http-equiv="refresh" content="5; URL=accueil.php">
</div>';
  }    
}else {
     echo '<div class="alert alert-dismissable alert-danger">
  Remplissez tous les champs pour vous connectez !
</div>';
  $champs = '<p><b>(Remplissez tous les champs pour vous connectez !)</b></p>';
}
?>