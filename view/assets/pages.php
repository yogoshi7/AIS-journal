<div id="page-selector">
    <span>Страница:</span>
</div>
<script>
    PrintPageButtons();
    
    const pageBlock = document.getElementById("page-selector");
    pageBlock.addEventListener("click", (e) => {
        if (e.target.nodeName === "BUTTON")
        {
            PrintRequestsToTable(GetPage(e.target));
            
            //элементы блока страниц
            let pageBlockChildren = pageBlock.children;
            Array.from(pageBlockChildren).forEach(child => {
                child.classList.remove("selected");
            });
            //присваивает класс selected нажатой кнопке
            e.target.classList.add("selected");
        }
    });
</script>