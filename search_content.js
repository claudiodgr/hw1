function addToggle(event) {

    function responseHandler(res) {
        if (!res.ok) {
            return Promise.reject("Invalid Response");
        }
        return res.text();
    }
    function textHandler(text) {
        if (text == "321") {
            alert("error invalid parameters OR invalid session");
            return;
        }

        if (text == "545") {
            alert("error user not found");
            return;
        }

        if (text == "success") {
            event.target.classList.add('added');
            return;
        }

        if (text == "delete") {
            event.target.classList.remove('added');
            return;
        }

        console.error("error");

    }

    fetch(`./search_content.php?playlistId=${event.target.dataset.id}`).then(responseHandler).then(textHandler);
}

function submitHandler(event) {
    event.preventDefault();
    const wrapper = document.querySelector('.wrapper');
    const resultsContainer = document.querySelector(".results-container");
    wrapper.style.top = '3rem';
    resultsContainer.style.opacity = '0';


    if (document.body.querySelector(".results-container")) {
        document.body.querySelector(".results-container").innerHTML = "";
    }



    async function responseHandler(response) {
        if (!response.ok) {
            return Promise.reject();
        }

        return response.json();
    }

    function jsonHandler(textRes) {
        const PAGINATION = 10;


        const gridContainer = document.querySelector(".results-container");
        gridContainer.style.opacity = '1';
        const jsonData = textRes['data'];
        let count = 0;
        for (const queryResult of jsonData) {
            if (count >= PAGINATION) {
                break;
            }
            const column = document.createElement('div');
            column.classList.add('playlist')

            const addButton = document.createElement('i');
            // <i class="fa-sharp fa-regular fa-plus"></i>
            addButton.classList.add('add-button', 'fa-sharp', 'fa-regular', 'fa-plus');
            addButton.dataset.id = queryResult['id'];

            column.appendChild(addButton);

            const iframeContainerHTML = `<iframe title="deezer-widget" src="https://widget.deezer.com/widget/dark/playlist/${queryResult['id']}" width="100%" height="500" frameborder="0" allowtransparency="true" allow="encrypted-media; clipboard-write"></iframe>`;
            column.innerHTML += iframeContainerHTML;

            gridContainer.appendChild(column);
            count++;
        }

        const addButtons = document.querySelectorAll('.add-button');
        for (const addButt of addButtons) {
            function addButtonResponseHandler(res) {
                if (!res.ok) {
                    return Promise.reject("Invalid Response");
                }
                return res.text();
            }
            function addButtonTextHandler(text) {
                if (text == "321") {
                    alert("error invalid parameters OR invalid session");
                    return;
                }

                if (text == "545") {
                    alert("error user not found");
                    return;
                }

                if (text == "delete") {
                    addButt.classList.add('added');
                    return;
                }

                if (text == "notpresent") {
                    addButt.classList.remove('added');
                    return;
                }

                console.error("error");

            }
            fetch(`./search_content.php?playlistIdChk=${addButt.dataset.id}`).then(addButtonResponseHandler).then(addButtonTextHandler);
            addButt.addEventListener('click', addToggle);
        }

    }



    const form_data = new FormData(search_form);
    
    const options = {
        method: 'POST',
        body: form_data
    }

    fetch(`${uri}/../do_search_content.php`, options).then(responseHandler).then(jsonHandler);
}

var hamburgerMenu = document.querySelector('.hamburger-menu');
var navigationDrawer = document.getElementById('navigation-drawer');
var backdrop = document.getElementById('backdrop');

hamburgerMenu.addEventListener('click', function () {
    navigationDrawer.classList.toggle('open');
    backdrop.classList.toggle('visible');
});

backdrop.addEventListener('click', function () {
    navigationDrawer.classList.remove('open');
    backdrop.classList.remove('visible');
});

const search_form = document.querySelector('.search-form');

search_form.addEventListener('submit', submitHandler);
