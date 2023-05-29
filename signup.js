async function validate(event) {
    const formElements = register_form.elements;
    const emailVal = formElements["email"].value;
    const passwordVal = formElements["password"].value;
    const passwordConfirmVal = formElements["password_confirm"].value;

    for (let control of formElements) {
        if (control.name == 'password' && control.value.length < 8) {
            event.preventDefault();
            control.setCustomValidity("La password deve essere lunga almeno 8 caratteri");
            
        } else {
            control.setCustomValidity("");
        }

        if (control.value === "" && control.id !== "file_upload") {
            alert("Inserire tutti i campi");
            event.preventDefault();
            return;
        }
    }
    if (!emailVal.match(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/)) {
        event.preventDefault();
        formElements['email'].setCustomValidity("Formato email non valido");
        
    } else {
        formElements['email'].setCustomValidity("");
        
    }
    if (passwordVal != passwordConfirmVal) {
        formElements['password_confirm'].setCustomValidity("Errore conferma password");
        
        event.preventDefault();
    } else {
        formElements['password_confirm'].setCustomValidity("");
        
    }

}

function validateElem(event) {
    const passwordError = document.getElementById('password-error');
    const emailError = document.getElementById('email-error');
    const passwordConfirmError = document.getElementById('password_confirm-error');
    if (event.target.id === 'password' && event.target.value.length < 8) {
        passwordError.textContent = 'La password deve essere di almeno 8 caratteri';
    } else {
        passwordError.textContent = '';
        event.target.setCustomValidity('');
    }

    if (event.target.id === 'email' && !event.target.value.match(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/)) {
        emailError.textContent = 'Formato email non valido';
    } else {
        emailError.textContent = '';
        event.target.setCustomValidity('');
    }

    if (event.target.id === 'password_confirm') {
        const password = document.getElementById('password');
        if (event.target.value !== password.value) {
            passwordConfirmError.textContent = 'Errore conferma password';
        } else {
            passwordConfirmError.textContent = '';
            event.target.setCustomValidity('');
        }
    }

}

if (respCode != '') {
    alert(respCode);
    respCode = '';
}


function onBlur(event) {
    const username = register_form.elements["username"].value;


    function onResponse(response) {
        if (!response.ok) {
            return Promise.reject();
        }
        return response.text();
    }

    function checkFound(text) {
        const errorElem = document.getElementById('username-error');
        if (text == 'found') {
            errorElem.textContent = 'Username già in uso';
        } else {
            errorElem.textContent = '';
        }
    }
    fetch(`${uri}?searchUser=${username}`).then(onResponse).then(checkFound);
}
// popular image types
const fileTypes = [
    "image/apng",
    "image/bmp",
    "image/gif",
    "image/jpeg",
    "image/pjpeg",
    "image/png",
    "image/svg+xml",
    "image/tiff",
    "image/webp",
    "image/x-icon"
];

function FilterFileType(file) {
    return fileTypes.includes(file.type);
}

function returnFileSize(number) {
    if(number < 1024) {
        return number + 'bytes';
    } else if(number >= 1024 && number < 1048576) {
        return (number/1024).toFixed(2) + 'KB';
    } else if(number >= 1048576) {
        return (number/1048576).toFixed(2) + 'MB';
    }
}


function uploadHandler(event) {
    prev.innerHTML = '';

    const currentFiles = image_upload.files;
    if (currentFiles.length === 0) {
        const parag = document.createElement('p');
        parag.textContent = 'No files selected';
        prev.append(parag);
        return;
    } else {
        const parag = document.createElement('p');

        if (FilterFileType(currentFiles[0])) {
            parag.textContent = `Name: ${currentFiles[0].name}, Size: ${returnFileSize(currentFiles[0].size)}.`;
            const image = document.createElement('img');
            image.src = URL.createObjectURL(currentFiles[0]);
            prev.append(parag, image);
        } else {
            parag.textContent = `Name: ${currentFiles[0].name} is not valid. Sorry.`;
            prev.append(parag);
            return;
        }
    }


    function handleFileUploadRes(res) {
        if (!res.ok) {
            return Promise.reject("Bad Response!");
        }

        return res.text();
    }

    function handleTextRes(txt) {
        alert(txt);
    }


    const myFileUpload = new FormData();
    myFileUpload.append('file_to_upload', currentFiles[0]);

    const options = {
        method: 'POST',
        body: myFileUpload
    }

    fetch('./upload.php', options).then(handleFileUploadRes).then(handleTextRes);

}


const register_form = document.forms["register"];
register_form.addEventListener("submit", validate);
register_form["username"].addEventListener("blur", onBlur);
register_form["password"].addEventListener("input", validateElem);
register_form["email"].addEventListener("input", validateElem);
register_form["password_confirm"].addEventListener("input", validateElem);
const image_upload = register_form['file_upload'];
image_upload.addEventListener("change", uploadHandler);
image_upload.style.opacity = "0";
const prev = document.body.querySelector(".prev");