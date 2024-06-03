<button id="delete" type="button" onclick="DeleteEntry()">Удалить</button>
<script>
    async function DeleteEntry()
    {
        if (window.confirm("Вы действительно хотите удалить запись?"))
        {
            const id = window.location.pathname.split("/")[5];
            
            const request = await fetch(url+"api/"+tableName+"/request/edit",
            {
                method: "DELETE",
                header: {"Content-Type": "application/json", "Accept": "application/json"},
                body: JSON.stringify(id)
            });
            
            const responce = await request.json();
            
            if (responce === true)
            {
                window.location.replace(url+tableName+"/requests/edit");
            }
            else
            {
                const messageBlock = document.getElementById("message");
                const p = document.createElement("p");
                p.append(responce);
                messageBlock.append(p);
            }
        }
    }
</script>