import './styles/espace.scss';
import './bootstrap';

$(".profile").addClass("pre-enter");
setTimeout(function(){
	$(".profile").addClass("on-enter");
}, 500);
setTimeout(function(){
	$(".profile").removeClass("pre-enter on-enter");
}, 3000);