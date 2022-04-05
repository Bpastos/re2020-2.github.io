$( document ).ready(function() {
    var location = window.location.pathname;
    console.log(location);
    if(location == '/'){
        $('.homeLink').after('<div class="activeLigne"></div>');
    }
    else{
        $('nav a[href^="/' + window.location.pathname.split("/")[1] + '"]').after('<div class="activeLigne"></div>');
    }
});