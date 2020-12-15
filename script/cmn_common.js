function cmn_gen_pwd() {
 // Initiated by Louis, on http://www.abricocotier.fr/12018-un-generateur-de-mots-de-passe-en-javascript
    if (parseInt(navigator.appVersion) <= 3) {
        alert("Sorry this only works in 4.0+ browsers");
        return true;
    }
 
    var length=10;
    var sPassword = "";
    //length = document.aForm.charLen.options[document.aForm.charLen.selectedIndex].value; 
    var lowercase = 1;
    var uppercase = 1; //(document.aForm.uppercase.checked);
    var figures = 1; //(document.aForm.figures.checked);
    var punction = 1; // (document.aForm.punctuation.checked);            
 
    for (i=1; i <= length; i++) { 
        numI = getRandomNum();
        if ((punction == 0 && checkPunc(numI)) || (figures == 0 && checkFigures(numI)) ||(uppercase == 0 && checkUppercase(numI))) {i -= 1;}
        else {sPassword = sPassword + String.fromCharCode(numI);}
    } 
    //document.aForm.passField.value = sPassword 
    return true;
}
 
function getRandomNum() {
 // Initiated by Louis, on http://www.abricocotier.fr/12018-un-generateur-de-mots-de-passe-en-javascript
    // between 0 - 1
    var rndNum = Math.random()
    // rndNum from 0 - 1000
    rndNum = parseInt(rndNum * 1000);
    // rndNum from 33 - 127
    rndNum = (rndNum % 94) + 33;
    return rndNum;
}
 
function checkPunc(num) {
 // Initiated by Louis, on http://www.abricocotier.fr/12018-un-generateur-de-mots-de-passe-en-javascript
    if (((num >=33) && (num <=47)) || ((num >=58) && (num <=64))) { return true; }
    if (((num >=91) && (num <=96)) || ((num >=123) && (num <=126))) { return true; }
    return false;
}
 
function checkFigures(num) {
 // Initiated by Louis, on http://www.abricocotier.fr/12018-un-generateur-de-mots-de-passe-en-javascript
    if ((num >=48) && (num <=57)) { return true; }
    else { return false; }
}
 
function checkUppercase(num) {
 // Initiated by Louis, on http://www.abricocotier.fr/12018-un-generateur-de-mots-de-passe-en-javascript
    if ((num >=65) && (num <=90)) { return true; }
    else { return false; }
}