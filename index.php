<?php
  $db_user='phpmyadmin';
  $db_password='root';
  $db_name='test';
  $db_host='localhost';
  $dsn='mysql:host='.$db_host.';dbname='.$db_name;
  $pdo=new PDO($dsn,$db_user,$db_password);

  if(isset($_POST['submit'])){
    $errors='';
    $text=trim(filter_var($_POST['text'], FILTER_SANITIZE_STRING));
    if(empty($text)){
      $errors='Поле не может быть пустым!';
    }
    else{
      $sql='INSERT INTO tasks(task) VALUES(?)';
      $query=$pdo->prepare($sql);
      $query->execute([$text]);
      header('Location: index.php');
    }
  }

  if(isset($_GET['delete'])){
    $id=$_GET['delete'];
    $sql='DELETE FROM `tasks` WHERE id=?';
    $query=$pdo->prepare($sql);
    $query->execute([$id]);
    header('Location: index.php');
  }

  $sql='SELECT * FROM `tasks` ORDER BY `id` ASC';
  $query=$pdo->prepare($sql);
  $query->execute([]);
?>

<?php require_once 'blocks/head.php' ?>
<?php require_once 'blocks/header.php' ?>
<main>
  <div class="row mt-1 mb-5">
    <div class="col-md-12">
      <form class="form-group pt-5" method="post" action="index.php">
        <div class="row">
          <div class="col-md-12 mb-2">
            <?php if(!empty($errors)) echo '<p class="alert alert-danger">' . $errors . '</p>' ?>
          </div>
        </div>
        <div class="row">
          <div class="col-md-9">
            <input class="form-control-lg form-control shadow-lg rounded-pill" type="text" name="text" value="" placeholder="Текст заметки">
          </div>
          <div class="col-md-3">
            <button class="btn btn-success btn-lg shadow-lg rounded" name="submit" type="submit">Добавить</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <table class="rounded-lg table-striped">
        <thead>
          <tr>
            <th scope="col">Задача</th>
            <th scope="col">Действия</th>
          </tr>
        </thead>
        <tbody>
          <?php
            while($row = $query->fetch(PDO::FETCH_OBJ)) {
          ?>
          <tr>
            <td><?= $row->task ?></td>
            <td><a href="index.php?delete=<?=$row->id?>" class="badge badge-danger">Удалить</a></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</main>
<?php require_once 'blocks/footer.php' ?>
