window.onload =  function() {
    var notesText = document.querySelector("#notes-textarea");
    var notesContainer = document.querySelector("#contact-notes");
    var assignBtn = document.querySelector("#assign-btn");
    var leadBtn = document.querySelector("#lead-btn");
    var msgResults = document.querySelector("#msg-results");
    var addNoteBtn = document.querySelector("#add-note-btn");

    notesText.addEventListener('keydown', (e) => {
        // Submit when Enter key is pressed:
        if(e.keyCode == 13){
            e.preventDefault();
        }        
    })

    addNoteBtn.addEventListener('click', async (e) => {
        noteValue = notesText.value;

        let response = await fetch(`viewcontact.php?q=${noteValue}`);

        if(response.status === 200){
            let data = await response.text();
            console.log(data);
            notesContainer.innerHTML += data;
        } else {
            alert("There was a problem processing your request.");
        }
    })

    assignBtn.addEventListener('click', async (e) => {
        // When Enter key is pressed:
            e.preventDefault();
            let response = await fetch(`viewcontact.php?assign="true"`);

            if(response.status === 200){
                let data = await response.text();
                console.log(data);
                msgResults.innerHTML = data;
                console.log("done");
            } else {
                alert("There was a problem processing your request.");
            }
    })

    leadBtn.addEventListener('click', async (e) => {
        // When Enter key is pressed:
            e.preventDefault();
            switchedVal = "";
            if(leadBtn.textContent == "Switch to Sales Lead"){
                leadBtn.textContent = "";
                leadBtn.innerHTML = '<i class="fa-solid fa-down-left-and-up-right-to-center" id="switch-icon"></i>Switch to Support';
                leadBtn.classList.remove('lead-btn');
                leadBtn.classList.add('support-btn');
                switchedVal = "Sales Lead";
            } else{
                leadBtn.textContent = "";
                leadBtn.innerHTML = '<i class="fa-solid fa-down-left-and-up-right-to-center" id="switch-icon"></i>Switch to Sales Lead';
                switchedVal = "Support";
                leadBtn.classList.remove('support-btn');
                leadBtn.classList.add('lead-btn');
            }
            let response = await fetch(`viewcontact.php?lead=${switchedVal}`);

            if(response.status === 200){
                let data = await response.text();
                msgResults.innerHTML = data;
            } else {
                alert("There was a problem processing your request.");
            }
    })   
}