$(function () {
      $(document).ajaxStart(function(){
        $("#wait").css("display", "block");
    });
        $(document).ajaxComplete(function(){
        $("#wait").css("display", "none");
    });
    $('#confirm_button').click(function () {
        $('#confirm_button').prop('disabled', true);
        $.ajax({
            method: "POST"
            , url: "controllers/Config_bdd.php"
            , data: {
                host: $('#host').val()
                , username: $('#username').val()
                , password: $('#password').val()
            }
        }).done(function (msg) {
            if (msg !== 'err101') {
                window.location.replace('vue/vue_main_page.php');
                return;
            }
            $('#confirm_button').prop('disabled', false);
            alert('les identifiants entrés sont incorrects');
        });
    });
    $('#logoff_icon').click(function () {});
});