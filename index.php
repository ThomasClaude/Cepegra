
<!-- Connection php  -->

<?php
$dsn = 'mysql:host=localhost;dbname=cepegra';
$dbUser = "root";
$dbPassword = "root";
try {
    $connection = new PDO($dsn, $dbUser, $dbPassword);
    $connection->exec('SET NAMES utf8');
} catch (PDOException $e) {
    print "Erreur: " . $e->getMessage();
    exit;
}
  

if (isset($_POST['insert_newperson'])):
  echo '<pre>';
  print_r($_FILES);
  if ($_FILES['avatar']['error']==0):
    $tmpName = $_FILES['avatar']['tmp_name'];
    move_uploaded_file($tmpName, 'img/' . $_FILES['avatar']['name']);
  endif;
  $addStatement = $connection->prepare("INSERT INTO stagiaires SET nom = :nom, avatar = :avatar");
  $addStatement->execute(
    array(
      'nom' => $_POST['nom'],
      'avatar' => $_FILES['avatar']['name']
    )
  );
  
  $lastId = $connection->lastInsertId();
  $addStatement = $connection->prepare("INSERT INTO c_b_o SET id_stagiaire = :id_stagiaire, id_atelier = :id_atelier");
  $addStatement->execute(
    array(
      'id_atelier' => $_POST['id_atelier'],
      'id_stagiaire' => $lastId
    )
  );
  header('location:index.php'); exit;
endif;
if (isset($_GET['id_stagiaires'])):
  $addStatement = $connection->prepare("DELETE FROM stagiaires WHERE id_stagiaires = :id_stagiaires");
  $addStatement->execute(
    array(
      'id_stagiaires' => $_GET['id_stagiaires']
    )
  );
  
  $addStatement = $connection->prepare("DELETE FROM c_b_o WHERE id_stagiaire = :id_stagiaire");
  $addStatement->execute(
    array(
      'id_stagiaire' => $_GET['id_stagiaires'],
    )
  );
  header('location:index.php'); exit;
endif;


/* Get from table   */

    // $preparedStatement = $connection->prepare('SELECT * FROM stagiaires_ateliers ');
    // $preparedStatement->execute(

    //   array(
    //     'form' => $_GET['id_stagiaires'].'%'
    //   )
    // );
    // $addStatement = $connection->prepare('INSERT INTO people (firstname, age) VALUES (:firstname, :age)');
    // $addStatement ->execute(
    //     array(
    //     'firstname' => $_POST['firstname'],
    //     'age' => $_POST['age']
    //   )

    // );

    $preparedStatement = $connection->prepare('SELECT id_ateliers, ateliers.name ateliers_nom FROM ateliers');
    $preparedStatement->execute ();
    $results = $preparedStatement->fetchAll();

    $allStatement = $connection->prepare('SELECT * FROM stagiaires_ateliers');
    $allStatement->execute ();
    $stagiaires = $allStatement->fetchAll();
  ?>


  

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Demo php</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
</head>



<body>
  <div class="flexbox">
    <div class="add-people">
      <h2>Add someone</h2>
      <form method="post"
            enctype="multipart/form-data"
            action="index.php"
      >
        <label class="add-people__label"
               for="nom"
        >
          Name
        </label>
        <input type="text"
                id="nom"
                name="nom"
                class="add-people__input"
        >
        <label class="add-people__label"
               for="avatar"
        >
          Avatar
        </label>
        <input type="file" 
                name="avatar[]" 
                id="avatar"
                multiple
                class="add-people__input"
        >
        <select name="id_atelier"
                id="id_ateliers"
        >
          <?php foreach ($results as $result): ?>
          <option value="<?php echo $result["id_ateliers"] ?>">
            <?php echo $result["ateliers_nom"] ?>
          </option>
          <?php endforeach ?>
        </select>
        <input type="submit"
               value="add"
               name="add"
               class="add-people__submit"
          >
        <input type="hidden" name="insert_newperson">
      </form>
    </div>
    </div>

  </div>

  

  <table>
    <tr>
      <th>
        Id-Stagiaire
      </th>
      <th>
        Stagiaire
      </th>
      <th>
        Id-Atelier
      </th>
      <th>
        Atelier
      </th>
    </tr>

    <?php foreach ($stagiaires as $stagiaire): ?>
      <tr>
        <td>
          <?php echo $stagiaire["id_stagiaires"] ?>
        </td>
        <td>
          <?php echo $stagiaire["stagiaires_nom"] ?>
        </td>
        <td>
          <?php echo $stagiaire["id_ateliers"] ?>
        </td>
        <td>
          <?php echo $stagiaire["ateliers_nom"] ?>
        </td>
        <td>
        <a href="index.php?id_stagiaires=<?php echo $stagiaire['id_stagiaires'] ?>" data-name="<?php echo $stagiaire["stagiaires_nom"] ?>"
      >x</a>
        </td>
      </tr>
      
    <?php endforeach ?>
  </table>
<script>
  const linkElement = document.querySelectorAll('a');
  for (let i = 0; i < linkElement.length ; i++) {
    linkElement[i].addEventListener('click', function(e) {
      let stagiaireName = this.getAttribute('data-name');
      let myElement = this;
      let myEvent = e;
      if (!confirm('Supprimer ' + stagiaireName)) {
        e.preventDefault();
        
        // myElement.dispatchEvent(myEvent);
      }
    });
  }

</script>
</body>

 <?php
/*
 if($_POST['login']=='aDmin' AND md5($_POST['pass'])== '1b7ce0742dca60b3193b2066e89316e1
 ')
*/
?>



</html>