window.onload = async () => {
	let loginBtn = document.querySelector("#login-btn");
	let resMsgs = document.querySelector("#result-msgs");
	const form = document.querySelector("#login-form");
	let url = "login.php?";
	
    loginBtn.addEventListener("click", function(e) {
        e.preventDefault();
		console.log('db');
        const formdata = new FormData(form);
        const data = new URLSearchParams(formdata);

        var request = new XMLHttpRequest();
    
        request.onreadystatechange = function() {
            if(this.status == 200) {
                console.log(request.responseText);
                resMsgs.innerHTML = request.responseText;
                // form.reset();
				if(request.responseText === "redirect") {
                    form.reset();
					window.location.href = "dashboard.html";
                }
            }
        }; 
        
        request.open("POST", url, true);
        request.send(data);       
    });
    

}



