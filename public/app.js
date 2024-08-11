/**
 * CountHandler Class
 * 
 * This class is responsible for handling the process of counting HTML elements on a webpage.
 * It retrieves user input for a URL and an HTML element, validates the input, sends the data 
 * to a server via an XMLHttpRequest, and then processes and displays the server's response.
 * 
 * Main functionalities include:
 * - Form validation for URL and HTML element input.
 * - Sending POST requests to the server with the input data.
 * - Handling the server's response and displaying results or error messages to the user.
 * - Showing a loading indicator while the request is being processed.
 * 
 * Usage:
 * - Create an instance of CountHandler and attach the `countHandler` method to the desired event (e.g., button click).
 * 
 * Example:
 * const handler = new CountHandler();
 * document.getElementById('submit_button').addEventListener('click', () => handler.countHandler());
 * 
 * Dependencies:
 * - The class expects specific HTML elements with certain IDs (e.g., 'url_input', 'element_input', 'res_loader', etc.).
 * - The server endpoint should handle the POST request and return a JSON response with the required data.
 */


class CountHandler {
    constructor() {
        // Initialize references to the HTML elements used in the class
        this.resLoader = document.getElementById('res_loader');         // Loader element shown during the request
        this.resHolder = document.getElementById('res_holder');         // Container to display results
        this.urlInput = document.getElementById("url_input");           // Input field for the URL
        this.elementInput = document.getElementById("element_input");   // Input field for the HTML element
        this.urlError = document.getElementById('url_error');           // Element to display URL validation errors
        this.elementError = document.getElementById('element_error');   // Element to display HTML element validation errors
        this.generalError = document.getElementById('general_error');   // Element to display general errors
    }

    countHandler() {
        // Show loader and hide any previous error messages
        this.showLoader();
        this.hideErrors();

        // Get the values entered by the user
        let url = this.urlInput.value;
        let element = this.elementInput.value;

        // Validate the form inputs before proceeding
        if (!this.validateForm(url, element)) {
            this.hideLoader();
            return false;
        }

        // Create a new XMLHttpRequest to send the data to the server
        const xhttp = new XMLHttpRequest();
        xhttp.open('POST', "../../Countify/controllers/request_controller.php");

        // Handle the server's response when it is received
        xhttp.onload = () => this.handleResponse(xhttp);
        xhttp.onerror = () => this.showError("Request failed due to a network error.");

        // Create a FormData object to send the URL and HTML element to the server
        const data = new FormData();
        data.append('url', url);
        data.append('element', element);

        // Send the request to the server
        xhttp.send(data);
    }

    showLoader() {
        // Show the loader and hide the results container
        this.resLoader.style.display = "block";
        this.resHolder.style.display = "none";
    }

    hideLoader() {
        // Hide the loader
        this.resLoader.style.display = "none";
    }

    hideErrors() {
        // Hide all error messages on the page
        for (let element of document.getElementsByClassName('error')) {
            element.style.display = 'none';
        }
    }

    validateForm(url, element) {
        // Regular expressions to validate the URL and HTML element name
        const urlPattern = /^(https?:\/\/)?[\w\-]+(\.[\w\-]+)+[/#?]?.*$/;
        const elementPattern = /^[a-zA-Z][a-zA-Z0-9]*$/;
        let isValid = true;

        // Validate the URL and display an error if it's invalid
        if (!urlPattern.test(url)) {
            this.showError("Please enter a valid URL.", this.urlError);
            isValid = false;
        }

        // Validate the HTML element name and display an error if it's invalid
        if (!elementPattern.test(element)) {
            this.showError("Please enter a valid HTML element name.", this.elementError);
            isValid = false;
        }

        // Return true if both inputs are valid, otherwise false
        return isValid;
    }

    showError(message, element) {
        // Display an error message in the specified element
        element.innerHTML = message;
        element.style.display = 'block';
    }

    handleResponse(xhttp) {
        // Handle the response from the server
        if (xhttp.status === 200) {
            let res = JSON.parse(xhttp.responseText);

            // Handle errors returned by the server
            if (res.is_error) {
                this.handleErrorResponse(res);
            } else {
                // Handle a successful response
                this.handleSuccessResponse(res);
            }
        } else {
            // Display a general error message if the request failed
            this.showError("Request failed: Please Try Again Later.", this.generalError);
        }
    }

    handleErrorResponse(res) {
        // Handle errors based on the type of error returned by the server
        if (res.error_type === "validation") {
            if (res.hasOwnProperty('element')) {
                this.showError(res.element, this.elementError);
            }
            if (res.hasOwnProperty('url')) {
                this.showError(res.url, this.urlError);
            }
        } else if (res.error_type === "loading") {
            this.showError(res.error_message, this.generalError);
        }
        this.hideLoader();
    }

    handleSuccessResponse(res) {
        // Handle a successful response by displaying the results
        this.hideLoader();
        this.resHolder.style.display = "flex";

        // Update the result container with the data from the server
        document.getElementById('the_url').innerHTML = res.url;
        document.getElementById('request_date').innerHTML = this.convertDate(res.date);
        document.getElementById('load_time').innerHTML = res.duration + " ms";
        document.getElementById('element_tag').innerHTML = "< " + res.element + " >";
        document.getElementById('current_element_count').innerHTML = res.count;

        document.getElementById('check_url').innerHTML = res.check_url;
        document.getElementById('avg_time').innerHTML = res.avg_time + " ms";
        document.getElementById('element_count').innerHTML = res.element_domain_count;
        document.getElementById('element_count_all').innerHTML = res.element_all_count;
    }

    convertDate(dateStr) {
        // Convert the date string into a more readable format
        const date = new Date(dateStr.replace(" ", "T"));
        const formattedDate = date.toLocaleDateString("en-GB").split('/').join('/');
        const formattedTime = date.toLocaleTimeString("en-GB", {
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        });
        return `${formattedDate} ${formattedTime}`;
    }
}

// Instantiate the CountHandler class and attach the countHandler method to the submit button
const handler = new CountHandler();
document.getElementById('submit_button').addEventListener('click', () => handler.countHandler());
