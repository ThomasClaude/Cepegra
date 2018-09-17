<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Demo php</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
  <script src="main.js"></script>
</head>

<!-- Connection php  -->

<?php
$dsn = 'mysql:host=localhost;dbname=demo';
$dbUser = "root";
$dbPassword = "root";
try {
    $connection = new PDO($dsn, $dbUser, $dbPassword);
    $connection->exec('SET NAMES utf8');
} catch (PDOException $e) {
    print "Erreur: " . $e->getMessage();
    exit();
}

?>

<body>
  <div class="flexbox">
    <div class="add-people">
      <h2>Add someone</h2>
      <form method="post">
        <label for="firstname"
               class="add-people__label"
        >
          Name
          <input type="text"
                 id="firstname"
                 name="firstname"
                 class="add-people__input"
          >
        </label>
        <label for="age"
               class="add-people__label"
        >
          Age
          <input type="number"
                 id="age"
                 name="age"
                 class="add-people__input"
          >
        </label>
        <input type="submit"
               value="add"
               name="add"
               class="add-people__submit"
          >
      </form>
    </div>
    <div class="get-people">
      <h2>Find someone</h2>
      <form method="get">
        <label for="firstname"
               class="get-people__label"
        >
          Name
          <input type="text"
                 id="firstname"
                 name="firstname"
                 class="get-people__input"
          >
        </label>
        <input type="submit"
               value="get"
               name="get"
               class="get-people__submit"
          >
      </form>
    </div>
    <div class="get-people">
      <h2>Remove someone</h2>
      <form method="post">
        <label for="firstname"
               class="get-people__label"
        >
          Name
          <input type="text"
                 id="firstname"
                 name="firstname"
                 class="get-people__input"
          >
        </label>
        <input type="submit"
               value="remove"
               name="get"
               class="get-people__submit"
          >
      </form>
    </div>

  </div>

  <!-- Get, Post & Delete from table    -->

  <?php
    $preparedStatement = $connection->prepare('SELECT id, firstname, age FROM people WHERE firstname LIKE :form ');
    $preparedStatement->execute(

      array(
        'form' => $_GET['firstname'].'%'
      )
    );
    $addStatement = $connection->prepare('INSERT INTO people (firstname, age) VALUES (:firstname, :age)');
    $addStatement ->execute(
        array(
        'firstname' => $_POST['firstname'],
        'age' => $_POST['age']
      )

    );
    $removeStatement = $connection->prepare('DELETE FROM people (firstname, age) VALUES (:firstname, :age)');
    $removeStatement ->execute(
        array(
        'firstname' => $_POST['firstname'],
        'age' => $_POST['age']
      )

    );
    $results = $preparedStatement->fetchAll();
  ?>

  <table>
    <tr>
      <th>
        ID
      </th>
      <th>
        Name
      </th>
      <th>
        Age
      </th>
    </tr>

    <?php foreach ($results as $result): ?>
      <tr>
        <td>
          <?php echo $result ["id"] ?>
        </td>
        <td>
          <?php echo $result ["firstname"] ?>
        </td>
        <td>
          <?php echo $result ["age"] ?>
        </td>
      </tr>
    <?php endforeach ?>
  </table>

</body>





</html>