let current = 0;
let MIN = 0;
let MAX = 0;

$(document).ready(init);

/**
 * Setup initial state on load
 */
function init() {
    MAX = document.getElementById("hidden-input").getAttribute("value");
    document.getElementById("questions").getElementsByTagName("li")[current].style.backgroundColor = "yellow";
    document.onkeydown = checkKey;
}

/**
 * back():
 *  This is used for going back through a question set.
 */
function back() {
    if (current > MIN) {
        let targetLi = document.getElementById("questions").getElementsByTagName("li")[current];
        targetLi.style.backgroundColor = "white";
        current--;
        targetLi = document.getElementById("questions").getElementsByTagName("li")[current];
        targetLi.style.backgroundColor = "yellow";
        document.getElementById("back-btn").setAttribute("href", "#myInput" + current)
    }
    window.location.href = "#myInput" + current;
}

/**
 * selectLi():
 *  This is used when a user selects on a specfic question
 */
function selectLi(el) {
    console.log(current)
    let targetLi = document.getElementById("questions").getElementsByTagName("li")[current];
    targetLi.style.backgroundColor = "white";
    current = el;
    targetLi = document.getElementById("questions").getElementsByTagName("li")[current];
    targetLi.style.backgroundColor = "yellow";
}

/**
 * next():
 *  This is used for to go forward through the question set
 */
function next() {
    if (current < MAX - 1) {
        targetLi = document.getElementById("questions").getElementsByTagName("li")[current];
        targetLi.style.backgroundColor = "white";
        current++;
        var targetLi = document.getElementById("questions").getElementsByTagName("li")[current];
        targetLi.style.backgroundColor = "yellow";
        document.getElementById("next-btn").setAttribute("href", "#myInput" + current)
    }
    window.location.href = "#myInput" + current;
}

function copy(copyText) {
    copyText.select();
    copyText.setSelectionRange(0, 99999)
    document.execCommand("copy");

    var tooltip = document.getElementById("alert");
    tooltip.style.visibility = "visible";
    tooltip.innerHTML = "Copied: " + copyText.value;

}

function oldText() {
    var tooltip = document.getElementById("alert");
    tooltip.innerHTML = "Copy to clipboard";
    tooltip.style.visibility = "hidden";
}

function random() {
    targetLi = document.getElementById("questions").getElementsByTagName("li")[current];
    targetLi.style.backgroundColor = "white";
    var randomise = true;
    while (randomise) {
        var temp_current = current;
        current = Math.floor(Math.random() * ((MAX - 1) - MIN) + MIN);
        if (temp_current != current) {
            randomise = false;
        }
    }
    var targetLi = document.getElementById("questions").getElementsByTagName("li")[current];
    targetLi.style.backgroundColor = "yellow";
    document.getElementById("random-btn").setAttribute("href", "#myInput" + current)
}

function checkKey(e) {

    e = e || window.event;

    if (e.keyCode == '38') {
        // up arrow
        back()

    } else if (e.keyCode == '40') {
        // down arrow
        next()
    } else if (e.keyCode == '37') {
        // left arrow
        back()
    } else if (e.keyCode == '39') {
        // right arrow
        next()
    }
}
