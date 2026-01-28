
/*At the moment, I only need JS for small things.  This is DOM for the
FAQ section because we decided to do a drop down arrow*/


function firstQuestionSupport(){
    event.preventDefault();

//this is for the first support question

var divForText = document.getElementById("questionOneDOM");

while (divForText.hasChildNodes()){
divForText.removeChild(divForText.lastChild);
//makes sure it doesn't keep creating them
}
//now is the text part

var textOneAnchor = document.createElement ("p");

var theTextOne = document.createTextNode(
    "Not at all! At Goblin Gizmos, we welcome collectors of all expertise levels.  If you have something you collect, then congratulations! You're welcome on our website."

)

divForText.appendChild(textOneAnchor);



}



function insideBroswer(){


var questionOneClickListener = document.getElementById("questionOneClick");
questionOneClickListener.addEventListener ("click", firstQuestionSupport, false);











}



//make sure the DOM is loaded
window.addEventListener("load", insideBroswer, false);