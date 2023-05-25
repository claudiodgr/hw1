function populatePosts(info) {
    const gridContainer = document.body.querySelector('.grid');
    const column = document.createElement('div');
    column.classList.add('playlist')

    const iframeContainerHTML = `<iframe title="deezer-widget" src="https://widget.deezer.com/widget/dark/playlist/${info['playlist_deezer_id']}" width="100%" height="500" frameborder="0" allowtransparency="true" allow="encrypted-media; clipboard-write"></iframe>`
    column.innerHTML += iframeContainerHTML;

    // <i class="like-button fas fa-heart"></i>
    likeButton = document.createElement('i');
    likeButton.classList.add('like-button', 'fas', 'fa-heart');
    likeButton.addEventListener('click', resolveLike);
    likeButton.dataset.id = info['playlistId'];
    if (info['like']) {
        likeButton.classList.add('liked');
    }
    
    column.appendChild(likeButton);

    const chatbox = document.createElement('div');
    chatbox.classList.add('chatbox');
    chatbox.textContent = info['username'];
    column.appendChild(chatbox);

    gridContainer.appendChild(column);
}

function resolveLike(event) {
    async function resolveResponse(resp) {
        if (!resp.ok) {
            return Promise.reject('Bad response');
        }
        return resp.text();
    }
    const postId = encodeURIComponent(event.currentTarget.dataset.id);
    function resolveResponseCode(respCode) {
        if (respCode === 'Unlike') {
            event.target.classList.add('liked');
        } else {
            event.target.classList.remove('liked');
        }
    }



    fetch(`./home.php?resolveLike=${postId}`).then(resolveResponse).then(resolveResponseCode).catch((error) => alert(error));
}

function jsonHandler(jsonResp) {
    for (let i = 0; i < jsonResp.length; i++) {
        populatePosts(jsonResp[i]);
    }
}

function responseHandler(resp) {
    if (!resp.ok) {
        return Promise.reject("Bad Response");
    }
    return resp.json();
}

function checkOverflowAndAddTooltip() {
    const chatboxes = document.querySelectorAll('.chatbox');

    chatboxes.forEach((chatbox) => {
        const isOverflowing = chatbox.offsetWidth < chatbox.scrollWidth;

        if (isOverflowing) {
            chatbox.setAttribute('title', chatbox.textContent);
        }
    });
}

fetch('./home.php?postList=').then(responseHandler).then(jsonHandler).catch((error) => alert(error))
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


const hearts = document.querySelectorAll('.like-button');
hearts.forEach((heart) => {
    heart.addEventListener('click', function () {
        heart.classList.toggle('liked');
    });
});

checkOverflowAndAddTooltip();
