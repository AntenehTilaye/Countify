<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HTML Element Counter</title>
    <link rel="stylesheet" href="./public/app.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div class="page">
        <div class="header">
            <div class="logo">
                #
            </div>
            <div class="logo-text">
                HTML Element Counter
            </div>
        </div>
        <div class="content">

            <form onsubmit="" class="form-holder">
                <div class="input-group">
                    <label for="url_input" class="input-label">URL</label>
                    <input type="text" id="url_input" class="the-input">
                </div>
                <div class="input-group">
                    <label for="element_input" class="input-label">Element</label>
                    <input type="text" id="element_input" class="the-input">
                </div>
                <div class="input-group">
                    <button type="submit" class="button">Submit</button>
                </div>
            </form>

            <div class="response-holder">
                <div class="current-request">
                    <div class="url">
                        <span class="logo">URL</span>
                        <span class="link">http://colnect.com/en</span>
                        Fetched on
                        <span class="date">29/06/2023 1:23</span>
                        , took
                        <span class="second">532msec</span>
                    </div>
                    <div class="element">
                        <span class="logo">Element</span>
                        <span class="tag">
                            < img>
                        </span>
                        appeared
                        <span class="count">2</span>
                        time on the page
                    </div>
                </div>
                <div class="general-stats">

                    <div class="card">
                        <div class="count">
                            <i class="fa-solid fa-link"></i>
                            <div class="num">323</div>
                        </div>
                        <div class="label">
                            Checked URLs (domain)
                        </div>
                    </div>
                    <div class="card">
                        <div class="count">
                            <i class="fa-solid fa-clock"></i>
                            <div class="num">323</div>
                        </div>
                        <div class="label">
                            Avg. fetch time (24 hrs)
                        </div>
                    </div>


                    <div class="card">
                        <div class="count">
                            <i class="fa-solid fa-code"></i>
                            <div class="num">323</div>
                        </div>
                        <div class="label">
                            Element count (domain)
                        </div>
                    </div>


                    <div class="card">
                        <div class="count">
                            <i class="fa-solid fa-file-code"></i>
                            <div class="num">323</div>
                        </div>
                        <div class="label">
                            Element count (all)
                        </div>
                    </div>


                </div>
            </div>
        </div>
        <div class="footer">
            Developed by <span>Anteneh Tilaye</span>        
        </div>
    </div>
</body>

</html>