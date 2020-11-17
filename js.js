$(document).ready(function () {
    $('#subForm').click(function f() {
        let name = $('#name').val()
        let tel = $('#tel').val()
        let email = $('#email').val()
        $.ajax({
            type: "POST",
            url: "send.php",
            data: {
                name: name,
                tel: tel,
                email: email,
            },
            complete: function (xhr) {
                switch (xhr.responseText) {
                    case "DB_Exception":
                        $('.main__info_hide').attr("class", "main__info_show alert alert-danger")
                        $('.main__info__msg').text($('#name').val() + ", " + "произошел сбой. Отправка данных повторится в фоновом режиме через несколько секунд, не закрывайте страницу!")
                        setTimeout(() => {
                            $('.main__info__msg').text("")
                            $('.main__info_show').attr("class", "main__info_hide")
                        }, 4000)
                        setTimeout(f, 10000)
                        break
                    case "order exist":
                        $('.main__info_hide').attr("class", "main__info_show alert alert-success")
                        $('.main__info__msg').text($('#name').val() + ", " + "заявка уже есть в БД. Специалист свяжется с Вами в ближайшее время. Новую заявку Вы можете отправить завтра.")
                        setTimeout(() => {
                            $('.main__info__msg').text("")
                            $('.main__info_show').attr("class", "main__info_hide")
                            $('#name').val("")
                            $('#tel').val("")
                            $('#email').val("")
                        }, 4000)
                        break
                    case "emptyFields":
                        if( $('#name').val() != "" ) {
                            $('.main__info_hide').attr("class", "main__info_show alert alert-warning")
                            $('.main__info__msg').text($('#name').val() + ", " + "заполните, все поля.")
                        } else {
                            $('.main__info_hide').attr("class", "main__info_show alert alert-warning")
                            $('.main__info__msg').text("Заполните, все поля.")
                        }

                        setTimeout(() => {
                            $('.main__info__msg').text("")
                            $('.main__info_show').attr("class", "main__info_hide")
                        }, 4000)
                        break
                    case "ready":
                        $('.main__info_hide').attr("class", "main__info_show alert alert-success")
                        $('.main__info__msg').text($('#name').val() + ", " + "заявка отправлена!")
                        setTimeout(() => {
                            $('.main__info__msg').text("")
                            $('.main__info_show').attr("class", "main__info_hide")
                            $('#name').val("")
                            $('#tel').val("")
                            $('#email').val("")
                        }, 4000)
                        break
                    case "Email is email":
                        $('.main__info_hide').attr("class", "main__info_show alert alert-warning")
                        $('.main__info__msg').text($('#name').val() + ", " + "Email должен быть в формате name@domain.ru")
                        setTimeout(() => {
                            $('.main__info__msg').text("")
                            $('.main__info_show').attr("class", "main__info_hide")
                        }, 4000)
                        break
                    case "Name is cyrillic":
                        $('.main__info_hide').attr("class", "main__info_show")
                        $('.main__info__msg').text($('#name').val() + ", " + "Имя должен быть написано кириллицей или латиницей")
                        setTimeout(() => {
                            $('.main__info__msg').text("")
                            $('.main__info_show').attr("class", "main__info_hide")
                        }, 4000)
                        break
                    default:
                        console.log(xhr.responseText)
                        if ($('#name').val() != "") {
                            $('.main__info_hide').attr("class", "main__info_show")
                            $('.main__info__msg').text($('#name').val() + ", " + "что-то пошло не так. Мы работаем над этим.")
                        } else  {
                            $('.main__info_hide').attr("class", "main__info_show")
                            $('.main__info__msg').text("Что-то пошло не так. Мы работаем над этим.")
                        }

                        setTimeout(() => {
                            $('.main__info__msg').text("")
                            $('.main__info_show').attr("class", "main__info_hide")
                        }, 4000)
                        break
                }
            }
        })
    })
})
