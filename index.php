<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HTML Element Counter</title>
    <!-- Link to custom stylesheet -->
    <link rel="stylesheet" href="./public/app.css">
    <!-- Link to FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div class="page">
        <div class="header">
            <!-- Page logo and title -->
            <div class="logo">
                <span><</span>#<span>></span>
            </div>
            <div class="logo-text">
                HTML Element Counter
            </div>
        </div>
        <div class="content">
            <!-- Form for inputting URL and element -->
            <form onsubmit="return false" class="form-holder">
                <div class="input-group">
                    <label for="url_input" class="input-label">URL</label>
                    <input type="text" id="url_input" class="the-input">
                    <div class="error" id="url_error"></div>
                </div>
                <div class="input-group">
                    <label for="element_input" class="input-label">Element</label>
                    <input type="text" id="element_input" class="the-input">
                    <div class="error" id="element_error"></div>
                </div>
                <div class="input-group">
                    <button type="button" class="button" id="submit_button">Submit</button>
                </div>
                <div class="error" id="general_error"></div>
            </form>

            <!-- Loader and response display -->
            <div class="res_loader" id="res_loader">
                <img src="./public/pulse.gif" />
            </div>
            <div class="response-holder" id="res_holder">
                <div class="current-request">
                    <div class="url">
                        <span class="logo">URL</span>
                        <span class="link" id="the_url">http://colnect.com/en</span>
                        Fetched on
                        <span class="date" id="request_date">29/06/2023 1:23</span>
                        , took
                        <span class="second" id="load_time"> 0 ms</span>
                    </div>
                    <div class="element">
                        <span class="logo">Element</span>
                        <span class="tag" id="element_tag">
                            <img>
                        </span>
                        appeared
                        <span class="count" id="current_element_count">2</span>
                        times on the page
                    </div>
                </div>
                <div class="general-stats">
                    <!-- General statistics cards -->
                    <div class="card">
                        <div class="count">
                            <i class="fa-solid fa-link"></i>
                            <div class="num" id="check_url">0</div>
                        </div>
                        <div class="label">
                            Checked URLs (domain)
                        </div>
                    </div>
                    <div class="card">
                        <div class="count">
                            <i class="fa-solid fa-clock"></i>
                            <div class="num" id="avg_time">0</div>
                        </div>
                        <div class="label">
                            Average fetch time (24 hrs)
                        </div>
                    </div>
                    <div class="card">
                        <div class="count">
                            <i class="fa-solid fa-code"></i>
                            <div class="num" id="element_count">0</div>
                        </div>
                        <div class="label">
                            Element count (domain)
                        </div>
                    </div>
                    <div class="card">
                        <div class="count">
                            <i class="fa-solid fa-file-code"></i>
                            <div class="num" id="element_count_all">0</div>
                        </div>
                        <div class="label">
                            Element count (all)
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer -->
        <div class="footer">
            Developed by <span>Anteneh Tilaye</span>
        </div>
    </div>

    <!-- Link to custom JavaScript file -->
    <script src="./public/app.js"></script>
</body>

</html>