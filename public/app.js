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
