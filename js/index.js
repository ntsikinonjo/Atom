/**
 * functions running continuously
 */
$(window).on('resize', function() {

    if ($(window).width() < 460) {
        // set word collaboration to collab
        $('#jumbotron-heading').html('teach, learn, collabo');
    }
    else {
        // set word collaboration to collab
        $('#jumbotron-heading').html('teach, learn, collaborate');
    }

});

/**
 * function runs immediately
 */
(function() {

    // checks if we're on mobile
    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {

        // set word collaboration to collab
        $('#jumbotron-heading').html('teach, learn, collabo');

    }
    else {
        if ($(window).width() < 460) {
            // set word collaboration to collab
            $('#jumbotron-heading').html('teach, learn, collabo');
        }
    }

})();
$(document).ready(function() {

    // set title
    Welcome();

});

/**
 * cosmetics
 */
$('body,html').css('overflow-x','hidden')

/**
 * functions
 */
function Welcome() {

    $.ajax({
        url : 'api/signing/signed.php', // url
        method : 'POST', // method
        dataType : 'JSON',
        success : function(data) {
            
            if (data.message === 'success') {
                // redirect to dashboard
                window.location.href = 'dashboard/index.html'
            }
        }
    });

}

function SignIn(username, password) {

    var username = $('#username').val();
    var password = $('#password').val();

    $.ajax({
        url : 'api/signing/login.php', // url
        method : 'POST', // method
        data:{username : username, password : password},
        dataType : 'JSON',
        success : function(data) {
            
            if (data.message === 'success') {
                // redirect to dashboard
                window.location.href = 'dashboard/index.html'
            }
            else if (data.message === 'error') {
                ResponseModal('Incorrect username or password, please try again')
            }
        },
        error : function(xhr, data, errorThrown) {
            console.log(xhr)
            console.log(data)
            console.log(errorThrown);
            ResponseModal(errorThrown)
        }
    });
}

/**
 * events
 */
// sign in
$('#sign-in-button').click(function() {
    SignIn()
});
document.addEventListener('keyup', function(event) {
    event.preventDefault()
    if (event.keyCode === 13) {
        SignIn()
    }
})