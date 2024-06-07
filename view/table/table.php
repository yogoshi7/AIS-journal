<?php require_once __DIR__.'/../assets/filter.php' ?>
<div id="table">
    <table>
        <thead>
            <tr>
                <th>Дата и время подачи заявки</th>
                <th>Тема заявки</th>
                <th>Содержание заявки</th>
                <th>Место расположения заявки</th>
                <th>Передавший</th>
                <th>Принимающий</th>
                <th>Дата и время приема заявки</th>
                <th>Статус заявки</th>
                <th>Дата и время выполнения заявки</th>
                <th>Исполнитель</th>
            </tr>
        </thead>
        <tbody id="tbody">
        </tbody>
	</table>
</div>
<script>
    PrintRequestsToTable();
    
    if (window.location.pathname.split("/")[4] === "edit")
    {
        const thead = document.getElementsByTagName("thead")[0].children[0];
        const th = document.createElement("th");
            
        th.append("Редактирование");
        thead.append(th);
    }
    
</script>
<?php require_once __DIR__.'/../assets/pages.php' ?>