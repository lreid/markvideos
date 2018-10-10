//set up the action listeners
window.onload = function () {
        mydate=new Date('2011-04-11');
        nowdate= Date.now();
        
        if (document.getElementById("peer").checked) {
            document.getElementById("peereval").style.display = "block";
        } else {
            document.getElementById("peereval").style.display = "none";
        }

        if (document.getElementById("video").checked) {
            document.getElementById("markvideo").style.display = "block";
        } else {
            document.getElementById("markvideo").style.display = "none";
        }


        document.getElementById("finalmark").style.display = "none";
        if (mydate>nowdate) {
//            alert ("now");
            document.getElementById("video").disabled = false;
            document.getElementById("peer").disabled = false;
            document.getElementById("seemark").disabled = true;

        } else {
            document.getElementById("peereval").style.display = "none";
            document.getElementById("markvideo").style.display = "none";
            document.getElementById("finalmark").style.display = "block";
            document.getElementById("video").disabled = true;
            document.getElementById("peer").disabled = true;
            document.getElementById("seemark").disabled = false;
            document.getElementById("seemark").checked = true;
            
        }
        prepareActionListeners();

}
function prepareActionListeners() {
//        document.getElementById('mypass').addEventListener('blur',showChoices);
//        document.getElementById('mypass').addEventListener('onkeypress',checkKey);
          document.getElementById('peereval').addEventListener('change',displayPeer);
          document.getElementById('pickagroup').addEventListener('change',displayVideo);
          document.getElementById('pickagroup').addEventListener('click',displayVideo);
          addSomeListeners("creative");
          addSomeListeners("examples");
          addSomeListeners("relevance");
          addSomeListeners("clearly");
}
function checkKey(event) {
        var x = event.keyCode;
    if (x == 13) { 
        showChoices();
    }
}

function showVideoMarking() {
        document.getElementById("markvideo").style.display = "block";
        document.getElementById("peereval").style.display = "none";
        document.body.style.backgroundColor="yellow";
}
function showPeerEval() {
        document.getElementById("markvideo").style.display = "none";
        document.getElementById("peereval").style.display = "block";
        document.body.style.backgroundColor="eggshell";
}

function displayPeer() {
      var x = document.getElementById("pickpeer").options[document.getElementById("pickpeer").selectedIndex];
      document.getElementById("studentpeer").innerHTML = x.innerHTML; 
      document.getElementById("submitPeerEval").disabled = false;
}

function addSomeListeners(whichRadioButton) {
  var allButtons = document.getElementsByName(whichRadioButton);
  for (var i=0; i<allButtons.length; i++) {
      allButtons[i].addEventListener("click",turnOnSubmit);
  }
}

function checkOneSetRadio (whichRadioButton) {
  var allButtons = document.getElementsByName(whichRadioButton);
  var oneButtonOn=false;
  for (var i=0; i<allButtons.length; i++) {
      if (allButtons[i].checked) {
          oneButtonOn = true;
      }
   }
  return oneButtonOn;
}

function turnOnSubmit() {
  if ((checkOneSetRadio("creative")) && (checkOneSetRadio("examples")) && (checkOneSetRadio("clearly")) && (checkOneSetRadio("relevance"))) {
      document.getElementById("submitGroupEval").disabled = false;
  } else {
      document.getElementById("submitGroupEval").disabled = true;

  }

}

function turnOffRadio() {
  for (var i=0; i<5; i++) {
     document.getElementsByName("creative")[i].checked =false;
     document.getElementsByName("examples")[i].checked =false;
     document.getElementsByName("relevance")[i].checked =false;
     document.getElementsByName("clearly")[i].checked =false;
  }
}

function displayVideo() {
    var x = document.getElementById("pickagroup").options[document.getElementById("pickagroup").selectedIndex];
    var video = document.getElementById("setTheVideo");
    var whichbutton;
    document.getElementById("videotopic").innerHTML = x.dataset.topic;
    document.getElementById("videourlhref").href = x.dataset.url;
    document.getElementById("videourlhref").innerHTML = x.dataset.url;
    turnOffRadio();
    if (x.dataset.fav == 1) {
       document.getElementById("favouritevideo").checked = true;
    } else {
       document.getElementById("favouritevideo").checked = false;
    }
    if (x.dataset.creative != "") {
    	whichbutton="c" + x.dataset.creative;
        document.getElementById(whichbutton).checked = true;
        whichbutton = "e" + x.dataset.example;
        document.getElementById(whichbutton).checked = true;
        whichbutton = "r" + x.dataset.rel;
        document.getElementById(whichbutton).checked = true;
    	whichbutton = "cc" + x.dataset.clear;
	document.getElementById(whichbutton).checked = true;
    }
    video.src = x.dataset.url.replace("watch?v=","embed/") + "?autoplay=1";
    
}


