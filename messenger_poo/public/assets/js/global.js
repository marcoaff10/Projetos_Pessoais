// Tela de autenticação

//============================================================================================================
//Registro de usuário
$('#register').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
        type: 'post',
        url: '?ct=main&mt=register',
        data: $('#register').serialize(),
        beforeSend: function () {
            $('#loading').show();
        },
        complete: function () {
            $('#loading').hide();
        },
        success: function (data) {
            location.href = "?ct=main&mt=inbox";
        },
        error: function (error) {
            console.log(error);
            Swal.fire({
                title: 'Oops!',
                text: error.statusText,
                icon: 'error',
                confirmButtonText: 'Tentar novamente'
            })
        }
    });
});

//============================================================================================================
//Login
$('#login').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
        type: 'post',
        url: '?ct=main&mt=login',
        data: $('#login').serialize(),
        beforeSend: function () {
            $('#loading').show();
        },
        complete: function () {
            $('#loading').hide();
        },
        success: function () {
            location.href = "?ct=main&mt=inbox";
        },
        error: function (error) {
            console.log(error);
            Swal.fire({
                title: 'Oops!',
                text: error.statusText,
                icon: 'error',
                confirmButtonText: 'Tentar novamente'
            })
        }
    });
});



// Tela inbox
//============================================================================================================
function logout() {
    $.ajax({
        url: '?ct=main&mt=logout',
        beforeSend: () => {
            $("#loading").show();
        },
        success: () => {
            location.href = '?ct=main&mt=index'
        }
    });
}

//============================================================================================================
// pesquisar amigos
function search() {
    var term = $("input.searchField").val();
    if (term.length >= 3) {
        $.ajax({
            url: '?ct=main&mt=search&search=' + term,
            success: function (data) {
                $('#searchContainer').show();
                $('#searchContainer').html(data);
            }
        });
    } else {
        $('#searchContainer').hide();
    }
}

//============================================================================================================
function loadInbox()
{
    $.ajax({
        url: '?ct=main&mt=load_inbox',
        success: (data) => {
            $("#inbox .container").html(data);
        },
        error: (error) => {
            console.log(error);
            Swal.fire({
                title: 'Erro!',
                text: error.statusText,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
}

function LoadProfile(id = 0)
{
    $.ajax({
        url: '?ct=main&mt=load_profile&id=' + id,
        success: data => {
            $('#profile .container').html(data);
        },
        error: error => {
            console.log(error);
            Swal({
                title: 'Erro',
                text: error.statusText,
                icon: 'error',
                confirmButtonText: 'OK'
            });

        }
    });
}

//============================================================================================================
// Mudar foto do perfil
function previewUpload(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#userImg').attr('src', e.target.result);
            var formData = new FormData($("#uploadPic")[0]);
            $.ajax({
                type: 'post',
                url: '?ct=main&mt=update_profile',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                error: function (error) {
                    Swal.fire({
                        title: 'Imagem não alterada!',
                        text: error.statusText,
                        icon: 'error',
                        confirmButtonText: 'Tentar novamente'
                    })
                }
            });
        }
        reader.readAsDataURL(input.files[0]);
    }
}

//============================================================================================================
// Mostrar foto de perfil
$("#imgInp").change(function () {
    previewUpload(this);
});

//============================================================================================================
//Carregar conversa dos usuários 
function chat(id = 0) {
    $.ajax({
        url: '?ct=main&mt=chat&id=' + id,
        success: (data) => {
            $('#chat').html(data);
        },
        error: (error) => {
            console.log(error);
            Swal.fire({
                title: 'Erro',
                text: error.statusText,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
}

//============================================================================================================
// Recarregar página e atualizar parametros das funções 
$(document).ready(() => {
    setInterval(() => {
        loadInbox();
    }, 1000);
    loadProfile()
    chat();
});