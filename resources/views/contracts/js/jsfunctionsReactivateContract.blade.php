<script>

    // Dar de Baja Una Matrícula
    $('body').on('click', '.reActivateLead', function () {
        // console.log(rctivate_contract_id);
        var rctivate_contract_id = $(this).data('object');
        $.confirm({
            // theme: 'dark',
            title: '<i class="fad fa-exclamation fa-rotate-180 text-red"></i> Atención<i class="fad fa-exclamation text-red"></i> ',
            content: '¿Confirma la reactivación del lead?',
            buttons: {
                confirmar :{
                    btnClass: 'btn-blue',
                    action: function () {
                        // here the button key 'hey' will be used as the text.
                        $.ajax({ 
                            url:"{{url('reactivatelead/') }}" +'/' + rctivate_contract_id ,
                            type: 'GET',
                            success: function (data) {
                                $(".text-danger").text("");
                                if(!($.isEmptyObject(data['success']))){
                                    toastr.success(data['success'], '', {timeOut: 3000,positionClass: "toast-top-center"});
                                    table.draw(false);
                                }else{
                                    toastr.error(data['error'], '', {timeOut: 3000,positionClass: "toast-top-center"});
                                    table.draw(false);
                                }
                            }
                        });
                        
                    },
                },
                Cancelar: {
                    text: 'Cancelar', // With spaces and symbols
                    action: function () {
                        $.alert('Operación Cancelada');
                    }
                }
            }
        });
    });
        //Fin controles Dar de Baja
</script>