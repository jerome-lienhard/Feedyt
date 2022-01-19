import './styles/espace.scss';
import './bootstrap';

// const indicator = document.querySelector('.nav-indicator');
// const items = document.querySelectorAll('.nav-item');

// function handleIndicator(el) {
//     items.forEach(item => {
//         item.classList.remove('is-active');
//         item.removeAttribute('style');
//     });

//     indicator.style.width = `${el.offsetWidth}px`;
//     indicator.style.left = `${el.offsetLeft}px`;
//     indicator.style.backgroundColor = el.getAttribute('active-color');

//     el.classList.add('is-active');
//     el.style.color = el.getAttribute('active-color');
// }


// items.forEach((item, index) => {
//     item.addEventListener('click', (e) => { handleIndicator(e.target) });
//     item.classList.contains('is-active') && handleIndicator(item);
// });
//* Loop through all dropdown buttons to toggle between hiding and showing its dropdown content - This allows the user to have multiple dropdowns without any conflict */
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