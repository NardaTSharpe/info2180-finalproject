window.onload = async (e) => {
    const result = document.querySelector('#results');
    let filtersList = document.querySelectorAll(".filters li");
    let activeEl = "";
    e.preventDefault();

    const handleActive = async (e) => {
        e.preventDefault();
        filtersList.forEach(el => {
            el.classList.remove("active");
        })
        e.currentTarget.classList.add("active");
        activeEl = e.currentTarget;

        let response = await fetch(`dashboard.php
        ?q=${activeEl.querySelector("a").text}`);

        if(response.status === 200){
            let data = await response.text();
            result.innerHTML = data;
        } else {
            alert("There was a problem processing your request.");
        }
    }

    filtersList.forEach(el => {
        el.addEventListener('click', handleActive);
    })

    let response = await fetch(`dashboard.php`);

        if(response.status === 200){
            let data = await response.text();
            result.innerHTML = data;
        } else {
            alert("There was a problem processing your request.");
        }

}