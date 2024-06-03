<!DOCTYPE html>
<html>
    <head>
        <title><?=$pageTitle?></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="<?=URL?>assets/css/style.css">
        <script type="text/javascript" src="<?=URL?>assets/js/script.js"></script>
    </head>
    <body>
        <div class="main">
            <?php require_once ('header.php'); ?>
            <div id="content">
                <div id="message">
                </div>
                <?php require_once ($pageContent); ?>
            </div>
        </div>
    </body>
</html>