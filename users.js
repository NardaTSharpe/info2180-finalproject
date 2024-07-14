window.onload = async function() {
    var result = document.getElementById("results");
    var response = await fetch('users.php');

    if(response.status === 200) {
        var data = await response.text();
        result.innerHTML = data;
    } 
    
    else {
        alert("There was a problem processing your request.");
    }       
}