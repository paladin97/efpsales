
<script>
    $(document).ready( function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(".wrapper").show(); 
    });
    var formData = new FormData();
    formData.append('roletype',$('#typeRoleRequest').val());
    formData.append('contract_id',$('#contract_id').val());
    //Control para indicarle a la persona la bienvenida
    //Puedes firmar desde dispositivos táctiles o con tu DNI
    $.confirm({
        // theme: 'dark',
        title: '<i class="fas fa-file-contract fa-2x text-lightblue"></i> &nbsp;<i class="fa fa-exclamation text-lightblue"></i> Bienvenido a tu contrato online<i class="fa fa-exclamation text-lightblue"></i>  ',
        content: 'A continuación, verás la información relacionada con tu contrato, revisa que la información sea la correcta'+
                ' y firma en el lugar adecuado. Puedes firmar desde dispositivos táctiles',
        buttons: {
            confirmar :{
                btnClass: 'btn-blue',
                action: function () {
                    // here the button key 'hey' will be used as the text.
                    $.ajax({ 
                        data: formData,
                        url: "{{ route('contract.open') }}",
                        type: "POST",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},     
                        dataType: 'json',
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            $(".text-danger").text("");
                            if(!($.isEmptyObject(data['success']))){
                                $.alert(data['success']);
                                $("#dropContractModal").modal('hide');
                            }else{
                                console.log(data);
                            }
                        }
                    });
                    
                },
            }
        }
    });

    var wrapper = document.getElementById("signature-pad"),
    wrapperPayer = document.getElementById("signature-pad-payer"),
    clearButton = wrapper.querySelector("[data-action=clear]"),
    clearButtonPayer = wrapperPayer.querySelector("[data-action=clear]"),
    saveButton = wrapper.querySelector("[data-action=save]"),
    saveButtonPayer = wrapperPayer.querySelector("[data-action=save]"),
    canvas = wrapper.querySelector("canvas"),
    canvasPayer = wrapperPayer.querySelector("canvas");
    signaturePad = new SignaturePad(canvas);
    signaturePadPayer = new SignaturePad(canvasPayer);

    // Adjust canvas coordinate space taking into account pixel ratio,
    // to make it look crisp on mobile devices.
    // This also causes canvas to be cleared.
    window.resizeCanvas = function () {
        var ratio =  window.devicePixelRatio || 1;
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        canvas.getContext("2d").scale(ratio, ratio);
        var ratio =  window.devicePixelRatio || 1;
        canvasPayer.width = canvasPayer.offsetWidth * ratio;
        canvasPayer.height = canvasPayer.offsetHeight * ratio;
        canvasPayer.getContext("2d").scale(ratio, ratio);
    }

    clearButton.addEventListener("click", function (event) {
        signaturePad.clear();
    });
    clearButtonPayer.addEventListener("click", function (event) {
        signaturePadPayer.clear();
    });

    saveButton.addEventListener("click", function(event) {
        event.preventDefault();

        if (signaturePad.isEmpty() && $('#studentSignature').val() == "") {
            alert("Debe ingresar la firma.");
        } else {
        var image_data = signaturePad.toDataURL('image/png');
        var enrollment_number = $('#contract_enrollment_number').val();
        var typeRoleRequest = $('#typeRoleRequest').val();
        var contract_id = $('#contract_id').val();
        $.ajax({
            url: "{{ route('contract.accept') }}",
            type: 'POST',
            data: {
                image_data: image_data,
                enrollment_number : enrollment_number,
                contract_id : contract_id,
                typeRoleRequest : typeRoleRequest,
            },
        }).done(function() {
            //
        });
        }
    });

    saveButtonPayer.addEventListener("click", function(event) {
        event.preventDefault();

        if (signaturePadPayer.isEmpty()  && $('#payerSignature').val() == "") {
            alert("Debe ingresar la firma.");
        } else {
        var image_data = signaturePadPayer.toDataURL('image/png');
        var enrollment_number = $('#contract_enrollment_number').val();
        var typeRoleRequest = $('#typeRoleRequest').val();
        var contract_id = $('#contract_id').val();
        $.ajax({
            url: "{{ route('contract.accept') }}",
            type: 'POST',
            data: {
                image_data: image_data,
                enrollment_number : enrollment_number,
                contract_id : contract_id,
                typeRoleRequest : typeRoleRequest,
            },
            success: function (data) {
                toastr.success(data['success'], '', {timeOut: 3000,positionClass: "toast-top-center"});
                location.reload();
                },
            error: function (data) {
                console.log('Error:', data);
                $(".alert").removeClass('hide');
                $(".alert").addClass('show');
                $(".alert").slideDown(300).delay(3000).slideUp(300);
                $('#saveBtn').html('Guardar Cambios');
                }
        }).done(function() {
            //
        });
        }
    });

    $('#acceptButton').click(function (e) {
        e.preventDefault();
        $(this).html('Enviando..');
        var formData = new FormData($('#acceptContractForm')[0]);
        // console.log(formData);
        $.ajax({ 
            data: formData,      
            url: "{{ route('contract.accept') }}",
            type: "POST",
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function (data) {
                toastr.success(data['success'], '', {timeOut: 3000,positionClass: "toast-top-center"});
                location.reload();
                },
            error: function (data) {
                console.log('Error:', data);
                $(".alert").removeClass('hide');
                $(".alert").addClass('show');
                $(".alert").slideDown(300).delay(3000).slideUp(300);
                $('#saveBtn').html('Guardar Cambios');
                }
        });
    });
</script>