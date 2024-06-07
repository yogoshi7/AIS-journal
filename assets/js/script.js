const url = "https://placeholder.na4u.ru/journals/";
const path = window.location.href.substring(url.length);
const tableName = path.includes('api/') ? path.substring('api/').split('/')[0] : path.split('/')[0];

//предупреждает пользователя о том, что нужно заполнить хотя бы одно поле для отправки
async function alertEmptyForm() { 
    const requestForm = document.getElementById('request-form');
    const request_content = document.getElementsByName("content")[0].value;
    const request_location = document.getElementsByName("location")[0].value;
    const sender = document.getElementsByName("sender")[0].value;
    const receiver = document.getElementsByName("receiver")[0].value;

    if (request_content.trim() === "" && request_location.trim() === "" && sender.trim() === "" && receiver.trim() === "") {
        return false;
    }
}

function CheckIsFormEmpty() { 
    const request_content = document.getElementsByName("content")[0].value;
    const request_location = document.getElementsByName("location")[0].value;
    const sender = document.getElementsByName("sender")[0].value;
    const receiver = document.getElementsByName("receiver")[0].value;

    if (request_content.trim() === "" && request_location.trim() === "" && sender.trim() === "" && receiver.trim() === "") {
        return true;
    }
    return false;
}

async function preventSending()
{
    const requestForm = document.getElementById('request-form');
    
    requestForm.addEventListener('keydown', function(e) {
        if (e.key == 13)
        {
            e.preventDefault();
            return false;
        }
    });
}

/***************Table output functions*************/

//выводит заявки в таблицу
async function PrintRequestsToTable(params="")
{
    //получение json от сервера
    responce = await fetch(url+"api/"+tableName+"/requests"+params,
    {
        method: "GET",
        header: {"Content-Type": "application/json"}
    });
    
    //выводит данные если ответ получен
    if (responce.ok === true)
    {
    
        //копия ответа для проверки есть ли данные или нет
        const responceCopy = await responce.clone().json();
        const responceLength = Object.keys(responceCopy).length;
        
        const tableBlock = document.getElementById("table");
        const messageBlock = document.getElementById("message");
        
        //показывает и скрывает блоки в зависимости от полученных данных
        if (responceLength === 0)
        {
            if (messageBlock.innerHTML === "")
            {
                tableBlock.style.display = "none";
                const message = document.createElement("p");
                message.setAttribute("id", "message-p");
                message.append("Записи не найдены");
                messageBlock.append(message);
            }
        }
        else
        {
            tableBlock.style.display = "block";
            messageBlock.innerHTML = "";
        }
        
        const requests = await responce.json();
        const tbody = document.getElementById("tbody");
        //чистит таблицу
        tbody.innerHTML = "";
        
        requests.forEach(request => {
            tbody.append(ToRow(request))
        });
    }
    
    
    //определяет страница редактирования или нет
    if (window.location.pathname.split("/")[window.location.pathname.split("/").length-1] === "edit")
    {
        //добавляет столбец с ссылками на редактирование конкретных заявок
        AppendTable();
    }
    
}

//формирует строку таблицы из отдельной заявки
function ToRow(request)
{
    const tr = document.createElement("tr");
    tr.setAttribute("data-rowid", request.id);
    
    //проверка на null для nullable полей; присваивает пустую строку для "красивого" вывода
    const sendanceDatetime = request.sendance_date+" "+request.sendance_time;
    const content = request.content !== null ? request.content : "";
    const location = request.location !== null ? request.location : "";
    const sender = request.sender !== null ? request.sender : "";
    const receiver = request.receiver !== null ? request.receiver : "";
    const receiveDatetime = (request.receive_date !== null ? request.receive_date : "")+" "+(request.receive_time !== null ? request.receive_time : "");
    const completion = request.completion_status === "1" ? "Выполнено" : "";
    const completionDatetime = (request.completion_date !== null ? request.completion_date : "")+" "+(request.completion_time !== null ? request.completion_time : "");
    const executor = request.executor !== null ? request.executor : "";
    
    //создает элемент ячейки таблицы для каждого поля
    const sendanceDatetimeTd = document.createElement("td");
    const typeTd = document.createElement("td");
    const contentTd = document.createElement("td");
    const locationTd = document.createElement("td");
    const senderTd = document.createElement("td");
    const receiverTd = document.createElement("td");
    const receiveDatetimeTd = document.createElement("td");
    const completionTd = document.createElement("td");
    const completionDatetimeTd = document.createElement("td");
    const executorTd = document.createElement("td");
    
    //присваивает ячейкам соответствующие значения
    sendanceDatetimeTd.append(sendanceDatetime);
    typeTd.append(request.type);
    contentTd.append(content);
    locationTd.append(location);
    senderTd.append(sender);
    receiverTd.append(receiver);
    receiveDatetimeTd.append(receiveDatetime);
    completionTd.append(completion);
    completionDatetimeTd.append(completionDatetime);
    executorTd.append(executor);
    
    //добавляет ячейки в конечную строку
    tr.append(sendanceDatetimeTd);
    tr.append(typeTd);
    tr.append(contentTd);
    tr.append(locationTd);
    tr.append(senderTd);
    tr.append(receiverTd);
    tr.append(receiveDatetimeTd);
    tr.append(completionTd);
    tr.append(completionDatetimeTd);
    tr.append(executorTd);
    
    return tr;
}

function AppendTable()
{
    const tableRows = document.getElementsByTagName("tbody")[0].children;
    
    Array.from(tableRows).forEach(row => {
        const rowId = row.getAttribute("data-rowid");
        const td = document.createElement("td");
        const a = document.createElement("a");
        
        a.append("Редактировать");
        a.setAttribute("href", url+tableName+"/request/edit/"+rowId);
        td.append(a);
        row.append(td);
    });
}

/************Filter functions******************/

//выводит значения в фильтр
async function PrintFilterValues()
{
    const responce = await fetch(url+"api/"+tableName+"/filter",
    {
        method: "GET",
        header: {"Content-Type": "application/json"}
    });
    if (responce.ok === true)
    {
        const filterValues = await responce.json();
        const selectLocations = document.getElementsByName("location")[0];
        const selectType = document.getElementsByName("type-id")[0];
        const selectSendanceDate = document.getElementsByName("sendance-date")[0];
        
        filterValues.locations.forEach(location => {
            selectLocations.append(ToOption(location, location));
        });
        filterValues.types.forEach(type => {
            selectType.append(ToOption(type.id, type.type));
        });
        //filterValues.sendance_dates.forEach(date => {
        //    selectSendanceDate.append(ToOption(date.raw, date.formatted));
        //});
        selectSendanceDate.setAttribute("max", filterValues.sendance_dates.max_date);
        selectSendanceDate.setAttribute("min", filterValues.sendance_dates.min_date);
    }
}


//добавляет данные в опции
function ToOption(attrValue, printValue=null)
{
    const option = document.createElement("option");
    option.setAttribute("value", attrValue);
    if(printValue !== null)
    {option.append(printValue);}
    
    return option;
}

//генерирует url параметры для получения отфильтрованных заявок
function GetFilterParams()
{
    //отдельный input на дату
    const sendanceDateInput = document.getElementsByName("sendance-date")[0];
    //получает все select-ы
    let selects = Array.from(document.getElementsByTagName("select"));
    selects[3] = sendanceDateInput;
    var valuesToSend = {};
    
    selects.forEach(select => {
        valuesToSend[select.name.replace("-", "_")] = select.value;
    });
    
    //помещает значения фильтра в объект URLSearchParams для удобного преобразования в параметры
    parameters = new URLSearchParams(valuesToSend);
    
    //возвращает только часть с параметрами => ?[parameter]=[value]&...
    return "?"+parameters.toString();
}

/***********Page buttons functions***************/

//выводит кнопки для переключения страниц
async function PrintPageButtons(filterParams="")
{
    const responce = await fetch(url+"api/"+tableName+"/pages"+filterParams, {
        method: "GET",
        header: {"Content-Type": "application/json"}
    });
    
    if (responce.ok === true)
    {
        const pages = await responce.json();
        const pageBlock = document.getElementById("page-selector");
        if (window.location.search.includes("page"))
        {const pageNum = window.location.search.split("&")[3].split("=")[1];}
        
        //по умолчанию показывает блок с кнопками
        pageBlock.style.display = "block";

        //получает все кнопки из блока страниц
        var element = pageBlock.getElementsByTagName("button");
        //очищает блок от "старых" кнопок
        for (index = element.length - 1; index >= 0; index--) {
            element[index].parentNode.removeChild(element[index]);
        }
        
        //вывод кнопок страниц
        for (i = 1; i <= pages; i++)
        {
            const pageButton = document.createElement("button");
            
            //if (typeof pageNum !== "undefined" && pageNum === i)
            //{pageButton.setAttribute("class", "selected");}
            
            pageButton.append(i);
            pageButton.setAttribute("value", i);
            //pageButton.setAttribute("onclick", "");
            pageBlock.append(pageButton);
        }
        
        //если записей нет, то скрывает блок
        if (pages === 0)
        {pageBlock.style.display = "none";}
    }
}

//генерирует url параметры для просмотра следующей страницы заявок с учетом выставленных фильтров
function GetPage(button)
{
    const filterParams = GetFilterParams();
    let parameters = new URLSearchParams(filterParams);
    parameters.append("page", button.value);
    
    return "?"+parameters.toString();
}

/*******************Form functions***************************/

//запрашивает и выводит данные для заполнения формы (types - фиксированные типы заявок; остальное - подсказки для пользователя о существующих полях)
async function PrintFillValues()
{
    const responce = await fetch(url+"api/"+tableName+"/request",
    {
        method: "GET",
        header: {"Content-Type": "application/json"}
    });
    
    if (responce.ok === true)
    {
        const fillValues = await responce.json();
        
        //получение полей ввода
        const sendanceDateInput = document.getElementsByName("sendance-date")[0];
        const sendanceTimeInput = document.getElementsByName("sendance-time")[0];
        const receiveDateInput = document.getElementsByName("receive-date")[0];
        const receiveTimeInput = document.getElementsByName("receive-time")[0];
        const typeSelect = document.getElementById("type");
        const locationDatalist = document.getElementById("location");
        const senderDatalist = document.getElementById("sender");
        const receiverDatalist = document.getElementById("receiver");
        
        //присвоение значений полям даты и времени
        sendanceDateInput.setAttribute("value", fillValues.currentDate);
        sendanceDateInput.setAttribute("max", fillValues.currentDate);
        sendanceTimeInput.setAttribute("value", fillValues.currentTime);
        sendanceTimeInput.setAttribute("max", fillValues.currentTime);
        receiveDateInput.setAttribute("min", fillValues.currentDate);
        receiveDateInput.setAttribute("max", fillValues.currentDate);
        receiveTimeInput.setAttribute("min", fillValues.currentTime);
        receiveTimeInput.setAttribute("max", fillValues.currentTime);
        
        
        fillValues.types.forEach(type => {
            typeSelect.append(ToOption(type.id, type.type));
        });
        fillValues.locations.forEach(location => {
            locationDatalist.append(ToOption(location));
        });
        fillValues.senders.forEach(sender => {
            senderDatalist.append(ToOption(sender));
        });
        fillValues.receivers.forEach(receiver => {
            receiverDatalist.append(ToOption(receiver));
        });
    }
}

//отправляет данные на сервер; при успешном выполнении перенаправляет пользователя на страницу вывода заявок
async function SendRequest()
{
    let method = "POST";
    let redirectPath = url+tableName+"/requests";
    
    const messageBlock = document.getElementById("message");
    messageBlock.innerHTML = "";
    messageBlock.style.display = "none";
    
    const formData = new FormData(document.getElementById("request-form"));
    let valuesToSend = {};
    for (const entry of formData.entries())
    {
        valuesToSend[entry[0]] = entry[1];
    }
    
    if (window.location.pathname.split("/").length === 6)
    {
        method = "PUT";
        redirectPath += "/edit";
        valuesToSend["id"] = window.location.pathname.split("/")[5];
    }
    
    console.log(valuesToSend);
    
    const request = await fetch(url+"api/"+tableName+"/request",
    {
        method: method,
        header: {"Content-Type": "application/json", "Accept": "application/json"},
        body: JSON.stringify(valuesToSend)
    });
    
    const responce = await request.json();
    console.log(responce.errorMessage);
    if (responce === true)
    {
        window.location.replace(redirectPath);
    }
    else
    {
        const p = document.createElement("p");
        messageBlock.style.display = "block";
        p.setAttribute("id", "message-p");
        p.append(responce);
        messageBlock.append(p);
        //document.getElementById("request-form").reset();
    }
}

//выводит данные заявки по id
async function PrintRequestById()
{
    //достает id из path
    const id = window.location.pathname.split("/")[5];
    const request = await fetch(url+"api/"+tableName+"/request?id="+id,
    {
        method: "GET",
        header: {"Content-Type": "application/json", "Accept": "application/json"}
    });
    
    if (request.ok === true)
    {    
        const responce = await request.json();

        const form = document.getElementById("request-form");
        const sendanceDateInput = document.getElementsByName("sendance-date")[0];
        const sendanceTimeInput = document.getElementsByName("sendance-time")[0];
        const receiveDateInput = document.getElementsByName("receive-date")[0];
        const receiveTimeInput = document.getElementsByName("receive-time")[0];
        const typeInput = document.getElementById("type");
        const contentInput = document.getElementsByName("content")[0];
        const locationInput = document.getElementsByName("location")[0];
        const senderInput = document.getElementsByName("sender")[0];
        const receiverInput = document.getElementsByName("receiver")[0];
        const completionStatusInput = document.getElementById("completion-checkbox");
        const completionDateInput = document.getElementsByName("completion-date")[0];
        const completionTimeInput = document.getElementsByName("completion-time")[0];
        const executorInput = document.getElementById("executor");
        
        sendanceDateInput.value = responce.sendance_date;
        sendanceTimeInput.value = responce.sendance_time;
        receiveDateInput.value = responce.receive_date;
        receiveTimeInput.value = responce.receive_time;
        typeInput.value = responce.type_id;
        contentInput.value = responce.content;
        locationInput.value = responce.location;
        senderInput.value = responce.sender;
        receiverInput.value = responce.receiver;
        completionDateInput.value = responce.completion_date;
        completionTimeInput.value = responce.completion_time;
        executorInput.value = responce.executor;
        if (responce.completion_status === "1")
            completionStatusInput.setAttribute("checked", "true");
    }
}