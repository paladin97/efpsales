<script>
    function limits(obj, limit) {
        var cnt = $("#counter > span");
        var txt = $(obj).val(); 
        var len = txt.length;
            
        // check if the current length is over the limit
        if(len > limit){
            $(obj).val(txt.substr(0,limit));
            $(cnt).html(len-1);
        } 
        else { 
            $(cnt).html(len);
        }
            
        // check if user has less than 20 chars left
        // console.log(limit-len);
        if(limit-len <= 5) {
            $(cnt).removeClass();
            $(cnt).addClass("text-red");
            $(cnt).css({"font-weight": "bold"});
        }
        else if(limit-len <= 20){
            $(cnt).removeClass();
            $(cnt).addClass("text-orange");
            $(cnt).css({"font-weight": "normal"});
        }
        else{
            $(cnt).removeClass();
            $(cnt).addClass("text-blue");
            $(cnt).css({"font-weight": "normal"});
        }

    }
    var limitnum = 1000;
        //Para controlar el limite del SMS
    $('#summernoteWhatsapp').keyup(function(){
        limits($(this), limitnum);
    });

    $('body').on('click', '.sendWhatsappTemplates', function () {
        // $('#summernoteWhatsapp').summernote({
        //     placeholder: 'Ingrese el mensaje',
        //     tabsize: 2,
        //     height: 350
        // });
        $('#wa_message').val('');
        $('#whatsapp_message_phone').val($(this).data('phone'));
        $("#counter > span").removeClass();
        $("#counter > span").addClass('text-blue');
        $("#counter > span").html(0);
        $('#whatsapp_template_type_id').val([]).trigger("change");
        $('#summernoteWhatsapp').val('');
        $('#whatsAppTemplateModal').modal('show');
    });

    //Aqui debemos cargar el select2 con los datos de Leads
    @foreach(App\Models\WhatsappTemplate::where('template_type_id',1)->orderBy('name','ASC')->get() as $cData) //Leads
        var data = {
            id: {{$cData->id}},
            text: '{{$cData->name}}'
        };
        var newOption = new Option(data.text, data.id, false, false);
        $('#whatsapp_template_type_id').append(newOption).trigger('change');
    @endforeach

    //Para mostrar la plantilla
    $('#whatsapp_template_type_id').on('select2:select', function (e) {
        var whatsapp_template_id = $(this).val();
        $.get("{{route('whatsappcrud.index') }}" +'/' + whatsapp_template_id +'/edit', function (data) {
            // console.log(data);
            var message_size = data.template.length;
            var message_limit = $("#counter > span");
            if(limitnum-message_size <= 20) {
                $(message_limit).removeClass();
                $(message_limit).addClass("text-orange");
                $(message_limit).css({"font-weight": "normal"});
            }
            else if(limitnum-message_size == 0){
                $(message_limit).removeClass();
                $(message_limit).addClass("text-red");
                $(message_limit).css({"font-weight": "bold"});
            }
            else{
                $(message_limit).removeClass();
                $(message_limit).addClass("text-blue");
                $(message_limit).css({"font-weight": "normal"});
            }
            $(message_limit).html(message_size);
            $('#summernoteWhatsapp').val(data.template);
        });
    });
    //Cuando envia la plantilla
    $('#saveBtnWhatsapp').click(function (e) {
        var previewMessage = $("#summernoteWhatsapp").val();
        if (!$("#summernoteWhatsapp").val()) {
            toastr.error('Debe escribir un mensaje', '', {timeOut: 3000,positionClass: "toast-top-center"});
        }
        else{
            var phone = $('#whatsapp_message_phone').val();
            var messageToSend = previewMessage.replace(/(?:\r\n|\r|\n)/g,'%0A%0A');
            console.log(messageToSend);
            var urlWhats ='https://wa.me/'+phone+'?text='+messageToSend;
            window.open(urlWhats, '_blank');
        }
    });

</script>