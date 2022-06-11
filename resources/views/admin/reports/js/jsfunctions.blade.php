<script>
    $('input[name="ranges"]').daterangepicker({
        opens: 'left',
        locale: {
            "format": "DD/MM/YYYY",
            "separator": " - ",
            "applyLabel": "Aplicar",
            "cancelLabel": "Limpiar",
            "fromLabel": "From",
            "toLabel": "To",
            "customRangeLabel": "Personalizado",
            "daysOfWeek": [ "Do","Lu", "Ma","Mi", "Ju","Vi","Sa"],
            "monthNames": ["Enero", "Febrero", "Marzo","Abril","Mayo", "Junio", "Julio","Agosto","Septiembre", "Octubre","Noviembre", "Diciembre"],
            // "firstDay": 1
        }
    }, function(start, end, label) {
        $("#dt_report_from").val(start.format('YYYY-MM-DD'));
        $("#dt_report_to").val(end.format('YYYY-MM-DD'));
        // console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
    }); 

</script>