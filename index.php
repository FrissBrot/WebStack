<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8" content="text/html">
    <meta name="description" content="Create your Stack!">
    <meta name="keywords"
        content="Timo, Timo Weber, Weber, personalwebsite, website, Weber Timo, timo weber, weber timo, Webstack, Stackme, Stack, Web, stack, link, LinkTree, Tree">
    <meta name="author" content="WebStack">
    <meta name="robots" content="noindex" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="mobile-web-app-title" content="Webstack">
    <meta name="apple-mobile-web-app-title" content="Webstack">

    <link rel="stylesheet" type="text/css" href="style.css" media="screen" />

    <link id="favicon" rel="icon" type="image/gif" href="favicon.gif">
    <link rel="icon" href="favicon.gif" type="image/gif">
    <link rel="shortcut icon" type="image/gif" href="favicon.gif">
    <link rel="apple-touch-icon" href="favicon.gif">
    <link rel="apple-touch-icon" sizes="152x152" href="favicon.gif">
    <link rel="apple-touch-icon" sizes="180x180" href="favicon.gif">
    <link rel="apple-touch-icon" sizes="167x167" href="favicon.gif">


    <meta property="og:title" content="WebStack">
    <meta property="og:description" content="Create your Stack!">
    <meta property="og:image" content="favicon.gif">
    <meta property="og:image:type" content="image/gif">
    <meta property="og:url" content="https://tweber.ch/">
    <meta property="og:site_name" content="Webstack">




    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;700;800&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <script src="https://unpkg.com/akar-icons-fonts"></script>

    <?php $login = 0?>
    <!-- Webstack -->
    <title>Webstack • Home</title>
    <div class="Navigate">
        <button class="NavigateFont" onclick="scrollToLogin(1)">LOGIN</button>
        <button class="NavigateFont marginleft30" onclick="scrollToLogin(2)">REGISTRIEREN</button>
    </div>
</head>

<body>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <div class="WarnungTop">
        <div id="Warnung">
            <span id="WarnungText">Warung</span>
        </div>
    </div>
    <div class="WebstackStart" id="WebstackStart">
        <div class="WebstackStartSchrift">
            <h1 class="WebstackH1"><span class="WebstackSpan">Webstack</span>.</h1>
        </div>
    </div>
    <div class="LoginPage">
        <div id="LoginWindow">
            <div class="tab">
                <button class="tablinks" id="loginbutton" onclick="openCity(event, 'Login')">Login</button>
                <button class="tablinks marginleft30 " id="registrierenbutton"
                    onclick="openCity(event, 'Registrieren')">Registrieren</button>
            </div>
            <div class="LoginTabs">
                <div id="Login" class="tabcontent">
                    <?php
                        if(isset($_POST["submit"])){
                            require("mysql.php");
                            $stmt = $mysql->prepare("SELECT * FROM accounts WHERE USERNAME = :user"); //Username überprüfen
                            $stmt->bindParam(":user", $_POST["username"]);
                            $stmt->execute();
                            $count = $stmt->rowCount();
                            if($count == 1){
                                //Username existiert
                                $row = $stmt->fetch();
                                if(password_verify($_POST["pw"], $row["PASSWORD"])){
                                    session_start();
                                    $_SESSION['username'] = $row["USERNAME"];
                                    header("Location: editor.php");
                                    } else {
                                        echo "Das Passwort ist falsch.";
                                    }
                            } else {
                                echo "Benutzername Existiert nicht";
                            }
                        }
                    ?>
                    <h3>Login</h3>
                    <form action="index.php" method="post" class="FormStyle">
                        <input type="text" name="username" placeholder="Username" required><br>
                        <input type="password" name="pw" placeholder="Passwort" required><br>
                        <div class="FormularButton">
                            <button id="ButtonBasicHover" type="submit" name="submit">Einloggen</button>
                        </div>
                    </form>
                </div>

                <div id="Registrieren" class="tabcontent">
                    <?php 
                        if (isset($_POST["submit"])) {
                                require("mysql.php");
                                $stmt = $mysql->prepare("SELECT * FROM accounts WHERE USERNAME = :user"); //Username überprüfen
                                $stmt->bindParam(":user", $_POST["username"]);
                                $stmt->execute();
                                if($stmt->rowCount() == 0){
                                    //Username ist frei
                                    if($_POST["pw1"] == $_POST["pw2"]){ //überprüfung ob beide Passwörter übereinstimmen.
                                        //user Erstellen
                                        $stmt = $mysql->prepare("INSERT INTO accounts (USERNAME, PASSWORD, colors, ButtonText, ButtonHyperlink) VALUES (:user, :pw1, '5395a7,9bfff4,82e8bf', '1,Dein erster Link', ',Soo viel Möglichkeiten')");
                                        $stmt->bindParam(":user", $_POST["username"]);
                                        $hash = password_hash($_POST["pw1"], PASSWORD_BCRYPT);
                                        $stmt->bindParam(":pw1", $hash);
                                        $stmt->execute();
                                        
                                        //angehendes automatisches Login
                                            require("mysql.php");
                                            $stmt = $mysql->prepare("SELECT * FROM accounts WHERE USERNAME = :user"); //Username überprüfen
                                            $stmt->bindParam(":user", $_POST["username"]);
                                            $stmt->execute();
                                            $count = $stmt->rowCount();
                                            if($count == 1){
                                                //Username ist frei
                                                $row = $stmt->fetch();
                                                if(password_verify($_POST["pw1"], $row["PASSWORD"])){
                                                    session_start();
                                                    $_SESSION['username'] = $row["USERNAME"];
                                                    header("Location: editor.php");
                                                    } else {
                                                        echo "Das Passwort ist falsch.";
                                                    }
                                            } else {
                                                echo "Benutzername Existiert nicht";
                                            }

                                        }
                                    } else {
                                        echo "Die Passwörter stimmen nicht überein";
                                    }
                                } else {
                                    echo "Der Username ist bereits vergeben";
                                }
                        
                        ?>
                    <h3>Registrieren</h3>
                    <form action="index.php" method="post" class="FormStyle">
                        <input type="email" name="email" placeholder="E-Mailadresse" required><br>
                        <input type="text" id="username" name="username" placeholder="Benutzername" required><br>
                        <input type="password" name="pw1" placeholder="Passwort" required><br>
                        <input type="password" name="pw2" placeholder="Passwort bestätigen" required><br>
                        <div class="FormularButton">
                            <button type="submit" name="submit" class="ButtonHoverEffect" role="button"><span
                                    class="text">Erstellen</span><span>Lets Goo!</span></button>
                        </div>
                </div>
            </div>
        </div>
    </div>
    <div id="MarketingPage">
        <div class="OnePage" id="MarketingEvent1">
            <p>p</p>
            <div id="Marketing1">
                <div id="MarketingText1">
                    <h1><a class="nogardient">Make it <br></a>Simple.</h1>
                </div>
                <div id="MarketingExample1" class="MarketingExampleInnerStyle MarketingExample">
                    <img src="ProfileMan.webp" alt="Profilbild">
                    <p>Chris Anderson</p>
                    <button><i class="ai-github-fill"></i>GitHub</button>
                    <button><i class="ai-spotify-fill"></i>Spotify</button>
                    <button><i class="ai-youtube-fill"></i>Youtube</button>
                    <button><i class="ai-instagram-fill"></i>Instagramm</button>
                </div>
            </div>
        </div>

        <div class="OnePage" id="MarketingEvent2">
            <p>a</p>
            <div id="Marketing2">
                <div id="MarketingText2">
                    <h1><a class="nogardient">It is <br></a>fancy.</h1>
                </div>
                <div id="MarketingExample2" class="MarketingExampleInnerStyle MarketingExample">
                    <img src="ProfileWoman.webp" alt="Profilbild">
                    <p>Sofia Martinez</p>
                    <button><i class="ai-linkedin-box-fill"></i>LinkedIn</button>
                    <button><i class="ai-behance-fill"></i>Behance</button>
                    <button><i class="ai-instagram-fill"></i>Instagramm</button>
                    <button><i class="ai-youtube-fill"></i>Youtube</button>
                </div>
            </div>
        </div>
    </div>
</body>
<footer>
    <div>
        <ul>
            <li>Impressum</li>
            <li>Timo Weber</li>
            <li>Kanton Obwalden</li>
            <li>Schweiz (CH)</li>
            <li>info@frissbrot.ch</li>
        </ul>
    </div>
    <div>
        <ul>
            <li>
                <p>Links</p>
            </li>
            <li><a href="https://github.com/FrissBrot/webstack" target="_blank">Quell Code</a></li>
            <li><a href="https://github.com/FrissBrot" target="_blank">GitHub</a></li>
            <li><a href="https://tweber.ch/datenschutz" target="_blank">Datenschutzbestimmung</a></li>
        </ul>
    </div>
</footer>
<script>
document.getElementById("loginbutton").click();
//document.getElementById("MarketingEvent1").addEventListener("mouseover", SlideAnimation());

function openCity(evt, cityName) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}

function scrollToLogin(mode) {
    document.querySelector('.LoginPage').scrollIntoView({
        behavior: 'smooth'
    });

    if (mode === 1) {
        openCity(event, 'Login');
        document.getElementById("loginbutton").click();
    } else {
        openCity(event, 'Registrieren');
        document.getElementById("registrierenbutton").click();
    }
}
/*
document.getElementById('MarketingEvent1').onmouseover = function SlideAnimation() {

    document.getElementById('MarketingText1').style.animation =
        "slide-right 0.5s cubic-bezier(0.250, 0.460, 0.450, 0.940) both";
    document.getElementById('MarketingExample1').style.animation =
        "slide-left 0.5s cubic-bezier(0.250, 0.460, 0.450, 0.940) both";
}

/*document.getElementById('MarketingEvent1').onmouseleave = function SlideAnimation() {

document.getElementById('MarketingText1').style.animation =
    "slide-right 0.5s cubic-bezier(0.250, 0.460, 0.450, 0.940) both reverse";
document.getElementById('MarketingExample1').style.animation =
    "slide-left 0.5s cubic-bezier(0.250, 0.460, 0.450, 0.940) both reverse";
console.log('out');
}

document.getElementById('MarketingEvent2').onmouseover = function SlideAnimation() {

    document.getElementById('MarketingExample2').style.animation =
        "slide-right 0.5s cubic-bezier(0.250, 0.460, 0.450, 0.940) both";
    document.getElementById('MarketingText2').style.animation =
        "slide-left 0.5s cubic-bezier(0.250, 0.460, 0.450, 0.940) both";
}*/

var elements = document.querySelectorAll(".MarketingExampleInnerStyle");

function isElementInViewport(element) {
    var rect = element.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
}

function callbackFunc() {
    console.log(i);
    for (var i = 0; i < elements.length; i++) {
        if (isElementInViewport(elements[i])) {
            elements[i].classList.add("visible");

        }

        /* Else-Bedinung entfernen, um .visible nicht wieder zu löschen, wenn das Element den Viewport verlässt. */
        else {
            elements[i].classList.remove("visible");

        }
    }
}

window.addEventListener("load", callbackFunc);
console.log("addEventlistener");
window.addEventListener("scroll", callbackFunc);

//funktion lässt kleine Warung erscheinen.
function warnung(Inhalt) {
    document.getElementById('Warnung').style.animation = "warnung 0.2s ease-out";
    document.getElementById("Warnung").style.opacity = "1";
    document.getElementById('WarnungText').innerHTML = Inhalt;
    setTimeout(warnungHide, 5000)
}

function warnungHide() {
    document.getElementById('Warnung').style.animation = "warnungOpacity 0.2s linear";
    setTimeout(warnungHide2, 200)
}

function warnungHide2() {
    document.getElementById("Warnung").style.opacity = "0";
}
</script>

</html>