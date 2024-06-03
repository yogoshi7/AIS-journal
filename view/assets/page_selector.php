<div id="page-selector">
    <span>Страница:</span>
    <?php for($pageNum = 1; $pageNum <= $maxPage; $pageNum++) {?>
    <a href="?page=<?=$pageNum?>"><?=$pageNum?></a>
    <?php }?>
</div>