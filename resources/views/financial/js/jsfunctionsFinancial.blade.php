<script>
    
	const table = $('.dasta-table').DataTable({
		dom: 'rt',
		
		order: [[ 0, 'desc' ]],
		language: {
			search: "<strong>Buscar:</strong>",
				lengthMenu: "<strong>Mostrar _MENU_ Registros por página&nbsp;&nbsp;</strong>",
				zeroRecords: "<strong>No se ha encontrado ningún registro</strong>",
				info: "<strong>Mostrando la página _PAGE_ de _PAGES_</strong>",
				infoEmpty: "<strong>No hay registros disponibles</strong>",
				infoFiltered: "<strong>(filtrado de los registros totales de _MAX_)</strong>",
				processing: "Procesando",
				paginate: {
					first: "<strong>Primero</strong>",
					last: "<strong>Último</strong>",
					next: "<strong>Siguiente</strong>",
					previous: "<strong>Anterior</strong>"
				}
		},
    });
	
    var wrapper = document.getElementById("signature-pad"),
    clearButton = wrapper.querySelector("[data-action=clear]"),
    saveButton = wrapper.querySelector("[data-action=save]"),
    canvas = document.querySelector("canvas"),
    parentWidth = $(canvas).parent().outerWidth();

    canvas.setAttribute("width", parentWidth);

    
    signaturePad = new SignaturePad(canvas);
    

    // Adjust canvas coordinate space taking into account pixel ratio,
    // to make it look crisp on mobile devices.
    // This also causes canvas to be cleared.
    window.resizeCanvas = function () {
        var ratio =  window.devicePixelRatio || 1;
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        canvas.getContext("2d").scale(ratio, ratio);
    }

    clearButton.addEventListener("click", function (event) {
        signaturePad.clear();
    });

    saveButton.addEventListener("click", function(event) {
        event.preventDefault();

        if (signaturePad.isEmpty()) {
            $.alert("Debe ingresar la firma.");
        } else {
        var image_data = signaturePad.toDataURL('image/png');
        var agent_id = $('#accept_agent_id').val();
        var period_liq = $('#accept_period_liq').val();
        var company_id = $('#company_id').val();
        var positive_value = $('#positive_value').val();
        var negative_value = $('#negative_value').val();
        $.ajax({
            url: "{{ route('liquidation.accept') }}",
            type: 'POST',
            data: {
                image_data: image_data,
                agent_id : agent_id,
                period_liq : period_liq,
                company_id : company_id,
                positive_value : positive_value,
                negative_value : negative_value,
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

	//Guardar Profesor
	$('.sendLiquidation').click(function (e) {
		e.preventDefault();
		$('#sendLiqBtn').addClass("disabled");
		var formData = new FormData($('#sendLiquidationForm')[0]);
		$.ajax({ 
			data: formData,      
			url: "{{ route('liquidation.send') }}",
			type: "POST",
			dataType: 'json',
			contentType: false,
			processData: false,
			success: function (data) {
				$(".text-danger").text("");
				if($.isEmptyObject(data.error)){
					$('#sendLiquidationForm').trigger("reset");
					$('#sendLiquidationFormModal').modal('hide');
					$('#sendLiqBtn').html('<i class="fad fa-envelope-open-text"></i> Enviar Liquidación Por correo');
					$('#sendLiqBtn').removeClass("disabled");
					toastr.success(data['success'], '', {timeOut: 3000,positionClass: "toast-top-center"});
				}else{
                    toastr.error('No se pudo enviar el correo, contacte con el webmaster', '', {timeOut: 3000,positionClass: "toast-top-center"});
					$.each( data.error, function( key, value ) {
						$("#TeacherAssignError").append('<li><label class="text-red">'+value+'</label></li>');
						// $("#"+key+"_error").text(value);
					});
					$('#sendLiqBtn').html('<i class="fad fa-envelope-open-text"></i> Enviar Liquidación Por correo');
					$('#sendLiqBtn').removeClass("disabled");
				}
			}
		});
	});

    
</script>