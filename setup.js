//set up the action listeners
window.onload = function () {
        mydate=new Date('2011-04-11');
        nowdate= Date.now();
        
        prepareActionListeners();

}
function prepareActionListeners() {
//        document.getElementById('mypass').addEventListener('blur',showChoices);
//        document.getElementById('mypass').addEventListener('onkeypress',checkKey);
}
function checkKey(event) {
        var x = event.keyCode;
    if (x == 13) { 
        showChoices();
    }
}

function showChoices() {
        document.getElementById("whatdoing").style.display = "block";
        document.body.style.backgroundColor="orange";
}

