<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?=URL?>assets/css/style.css">
    <script type="text/javascript" src="<?=URL?>assets/js/script.js"></script>
    <title>Список журналов</title>
</head>
<body>
    <div class="main">
        <div id="header">
            <h1>Список электронных журналов</h1>
        </div>
        <div id="content">
            <div id="list-block">
                <ul>
                    <?php foreach ($journals as $journal) { ?>
                    <li><a href="<?=$journal['id']?>"><?=$journal['name']?></a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>