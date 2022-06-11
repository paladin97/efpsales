<script>

// //Guardar Egresos
// $('#savedBtnEgretion').click(function (e) {
// 		e.preventDefault();
// 		$('#savedBtnEgretion').addClass("disabled");
// 		var formData = new FormData($('#editEgretion')[0]);
// 		$.ajax({ 
// 			data: formData,      
// 			url: "{{ route('liquidation.updateegretion') }}",
// 			type: "POST",
// 			dataType: 'json',
// 			contentType: false,
// 			processData: false,
// 			success: function (data) {
// 				$(".text-danger").text("");
// 				if($.isEmptyObject(data.error)){
// 					$('#editEgretion').trigger("reset");
// 					$('#editEgretionModal').modal('hide');
// 					$('#savedBtnEgretion').html('Guardar Cambios');
// 					$('#savedBtnEgretion').removeClass("disabled");
// 					toastr.success('Egresos Actualizados Correctamente', '', {timeOut: 3000,positionClass: "toast-top-center"});
// 					table.draw(false);
// 				}else{
// 					$.each( data.error, function( key, value ) {
// 						$("#EditEnrollError").append('<li><label class="text-red">'+value+'</label></li>');
// 						// $("#"+key+"_error").text(value);
// 					});
// 					$('#savedBtnEgretion').html('Guardar Cambios');
// 					$('#savedBtnEgretion').removeClass("disabled");
// 				}
// 			}
// 		});
// });

// // Editar Egresos
// $('body').on('click', '.editEgretion', function () {
//             var tr = $(this).closest('tr');
//             var row = table.row( tr );
//             var i = $(this).find('i');
//             var contract_id =row.data().crypt_case_id;
//             $.get("{{route('liquidationcrud.index') }}" +'/' + contract_id +'/edit', function (data) {
//                 $('#egretion_id').val(data[0].id);
//                 // $('#f_obs_enrollment_number').val(data[0].enrollment_number);
//                 $('#negative_value').val(data[0].negative_value);

//             });
//             $('#editEgretionModal').modal('show');
//         });


// </script>