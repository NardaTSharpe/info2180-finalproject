window.onload = async (e) => {
    const form = document.getElementById("addcontact-form");
    let saveBtn = document.querySelector("#save-btn");
    let responseContainer = document.querySelector("#response-message");
    let url = "contact.php?";

    saveBtn.addEventListener("click", function(e) {
        e.preventDefault();

        const formdata = new FormData(form);
        const data = new URLSearchParams(formdata);

        var request = new XMLHttpRequest();
    
        request.onreadystatechange = function() {
            if(this.status == 200) {
                console.log(request.responseText);
                responseContainer.innerHTML = request.responseText;

                if(request.responseText === "<span class='resMsg'>New user successfully submitted!</span><br>") {
                    form.reset();
                }
            } 
        }; 
        
        request.open("POST", url, true);
        request.send(data);       
    });

    let result = document.getElementById('result');

    let response = await fetch(`contact.php?load=options`);

    if(response.status === 200){
        let data = await response.text();
        result.innerHTML = data;
    } else {
        alert("There was a problem processing your request.");
    }

}
