


function insideBrowser() {
    // debugging
    //console.log("Script loaded");

    // store the faq answers here
    var faqAnswers = {
        questionOneClick: "Not at all! At Goblin Gizmos, we welcome collectors of all expertise levels. If you have something you collect, then congratulations! You're welcome on our website.",

        questionTwoClick: "Be sure to check out our specific list of categories to see more specifics on what others are posting. Otherwise, check out our Terms of Service (TOS) to see any items or themes that may be banned.",

        questionThreeClick: "You need an account to post your collections, but you are still able to view other posts without one. This is to ensure that you have the best experience possible, and can protect your collectibles.",

        questionFourClick: "We do not have a method where you can send or collect payments on Goblin Gizmos. To inquire about specific pieces, you need to contact and arrange details with the buyer/seller directly through any social media accounts or contacts they have listed.",

        questionFiveClick: "Unfortunately we cannot help you with monetary transactions that may occur outside of our website. Be sure to use your best judgement when scoping out posts that people make on here. Is there an image? Does the image look real? Does the price for the item seem accurate? Is this the only post on the account?",
    };

    // toggling the dropdown
    function toggleDropdown(event) {
        // debugging
        //console.log("Clicked:", event.currentTarget.id);

        var clickedId = event.currentTarget.id;
        var answerDivId = clickedId.replace("Click", "DOM");
        var answerDiv = document.getElementById(answerDivId);

        // debugging
        //console.log("Current content:", answerDiv.innerHTML);

        // closing the dropdown
        if (answerDiv.innerHTML.trim() !== "") {
            // debugging
            //console.log("Closing dropdown");
            answerDiv.innerHTML = "";
            return;
        }

        // debugging
        //console.log("Opening dropdown");

        // showing the answer
        var paragraph = document.createElement("p");
        paragraph.textContent = faqAnswers[clickedId];

        answerDiv.appendChild(paragraph);
    }

    // event listeners
    var questionIds = Object.keys(faqAnswers);

    for (var i = 0; i < questionIds.length; i++) {
        var element = document.getElementById(questionIds[i]);

        if (element) {
            element.addEventListener("click", toggleDropdown, false);
        }
    }
}

window.addEventListener("load", insideBrowser, false);