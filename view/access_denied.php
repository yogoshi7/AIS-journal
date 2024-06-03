<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?=URL?>assets/css/style.css">
    <script type="text/javascript" src="<?=URL?>assets/js/script.js"></script>
    <title>Доступ запрещен</title>
</head>
<body>
    <div id="header">
        <h2>Доступ к запрашиваемой странице запрещен</h2>
        <p>Если вам нужен доступ, позвоните на <a href="tel:2442">2442</a> или напишите на <a href="mailto:navprog@root.sas.etty.ru">navprog@root.sas.etty.ru</a> и обязательно передайте две строки ниже:</p>
    </div>
    <div id="content">
        <pre id="host-ip">
            host: <?=$clientHostName?>
            
            ip: <?=$clientIp?>
        </pre>
        <a href="/journals/">Вернуться на главную</a>
    </div>
</body>
</html>