function countHandler() {
    let url = document.getElementById("url_input").value;
    let element = document.getElementById("element_input").value;

    const xhttp = new XMLHttpRequest();

    xhttp.open('POST', "../../Countify/controllers/request_controller.php");

    xhttp.onload = () => {
        if (xhttp.status === 200) {
            console.log(xhttp.responseText);
        } else {
            console.error('Request failed. Status:', xhttp.status);
        }
    };

    xhttp.onerror = () => {
        console.error('Request failed due to a network error.');
    };

    var data = new FormData();
    data.append('url', url);
    data.append('element', element);

    xhttp.send(data);
}


function validateForm() {
    const urlInput = document.getElementById('url');
    const elementInput = document.getElementById('element');
    const urlPattern = /^(https?:\/\/)?[\w\-]+(\.[\w\-]+)+[/#?]?.*$/;
    const elementPattern = /^[a-zA-Z][a-zA-Z0-9]*$/;
    let isValid = true;

    // Validate URL
    if (!urlPattern.test(urlInput.value)) {
        alert('Please enter a valid URL.');
        isValid = false;
    }

    // Validate HTML Element
    if (!elementPattern.test(elementInput.value)) {
        alert('Please enter a valid HTML element name.');
        isValid = false;
    }

    return isValid;
}