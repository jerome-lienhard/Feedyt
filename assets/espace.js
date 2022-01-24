import './styles/espace.scss';
import './bootstrap';

var dropdown = document.getElementsByClassName("dropdown-btn");
var i;

for (i = 0; i < dropdown.length; i++) {
    dropdown[i].addEventListener("click", function() {
        this.classList.toggle("active");
        var dropdownContent = this.nextElementSibling;
        if (dropdownContent.style.display === "block") {
            dropdownContent.style.display = "none";
        } else {
            dropdownContent.style.display = "block";
        }
    });
}

/* Set the width of the sidebar to 250px (show it) */
let btn = document.querySelector("#btn-nav");
let liOuv = document.querySelector("#li-ouv");
let nav = document.querySelector(".sidepanel");
let menuTop = document.querySelector('#menu_top');
let btnOpen = document.querySelector("#btn-nav-open");
console.log(menuTop);
btn.addEventListener('click', () => {
    if (nav.style.width = "280px") {
        nav.style.width = "0px";
        liOuv.style.display = "block";
        btn.style.display = "none";
        btnOpen.style.display = "block";
        menuTop.style.marginLeft = 0;


    }
})

btnOpen.addEventListener('click', () => {
    if (nav.style.width = "0px") {
        nav.style.width = "280px";
        liOuv.style.display = "none";
        btn.style.display = "block";
        btnOpen.style.display = "none";
        menuTop.style.marginLeft = "280px";
    }
})