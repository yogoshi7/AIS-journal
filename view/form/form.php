<form method="post" action="?" id="request-form">
    <div id="request">
        <div id="left-col">
            <p>Заявка</p>
            <div class="line">
                <span>Дата подачи заявки</span><input name="sendance-date" type="date">
            </div>
            <div class="line">
                <span>Время подачи заявки</span><input name="sendance-time" type="time">
            </div>
            <div class="line">
                <span>Тема заявки</span>
                <select id="type" name="type-id">
                </select>
            </div>
            <div class="line">
                <span>Содержание заявки</span><textarea name="content" autocomplete="off"></textarea>
            </div>
            <div class="line">
                <span>Место расположения заявки</span><input name="location" type="text" list="location" autocomplete="off">
                <datalist id="location">
                </datalist>
            </div>
        </div>
        <div id="right-col">
            <p>Кто передал</p>
            <div class="line">
                <span>Передавший</span><input name="sender" type="text" list="sender" autocomplete="off">
                <datalist id="sender">
                </datalist>
            </div>
            <p>Кому передано и когда (дата, время)</p>
            <div class="line">
                <span>Принимающий</span><input name="receiver" type="text" autocomplete="off" list="receiver">
                <datalist id="receiver">
                </datalist>
            </div>
            <div class="line">
                <span>Дата</span><input name="receive-date" type="date" min="<?=$currentDate?>" max="<?=$currentDate?>">
            </div>
            <div class="line">
                <span>Время</span><input name="receive-time" type="time">
            </div>
            <?php if (strpos($_SERVER['REQUEST_URI'], 'edit')) require_once("completion.php") ?>
        </div>
        <button id="send" type="button">Отправить</button>
        <?php if (strpos($_SERVER['REQUEST_URI'], 'edit')) require_once("delete.php") ?>
    </div>
</form>
<script>
    PrintFillValues();
    
    if (window.location.pathname.split("/").length === 6)
    {
        PrintRequestById();
    }
    
    const sendanceDateInput = document.getElementsByName("sendance-date")[0];
    sendanceDateInput.addEventListener("change", () => {
        const sendanceTimeInput = document.getElementsByName("sendance-time")[0];
        const receiveDate = document.getElementsByName("receive-date")[0];
        const receiveTime = document.getElementsByName("receive-time")[0];
        
        if (sendanceDateInput.value === sendanceDateInput.getAttribute("max"))
        {
            const currentTime = new Date();
            sendanceTimeInput.setAttribute("max", currentTime.toLocaleTimeString([], {hour: "2-digit", minute: "2-digit"}));
        }
        else
        {
            sendanceTimeInput.removeAttribute("max");
        }
        
        receiveDate.setAttribute("min", sendanceDateInput.value);
        receiveTime.setAttribute("min", receiveTime.value);
    });
    
    const receiverInput = document.getElementsByName("receiver")[0];
    receiverInput.addEventListener("keyup", () => {
        const receiveDate = document.getElementsByName("receive-date")[0];
        const receiveTime = document.getElementsByName("receive-time")[0];
        if (receiverInput.value !== "")
        {
            const currentDate = new Date();
            receiveDate.value = currentDate.toISOString().split("T")[0] ;
            receiveTime.value = currentDate.toLocaleTimeString([], {hour: "2-digit", minute: "2-digit"});
        }
        else
        {
            receiveDate.value = "";
            receiveTime.value = "";
        }
    });
    
    const sendButton = document.getElementById("send");
    sendButton.addEventListener("click", (e) => {
        e.preventDefault();
        
        if (CheckIsFormEmpty())
        {
            alert('Заполните хотя бы одно текстовое поле');
        }
        else
        {
            SendRequest();
        }
    });    
    
</script>