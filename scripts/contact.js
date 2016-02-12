var strengths_icon = document.getElementById('strengths_icon_id');

strengths_icon.addEventListener('webkitAnimationEnd', function(){
    strengths_icon.style.webkitAnimation = ''
}, false);
strengths_icon.addEventListener('animationend', function(){
    strengths_icon.style.animation = '';
}, false);

document.getElementById('strengths_hex').onmouseover = function(){
    strengths_icon.style.animation = '1s ease 0s normal none 1 SHAKEY';
    strengths_icon.style.webkitAnimation = 'SHAKEY 1s 1 1';
};

var large_gear_icon = document.getElementById('large_gear');
var gear_animation_running = true;

large_gear_icon.addEventListener('webkitAnimationEnd', function(){
    large_gear_icon.style.webkitAnimation = '';
}, false);
large_gear_icon.addEventListener('animationend', function(){
    large_gear_icon.style.animation = '';
}, false);

var small_gear_icon = document.getElementById('small_gear');
small_gear_icon.addEventListener('webkitAnimationEnd', function(){
    small_gear_icon.style.webkitAnimation = '';
    gear_animation_running = false;
}, false);
small_gear_icon.addEventListener('animationend', function(){
    small_gear_icon.style.animation = '';
    gear_animation_running = false;
}, false);

document.getElementById('skills_hex').onmouseover = function(){
    if(!gear_animation_running){
        small_gear_icon.style.animation = "2s ease 1ms normal none 1 ROllREVERSE";
        large_gear_icon.style.animation = "2s ease 1ms normal none 1 ROll";
        small_gear_icon.style.webkitAnimation = "ROllREVERSE 2s 1ms 1";
        large_gear_icon.style.webkitAnimation = "ROll 2s 1ms 1";
    }
};

