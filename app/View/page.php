<!DOCTYPE html>
<html lang="cs">
    <head>
        <title>UserManager</title>
    </head>
    <?php foreach ($styles as $style): ?>
        <link rel="stylesheet" href="<?= $style ?>">
    <?php endforeach ?>
    <?php foreach ($scripts as $script): ?>
        <script src="<?= $script ?>"></script>
    <?php endforeach ?>
    <body>
        <?= $content ?>
    </body>
</html>