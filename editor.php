<?php
session_start();
if(!isset($_SESSION['username'])){
  header("Location: index.php");
  exit;
}

$loggedusername = $_SESSION['username'];

if(count($_GET) > 0){ //wird nur ausgeführt, wenn array grösser 0

    $colors = $_GET["backgroundInpute"] ."," .$_GET["buttonInput"] ."," .$_GET["hoverInput"];
    $ButtonText = $_GET["feldInput"];

}
else{
    
    $colors = 0;

}; 

if(strlen($colors) > 1){
    
    //Farb Daten in Datenbank Speichern
    require("mysql.php");
    $stmt = $mysql->prepare("UPDATE accounts SET colors = :color WHERE USERNAME = :username");
    $stmt->bindValue(":username", $loggedusername);
    $stmt->bindValue(":color", $colors);
    $stmt->execute();

};

if(strlen($ButtonText) > 1){

    //Text Daten in Datenbank Speichern
    require("mysql.php");
    $stmt = $mysql->prepare("UPDATE accounts SET ButtonText = :Inhalt WHERE USERNAME = :username");
    $stmt->bindValue(":username", $loggedusername);
    $stmt->bindValue(":Inhalt", $ButtonText);
    $stmt->execute();

};


//Farb Daten Abfragen
require("mysql.php");
$stmt = $mysql->prepare("SELECT colors FROM accounts WHERE USERNAME = :user"); //Username überprüfen
$stmt->bindValue(":user", $loggedusername);
$stmt->execute();
$SavedColors = $stmt->fetch();

// SavedColors in Array umwandeln
$SavedColors1 = explode(",", $SavedColors[0]);

//Text Daten Abfragen
require("mysql.php");
$stmt = $mysql->prepare("SELECT ButtonText FROM accounts WHERE USERNAME = :user"); //Username überprüfen
$stmt->bindValue(":user", $loggedusername);
$stmt->execute();
$SavedText = $stmt->fetch();

// Text in Array umwandeln
$SavedText1 = explode(",", $SavedText[0])
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="style.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="editor_style.css" media="screen" />
    <link rel="icon" href="pictures\favicon.gif" type="image/gif">
    <title>Webstack • Editor</title>
</head>

<body>
    <div class="Navigate">
        <a class="NavigateFont marginleft30" href="logout.php">ABMELDEN</a>
    </div>
    <div id="editfield">
        <div Id="editbar">
            <p id="EditBarTitle">BEARBEITEN</p>
            <div id="FarbenBereich">
                <p class="NavZwischenTittel">Farben</p>
                <form>
                    <label for="kb_selected_color_background" class="EditBarAtributesText">Hintergrundfarbe:</label>
                    <input type="color" id="kb_selected_color_background" class="EditBarAtributes PickerStyle"
                        value="#82E8BF">
                    <span id="hex-background" style="display: none;">(#000000)</span>
                </form>
                <form>
                    <label for="kb_selected_color_button" class="EditBarAtributesText">Buttonfarbe </label>
                    <input type="color" id="kb_selected_color_button" class="PickerStyle" value="#82E8BF">
                    <span id="hex-button" style="display: none;">(#000000)</span>
                </form>
                <form>
                    <label for="kb_selected_color_hover" class="EditBarAtributesText">Hoverfarbe </label>
                    <input type="color" id="kb_selected_color_hover" value="#82E8BF" class="PickerStyle">
                    <span id="hex-hover" style="display: none;">(#000000)</span>
                </form>
            </div>
            <div class="TrennBorder"></div>
            <div id="TextBereich">
                <p class="NavZwischenTittel">Text</p>
                <form>
                    <div class="NAV_Overflow" id="TextBereichGenerate"></div>
                </form>
                <button class="EditButtons" onclick="erstellenButton()">erstellen</button>
            </div>
            <div class="TrennBorder"></div>
            <button class="EditButtons"
                onclick="VarSubmit(removeFirstChar(backgroundInput.value), removeFirstChar(buttonInput.value), removeFirstChar(hoverInput.value))">Speichern</button>
        </div>
        <div id="previewbody">
            <img class="profileborder profilbildsize" src="pictures\pb.jpg" alt="Profilbild">
            <h1 id=usernameshow>Dein Benutzername</h1>
            <!-- <button type="button"
                onclick="window.open('https://open.spotify.com/user/69qw99f7ej6x4j26813u9hnly?si=bc5435e89ad54602', '_blank');"
                class="linkbutton" id="ButId1"><img alt="Spotify Icon" class="iconsize"
                    src="pictures\spotify.png">Spotify</button><br>
-->
        </div>
    </div>

</body>
<script>
var name = <?php echo json_encode($loggedusername); ?>;
document.getElementById('usernameshow').innerHTML = name;

// Farbe aus ColorPicker auslesen
var backgroundInput = document.getElementById("kb_selected_color_background");

var theColor = backgroundInput.value;
backgroundInput.addEventListener("input", function() {

    // Farcode (Hex) schreiben
    document.getElementById("hex-background").innerHTML = backgroundInput.value;

    // Farbvariable schreiben
    document.documentElement.style.setProperty('--kb-color-background', backgroundInput.value);

}, false);

// Farbe aus ColorPicker auslesen
var buttonInput = document.getElementById("kb_selected_color_button");

var theColor = buttonInput.value;
buttonInput.addEventListener("input", function() {

    // Farcode (Hex) schreiben
    document.getElementById("hex-button").innerHTML = buttonInput.value;

    // Farbvariable schreiben
    document.documentElement.style.setProperty('--kb-color-button', buttonInput.value);
}, false);

// Farbe aus ColorPicker auslesen
var hoverInput = document.getElementById("kb_selected_color_hover");

var theColor = hoverInput.value;
hoverInput.addEventListener("input", function() {

    // Farcode (Hex) schreiben
    document.getElementById("hex-hover").innerHTML = hoverInput.value;

    // Farbvariable schreiben
    document.documentElement.style.setProperty('--kb-color-hover', hoverInput.value);
}, false);


var ButtonText = <?php echo json_encode($SavedText1); ?>;

//Buttons aus Datenbank erstellen
for (i = 1; i <= "<?php echo ($SavedText1[0]); ?>"; i++) {

    //Buttons erstellen
    const btn = document.createElement("button");
    btn.innerHTML = ButtonText[i];
    btn.setAttribute("id", "ButId" + i);
    btn.setAttribute("class", "linkbutton");
    document.getElementById("previewbody").appendChild(btn);

    //Textfeld erstellen
    const btnT = document.createElement('input');
    btnT.type = 'text';
    btnT.setAttribute("id", "Feld_" + i);
    btnT.setAttribute("class", "EditTextInput")
    btnT.setAttribute("value", ButtonText[i])
    document.getElementById("TextBereichGenerate").appendChild(btnT);

    //Eventlisterner hinzufügen
    var elem = document.getElementById("Feld_" + i);
    elem.addEventListener("change", function() {
            TextAnpassen();
        },
        false);

};

//Textfeld anpassen
function TextAnpassen() {

    for (Id = 1; Id < ButtonCount; Id++) {

        var TextFeldNr = "#Feld_" + Id;
        var ButtonNr = "ButId" + Id;
        document.getElementById(ButtonNr).innerText = (document.querySelector(TextFeldNr).value);
    }
}


function removeFirstChar(string) {
    let str1 = string;
    let result = str1.slice(1);
    return result;
}

var ButtonCount = <?php echo ($SavedText1[0]); ?>; //Zählt wie viele Buttons erstellt wurden.

function erstellenButton() {

    //ButtonCount hochzählen
    ButtonCount++;

    //Button erstellen
    const btn = document.createElement("button");
    btn.innerHTML = "neuer Button";
    btn.setAttribute("id", "ButId" + ButtonCount);
    btn.setAttribute("class", "linkbutton");
    document.getElementById("previewbody").appendChild(btn);

    //Textfeld erstellen
    const btnT = document.createElement('input');
    btnT.type = 'text';
    btnT.setAttribute("id", "Feld_" + ButtonCount);
    btnT.setAttribute("class", "EditTextInput")
    btnT.setAttribute("value", "neuer Button")
    document.getElementById("TextBereichGenerate").appendChild(btnT);



    var elem = document.getElementById("Feld_" + ButtonCount);
    elem.addEventListener("change", function() {
            TextAnpassen();
        },
        false);
}

//Daten aus Datenbank in CSS füllen 

var BackgroundColorData = "#" + "<?php echo ($SavedColors1[0]); ?>";
document.documentElement.style.setProperty('--kb-color-background', BackgroundColorData);
document.querySelector('#kb_selected_color_background').value = BackgroundColorData;

var ButtonColorData = "#" + "<?php echo ($SavedColors1[1]); ?>";
document.documentElement.style.setProperty('--kb-color-button', ButtonColorData);
document.querySelector('#kb_selected_color_button').value = ButtonColorData;

var HoverColorData = "#" + "<?php echo ($SavedColors1[2]); ?>";
document.documentElement.style.setProperty('--kb-color-hover', HoverColorData);
document.querySelector('#kb_selected_color_hover').value = HoverColorData;


//Daten an Server Senden
function VarSubmit(BackgroundColor, buttonInput, hoverInput) {

    let feldInput = "&feldInput=" + ButtonCount;

    for (Id = 1; Id <= ButtonCount; Id++) {

        var TextFeldNr = "#Feld_" + Id;
        var ButtonNr = "ButId" + Id;
        let Feld_value = (document.querySelector(TextFeldNr).value);

        feldInput = feldInput + "," + Feld_value;
    }

    window.location.href = "editor.php?backgroundInpute=" + BackgroundColor + "&buttonInput=" + buttonInput +
        "&hoverInput=" + hoverInput + feldInput;
}
</script>

</html>