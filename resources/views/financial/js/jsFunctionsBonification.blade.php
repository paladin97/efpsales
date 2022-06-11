<script>

//Guardar Bonificación
// $('#savedBtnBonification').click(function (e) {
// 		e.preventDefault();
// 		$('#savedBtnBonification').addClass("disabled");
// 		var formData = new FormData($('#editBonification')[0]);
// 		$.ajax({ 
// 			data: formData,      
// 			url: "{{ route('liquidation.updatebonification') }}",
// 			type: "POST",
// 			dataType: 'json',
// 			contentType: false,
// 			processData: false,
// 			success: function (data) {
// 				$(".text-danger").text("");
// 				if($.isEmptyObject(data.error)){
// 					$('#editBonification').trigger("reset");
// 					$('#editBonificationModal').modal('hide');
// 					$('#savedBtnBonification').html('Guardar Cambios');
// 					$('#savedBtnBonification').removeClass("disabled");
// 					toastr.success('Bonificación Actualizada Correctamente', '', {timeOut: 3000,positionClass: "toast-top-center"});
// 					table.draw(false);
// 				}else{
// 					$.each( data.error, function( key, value ) {
// 						$("#EditEnrollError").append('<li><label class="text-red">'+value+'</label></li>');
// 						// $("#"+key+"_error").text(value);
// 					});
// 					$('#savedBtnBonification').html('Guardar Cambios');
// 					$('#savedBtnBonification').removeClass("disabled");
// 				}
// 			}
// 		});
// });

// Editar Bonificación
// $('body').on('click', '.editBonification', function () {
//             var tr = $(this).closest('tr');
//             var row = table.row( tr );
//             var i = $(this).find('i');
//             // var liquidation_id =row.data().crypt_case_id;
//             // $.get("{{route('liquidationcrud.index') }}" +'/' + liquidation_id +'/edit', function (data) {
//             //     $('#positive_id').val(data[0].id);
//             //     // $('#f_obs_enrollment_number').val(data[0].enrollment_number);
//             //     $('#positive_value').val(data[0].positive_value);

//             // });
//             $('#editBonificationModal').modal('show');
//         });


</script>