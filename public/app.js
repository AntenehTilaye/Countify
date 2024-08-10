function countHandler() {

    document.getElementById('res_loader').style.display = "block";
    document.getElementById('res_holder').style.display = "none";

    for (let element of document.getElementsByClassName('error')) {
        element.style.display = 'none';
    }
    
    let url = document.getElementById("url_input").value;
    let element = document.getElementById("element_input").value;

    if(!validateForm(url, element)){
        document.getElementById('res_loader').style.display = "none";
        return false;
    }

    const xhttp = new XMLHttpRequest();

    xhttp.open('POST', "../../Countify/controllers/request_controller.php");

    xhttp.onload = () => {
        if (xhttp.status === 200) {
            let res = JSON.parse(xhttp.responseText);

            if(res.is_error == true){
                if(res.error_type == "validation"){
                    if(res.hasOwnProperty('element')){
                        let element_tag = document.getElementById('element_error');
                        element_tag.style.display = 'block';
                        element_tag.innerHTML = res.element;
                        
                    }
                    if(res.hasOwnProperty('url')){
                        let url_tag = document.getElementById('url_error');
                        url_tag.style.display = 'block';
                        url_tag.innerHTML = res.url;
                    }

                }
                else if (res.error_type == "loading"){
                    let general_tag = document.getElementById('general_error');
                    general_tag.style.display = 'block';
                    general_tag.innerHTML = res.error_message;
                }

                document.getElementById('res_loader').style.display = "none";
               

            } else {
                document.getElementById('res_loader').style.display = "none";
                document.getElementById('res_holder').style.display = "flex";
            
                document.getElementById('the_url').innerHTML = res.url;
                document.getElementById('request_date').innerHTML = convert_date(res.date);
                document.getElementById('load_time').innerHTML = res.duration + " ms";
                document.getElementById('element_tag').innerHTML = "< " + res.element + " >";
                document.getElementById('current_element_count').innerHTML = res.count;

                document.getElementById('check_url').innerHTML = res.check_url;
                document.getElementById('avg_time').innerHTML = res.avg_time + " ms";
                document.getElementById('element_count').innerHTML = res.element_domain_count;
                document.getElementById('element_count_all').innerHTML = res.element_all_count;
            }

        } else {
            document.getElementById('general_error').innerHTML = "Request failed: Please Try Again Later.";
        }
    };

    xhttp.onerror = () => {
        document.getElementById('general_error').innerHTML = "Request failed due to a network error.";
    };

    var data = new FormData();
    data.append('url', url);
    data.append('element', element);

    xhttp.send(data);
}


function validateForm(url, element) {
    
    const urlPattern = /^(https?:\/\/)?[\w\-]+(\.[\w\-]+)+[/#?]?.*$/;
    const elementPattern = /^[a-zA-Z][a-zA-Z0-9]*$/;
    let isValid = true;

    // Validate URL
    if (!urlPattern.test(url)) {
        let url_tag = document.getElementById('url_error');
        url_tag.innerHTML = 'Please enter a valid URL.';
        url_tag.style.display = 'block';
        isValid = false;
    }

    // Validate HTML Element
    if (!elementPattern.test(element)) {
        let element_tag = document.getElementById('element_error');
        element_tag.innerHTML = 'Please enter a valid HTML element name.';
        element_tag.style.display = 'block';
        isValid = false;
    }

    return isValid;
}

function convert_date(dateStr) {
        // Parse the date string into a Date object
    const date = new Date(dateStr.replace(" ", "T"));

    // Format the date to dd/mm/yyyy
    const formattedDate = date.toLocaleDateString("en-GB").split('/').join('/');

    // Format the time to hh:mm AM/PM
    const formattedTime = date.toLocaleTimeString("en-GB", {
        hour: '2-digit',
        minute: '2-digit',
        hour12: true
        });

        return `${formattedDate} ${formattedTime}`;

}