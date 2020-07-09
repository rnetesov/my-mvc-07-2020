<h1>Привет мир</h1>

<form action="" method="post">
    <input type="text" name="name"><br>
    <input type="text" name="age"><br>
    <input type="submit" value="send">
</form>

<?php foreach ($headers as $header => $value): ?>
    <p><?= $header ?> : <?= $value ?></p>
<?php endforeach; ?>

