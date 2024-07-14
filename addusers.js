window.onload = async function() {
    const form = document.getElementById("adduser-form");
    var result = document.getElementById("result");
    let submitBtn = document.querySelector("#submit-user");
    let password = document.querySelector("#password");
    var url = "adduser.php?";


    submitBtn.addEventListener("click", function(event) {
        event.preventDefault();

        const formdata = new FormData(form);
        const data = new URLSearchParams(formdata);

        var request = new XMLHttpRequest();
    
        request.onreadystatechange = function() {
            if(this.status == 200) {
                console.log(request.responseText);
                result.innerHTML = request.responseText;

                if(request.responseText === "<span class='resMsg'>New user successfully submitted!</span><br>") {
                    form.reset();
                }
            }
        }; 
        
        request.open("POST", url, true);
        request.send(data);       
    });
    
    // Prevents space keys for password input
    password.addEventListener('keypress', function(event) {  
        var key = event.keyCode;
        if(key === 32) {
            event.preventDefault();
        }
    });
}
