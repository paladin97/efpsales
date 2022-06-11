<script>
 //   alert("entrando a mi js");


    let pusher = new Pusher($("#pusher_app_key").val(), {
        cluster: $("#pusher_cluster").val(),
        encrypted: true
    });

    var channel = pusher.subscribe('chat-' + $("#current_user").val());

    channel.bind('send', function(data) {


        if ($("#current_user").val() == 2) {

            var id_selected = $("li.selected").attr('id').split('-', 3);
            if (data.user_send == id_selected[2]) {
                $("ul.chat").append(
                    '<li><div class="row no-gutters"><div class="col-md-3"><div class="chat-bubble chat-bubble--left">' +
                    data.contens + '</div></div></div></li>'
                );
                $("ul.chat").animate({

                    scrollTop: $("ul.chat").prop("scrollHeight")

                }, 2000);

                $.post("/readMessage", {
                        send: $("#current_user").val(),
                        receive: data.user_send
                    },
                    function() {

                    });

            } else {

                $("#chast-li-" + data['user_send']).prependTo("#contact-comercial")
                $("#chast-li-" + data['user_send']).addClass("temblor")

                window.setTimeout(function() {
                    $("#chast-li-" + data['user_send']).removeClass("temblor")
                }, 3000);

                $("#chast-li-" + data['user_send'] + " span").text(Number($("#chast-li-" + data['user_send'] +
                    " span").text()) + 1)


            }



        } else {

            $("ul.chat").append(
                '<li><div class="row no-gutters"><div class="col-md-3"><div class="chat-bubble chat-bubble--left">' +
                data.contens + '</div></div></div></li>'


            );
            $("ul.chat").animate({

                scrollTop: $("ul.chat").prop("scrollHeight")

            }, 2000);

            $.post("/readMessage", {
                    send: $("#current_user").val(),
                    receive: null
                },
                function() {

                });

        }


    });

    $(document).ready(function() {

        if ($("#current_user").val() == 2) {
            var id_selected = $("li.selected").attr('id').split('-', 3);

            $.post("/readMessage", {
                    send: $("#current_user").val(),
                    receive: id_selected[2]
                },
                function() {

                });
        } else {
            $.post("/readMessage", {
                    send: $("#current_user").val(),
                    receive: null
                },
                function() {

                });
        }


        if ($("#not-read").length > 0) {

            window.setTimeout(function() {
                $("#not-read").fadeOut("slow");
            }, 3000 * 4);

            $("ul.chat").animate({

                scrollTop: $("#not-read").offset().top

            }, 2000);

        } else {

            $("ul.chat").animate({

                scrollTop: $("ul.chat").prop("scrollHeight")

            }, 2000);
        }

        $("li.selected span").text(0)

    });


    function getChat(send, receive, name_send, name_recieve) {


        if ($("#chast-li-" + receive).attr('class') != "selected") {
           // alert("Cargando");

            var formData = new FormData();
            formData.append('send', send);
            formData.append('receive', receive);

            $.ajax({
                url: "/loadchat",
                type: "POST",
                contentType: false,
                processData: false,
                dataType: "JSON",
                data: formData,

                success: function(json) {

                    $('.chat-bubble').hide('slow')

                    $("ul.chat>li").remove();

                    var x = true;

                    json.forEach(element => {

                        if (x == true && element['user_send_id'] != $("#current_user").val() &&
                            element['status_id'] == '1') {
                            $("ul.chat").append(
                                '<li id="li-not-read"><div id="not-read"><h6> mensajes no leidos </h6> </div></li>'
                            );
                            x = false;
                        }

                        if (send != element['user_send_id']) {
                            $("ul.chat").append(

                                '<li><div class="row no-gutters"><div class="col-md-3"><div class="chat-bubble chat-bubble--left">' +
                                element["contens"] + '</div> </div>  </div></li>'

                            );
                        } else {
                            $("ul.chat").append(
                                '<li><div class="row no-gutters"><div class="col-md-3 offset-md-9"><div class="chat-bubble chat-bubble--right">' +
                                element["contens"] + '</div> </div>  </div></li>'
                            );
                        }

                    });

                    $('.chat-bubble').show('slow');

                    if ($("#not-read").length > 0) {

                        $("ul.chat").animate({

                            scrollTop: $("#not-read").offset().top

                        }, 2000);

                        window.setTimeout(function() {
                            $("#not-read").fadeOut("slow");
                        }, 3000 * 2);

                    } else {
                        $("ul.chat").animate({

                            scrollTop: $("ul.chat").prop("scrollHeight")

                        }, 2000);
                    }


                    $(".selected").removeClass("selected");

                    $("#chast-li-" + receive + " div").addClass("selected");
                    $("#chast-li-" + receive).addClass("selected");

                    $("#name-user-receive").text(name_recieve)

                    console.log(json);

                    $("li.selected span").text(0)


                },

                error: function(jqXHR, status, error) {
                    console.log(error);
                    console.warn(jqXHR.responseText);
                }
            });
        }
    }

    function sendMenssages(user_id, user_name) {

        var content = $("#btn-input").val();
        var formData = new FormData();
        formData.append('contens', content);


        if (content != undefined) {
            if (user_id == 2) {

                formData.append('user_send_id', $("#current_user").val());

                receive_id = $("li.selected").attr('id').split('-', 3);

                formData.append('user_receive_id', receive_id[2]) // Cambiar estoo codigo feo

            } else {
                formData.append('user_send_id', user_id);

                formData.append('user_receive_id', 2)

            }

            $.ajax({
                url: "/sendmenssages",
                type: "POST",
                contentType: false,
                processData: false,
                dataType: "JSON",
                data: formData,

                success: function(json) {

                    if (json['res'] == true) {
                        $("ul.chat").append(
                            '<li><div class="row no-gutters"><div class="col-md-3 offset-md-9"><div class="chat-bubble chat-bubble--right">' +
                            content + '</div> </div>  </div></li>'

                        );
                        $("ul.chat").animate({

                            scrollTop: $("ul.chat").prop("scrollHeight")

                        }, 2000);
                        $("#btn-input").val("")

                        if (user_id == 2) {

                            $("li.selected").prependTo("#contact-comercial").show('slow');

                        }


                    } else {
                        alert("No se ha podido enviar su mensaje");

                        //En caso que haya habido un error en la respuesta 
                    }

                },

                error: function(jqXHR, status, error) {
                    console.log(error);
                    console.warn(jqXHR.responseText);
                    alert("Error de coneccion intente enviar nuevamente su mensaje");

                    // Problema de conexion o con la solicitud ajax

                }
            });
        }
        // Si es el admin 

    }
</script>
