import './styles/espace.scss';
import './bootstrap';

// ********************************************************
//                 Dropdown menu de la sidbar
// ***************************************************************
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

// ************************************************************
//              Hide and show de la sidebar
// ***************************************************************
let btn = document.querySelector("#btn-nav");
let liOuv = document.querySelector("#li-ouv");
let nav = document.querySelector(".sidepanel");
let menuTop = document.querySelector('#menu_top');
let btnOpen = document.querySelector("#btn-nav-open");
let logo = document.querySelector("#logo2");
console.log(menuTop);
btn.addEventListener('click', () => {
    if (nav.style.width = "280px") {
        nav.style.width = "0px";
        liOuv.style.display = "block";
        btn.style.display = "none";
        logo.style.opacity = "1";
        btnOpen.style.display = "block";
        menuTop.style.marginLeft = "0px";


    }
});

btnOpen.addEventListener('click', () => {
    if (nav.style.width = "0px") {
        nav.style.width = "280px";
        liOuv.style.display = "none";
        btn.style.display = "block";
        logo.style.opacity = "0";
        btnOpen.style.display = "none";
        menuTop.style.marginLeft = "280px";
    }
});

// *************************************************************
//                      Theme sombre
// ***************************************************************
let btnSombre = document.querySelector('#theme');
let sidbar = document.querySelector('#mySidepanel');
let dropdownBtn = document.querySelectorAll('.dropdown-btn');
let styleHover = document.querySelectorAll('.style_hover');
let dpContainer = document.querySelector('.dropdown-container');
let contain = document.querySelector('#contain');
let card = document.querySelectorAll('.card-body');
let body = document.querySelector('body');
let navBar = document.querySelector('.navbar');
let date = document.querySelectorAll('.date');
let textColor = document.querySelectorAll('.text-white');
let footer = document.querySelector('footer');

// ***************************************************************
//Fonction pour passer en theme sombre
function setBgDark() {
    sidbar.className = "sidepanel bg-dark";
    contain.className = "container bg-dark text-light";
    body.className = "bg-dark";



    navBar.className = "navbar navbar-expand navbar-dark fixed-top bg-dark menu_top skiplinks";
    dropdownBtn.forEach(styl => {
        styl.style.color = "white";
    });
    textColor.forEach(styl => {
        styl.className = "text-white";
    });
    date.forEach(styl => {
        styl.className = "ps-3 date bg-dark ";
    });
    styleHover.forEach(styl => {
        styl.style.color = "white";
    });
    card.forEach(styl => {
        styl.className = "card-body bg-dark";
        styl.style.color = "rgb(248, 249, 250)";
    });
    dpContainer.style.backgroundColor = "black";
    footer.className = "bg-dark text-white";

}

// ***************************************************************
// Fonction pour rétablir le theme
function updateBg() {
    sidbar.className = "sidepanel bg-light";
    contain.className = "container bg-white text-dark";
    body.className = "bg-white";

    navBar.className = "navbar navbar-expand navbar-light fixed-top bg-light menu_top skiplinks";
    textColor.forEach(styl => {
        styl.className = "text-dark";
    });
    dropdownBtn.forEach(styl => {
        styl.style.color = "rgba(0, 0, 0, 0.685)";
    });
    date.forEach(styl => {
        styl.className = "ps-3 date bg-light ";
    });
    styleHover.forEach(styl => {
        styl.style.color = "rgba(0, 0, 0, 0.685)";
    });
    card.forEach(styl => {
        styl.className = "card-body bg-white";
        styl.style.color = "black";
    });
    dpContainer.style.backgroundColor = "white";
    footer.className = "bg-light text-dark";
}

// ***************************************************************
// Bouton pour passer du theme sombre au normal
btnSombre.addEventListener('click', () => {

    if (localStorage.getItem('theme') == '') {
        setBgDark();
        localStorage.setItem('theme', 'dark');;
    } else {
        updateBg();
        localStorage.setItem('theme', '');
    }
});

// ***************************************************************
// Vérfication du local storage
if (localStorage.getItem('theme') == 'dark') {
    setBgDark();
} else {
    updateBg();
}