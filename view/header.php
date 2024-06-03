<div id="header">
    <h1><?=$table_info['name']?></h1>
    <p><?=$table_info['description']?></p>
    <div class="links">
        <a href="<?=URL?>">Главная</a>
        <a href="<?=URL?><?=$table?>/request/new">Заполнение заявки</a>
        <a href="<?=URL?><?=$table?>/requests">Просмотр журнала</a>
        <a href="<?=URL?><?=$table?>/requests/edit">Редактировать записи</a>
    </div>
</div>