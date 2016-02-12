function updateVisiblity(url, title, element){
    post(url, {name: title, visible: element.checked, type: "visible"});
}

function updateOnOdua(url, title, element){
    post(url, {name: title, is_on_odua: element.checked, type: "is_on_odua"});
}

function updatedFeatured(url, title, element){
    post(url, {name: title, featured: element.checked, type: "featured"});
}

function deletePost(url, title, element){
    var r = window.confirm("Are you sure you want to delete "+title);
    if(r==true) {
        post(url, {name: title, type:"remove"});
    }else{
        element.checked = false;
    }
}

function post(path, params, method) {
    method = method || "post"; // Set method to post by default if not specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    for(var key in params) {
        if(params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
         }
    }

    document.body.appendChild(form);
    form.submit();
}

/*
    ODUA EDITOR JS
*/
function deleteImage(elem, update_db){
    update_db = update_db || false;
    image_name = elem.parentElement.getAttribute('id');
    elem.parentElement.remove(); //removes slideshow_row;
    if(update_db){ //if true we POST delete image message to server
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function()
          {
          if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
            }
          }
        xmlhttp.open("POST","odua_post.php",true);
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xmlhttp.send("image=".concat(image_name,"&type=delete_image"));
    }
}

function addImageRow(elem){
    elm = document.getElementById("slideshow_row_placeholder"); parent = elm.parentElement;
    tempPlaceHolder = document.createElement('div');
    tempPlaceHolder.id = 'slideshow_row_placeholder';
    elm.insertAdjacentElement('afterend', tempPlaceHolder);
    tempDiv = document.createElement('div');
    tempDiv.innerHTML += "<div class='slideshow_row'><input type='file' name='slideshow_1'/> <button type='button' onclick='deleteImage(this)'>Delete</button></div>";
    input = tempDiv.childNodes[0];
    parent.replaceChild(input, elm);
}