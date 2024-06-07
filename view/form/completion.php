<p>Статус выполнения заявки</p>
<div class="line">
    <span>Статус</span>
    <input type="hidden" name="completion-status" value="0">
    <input id="completion-checkbox" name="completion-status" type="checkbox" value="1" onclick="UpdateSendanceDatetime()">Выполнена
</div>

<div class="line">
    <span>Исполнитель</span><input type="text" id="executor" name="executor" disabled>
</div>

<div class="line">
    <span>Дата выполнения</span><input name="completion-date" type="date" disabled>
</div>

<div class="line">
    <span>Время выполнения</span><input name="completion-time" type="time" disabled>
</div>

<div class="line">
    <span><?=$currentDate?> <?=$currentTime?></span>
</div>
<script>

    const completionDate = document.getElementsByName("completion-date")[0];
    const completionTime = document.getElementsByName("completion-time")[0];
    const currentDate = new Date();

    completionDate.setAttribute("max", currentDate.toISOString().split("T")[0]);
    completionTime.setAttribute("max", currentDate.toLocaleTimeString([], {hour: "2-digit", minute: "2-digit"}));
    
    
    function UpdateSendanceDatetime()
    {
        const completionCheckbox = document.getElementById("completion-checkbox");
        const executorInput = document.getElementById("executor");
        if (completionCheckbox.checked)
        {
            completionDate.value = currentDate.toISOString().split("T")[0];
            completionTime.value = currentDate.toLocaleTimeString([], {hour: "2-digit", minute: "2-digit"});
            completionDate.removeAttribute("disabled");
            completionTime.removeAttribute("disabled");
            executorInput.removeAttribute("disabled");
        }
        else
        {
            completionDate.value = "";
            completionTime.value = "";
            executorInput.value = "";
            completionDate.setAttribute("disabled", "disabled");
            completionTime.setAttribute("disabled", "disabled");
            executorInput.setAttribute("disabled", "disabled");
        }
    }
</script>
