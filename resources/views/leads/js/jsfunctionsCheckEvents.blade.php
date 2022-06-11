<script>
    // Modal de editar Lead
	$('body').on('click', '.check_calendar', function () {
        var dateToCheck = $('#dt_call_reminder').val();
        $.when(
            $("#listCalendarEvents").html('<div class="timeline">'+
                                        '<div class="time-label" id="checkInsertBody">'+
                                            '<span class="bg-lightblue text-white" id="calendarCheckTitle"></span>'+
                                        '</div>'+
                                    '</div>')
        ).then(function(){
            if (dateToCheck == "") {
                $.alert('Por favor seleccione una fecha');
            }
            else {
                $.ajax({
                    url: "{{ route('eventmaster.list') }}",
                    type: 'POST',
                    data: {
                        dateToCheck: dateToCheck,
                    },
                    success: function (data) {
                        $("#calendarCheckTitle").html(moment(dateToCheck).format('DD/MM/YYYY'));
                        var d1 = document.getElementById('checkInsertBody');
                        $.each( data, function( key, value ) {
                            d1.insertAdjacentHTML('afterend', '<div>'+
                                '<i class="fas fa-calendar bg-lightblue"></i>'+
                                '<div class="timeline-item">'+
                                    '<span class="time txt-lg text-lightblue" style="font-size:1.2em"><i class="fas fa-clock"></i> '+moment(value['start']).format('HH:mm')+'</span>'+
                                    '<h3 class="timeline-header">Recordatorio: '+value['student_first_name'] +' '+value['student_last_name']+'</h3>'+
                
                                    '<div class="timeline-body"> '+value['title']+'</div>'+
                                '</div>');
                        });
                    }
                });
                $('#leadNotesCheckEvents').modal('show');
            } 
        });
    	
        
    });

</script>