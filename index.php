<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VideoTube</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="./assets/js/commonActions.js"></script>
</head>
<body>
    <div id="pageContainer">
        <div id="mastHeadContainer">
            <button class="navShowHide">
                <img src="./assets/images/icons/menu.png" title="menu"  alt="Toggle side menu button">
            </button>
            <a class="logoContainer" href="./index.php">
                <img src="./assets/images/icons/VideoTubeLogo.png" title="logo" alt="Site logo">
            </a>
            <div class="searchBarContainer">
                <form action="search.php" method="GET">
                    <input type="text" class="searchBar" name="term" placeholder="Search">
                    <button class="searchButton">
                        <img src="./assets/images/icons/search.png" title="search" alt="Search icon">
                    </button>
                </form>
            </div>
        </div>

        <div id="sideNavContainer" style="display:none;">
        </div>

        <div id="mainSectionContainer">
            <div id="mainContentContainer">
                <p>Hello everyone</p>
            </div>
        </div>
    </div>
</body>
</html>