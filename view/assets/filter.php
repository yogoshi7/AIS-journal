<div id="filter">
    <div class="filter">
        Дата подачи
    <!--    <select name="sendance-date">
            <option value="0">Не выбрано</option>
        </select>-->
        <input type="date" name="sendance-date">
    </div>
    <div class="filter">
        Тема
        <select name="type-id">
            <option value="" selected="true">Не выбрано</option>
        </select>
    </div>
    <div class="filter">
        Расположение
        <select name="location">
            <option value="" selected="true">Не выбрано</option>
        </select>
    </div>
    <div class="filter">
        Статус
        <select name="completion-status">
            <option value="" selected="true">Не выбрано</option>
            <option value="0">Не выполнено</option>
            <option value="1">Выполнено</option>
        </select>
    </div>
    <div class="filter">
        <button id="filter-select">Выбрать</button>
        <button id="filter-clear">Очистить фильтр</button>
    </div>
</div>
<script>
    PrintFilterValues();
    
    const filterSelect = document.getElementById("filter-select");
    const filterClear = document.getElementById("filter-clear");
    
    filterSelect.addEventListener("click", () => {
        const params = GetFilterParams();
        PrintRequestsToTable(params);
        PrintPageButtons(params);
    });
    
    filterClear.addEventListener("click", () => {
        const filterBlocks = Array.from(document.getElementById("filter").children);
        filterBlocks.forEach(filterBlock => {
            filterBlock.children[0].value = "";
        });
        
        PrintRequestsToTable();
        PrintPageButtons();
    });
</script>