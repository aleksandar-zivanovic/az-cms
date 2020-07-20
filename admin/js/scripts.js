$(document).ready(function(){

    // EDITOR CKEDITOR
    ClassicEditor
        .create( document.querySelector( '#body' ) )
        .catch( error => {
        console.error( error );
    } );
    // REST OF THE CODE

    $('#selectAllBoxes').click(function(event){
        if(this.checked){
            $('.checkBoxes').each(function(){
                this.checked = true;
            });
        } else {
            $('.checkBoxes').each(function(){
                this.checked = false;
            });
        }
    });
    
    // JQuery kod za Admin Loader
    var div_box = "<div id='load-screen'><div id='loading'></div></div>";
    $("body").prepend(div_box);
    
    $('#load-screen').delay(200).fadeOut(200, function(){
        $(this).remove();
    })
    
    
} );



// funkcija za LIVE broj korisnika u admin panelu
function loadUsersOnline(){
    $.get("functions.php?onlineusers=result", function(data){
        $(".usersonline").text(data);
    });
}




