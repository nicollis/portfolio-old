function flippy(elem, b_elem) {
    y = document.getElementById(elem);
    z = document.getElementById(b_elem);
    
    //ny=ny+1
    y.style.transition="1s"
    y.style.webkitTransition="1000ms";
    var ny=180;
    y.style.transform="rotateY(" + ny + "deg)"
    y.style.webkitTransform="rotateY(" + ny + "deg)"
    y.style.OTransform="rotateY(" + ny + "deg)"
    y.style.MozTransform="rotateY(" + ny + "deg)"
    z.style.transition="1s"
    z.style.webkitTransition="1000ms";
    ny=360;
    z.style.transform="rotateY(" + ny + "deg)"
    z.style.webkitTransform="rotateY(" + ny + "deg)"
    z.style.OTransform="rotateY(" + ny + "deg)"
    z.style.MozTransform="rotateY(" + ny + "deg)"
}

function resetRotateY(elem, counter){
    elem.style.transition=""+counter+"ms"
    elem.style.webkitTransition=""+counter+"ms";
    elem.style.transform="rotateY(0deg)"
    elem.style.webkitTransform="rotateY(0deg)"
    elem.style.OTransform="rotateY(0deg)"
    elem.style.MozTransform="rotateY(0deg)"
    elem.addEventListener( 'webkitTransitionEnd', 
    function() { this.style.webkitTransition="0ms"; }, false );
    elem.addEventListener( 'transitionEnd', 
    function() { this.style.transition="0ms"; }, false );
}