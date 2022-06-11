<script>
    $('body').on('click', '.sendDossierMatBonf', function (event) {
        var id= $(this).data('object');
        $.ajax({
            url:"{{url('sendDossierMatBonf/') }}" +'/' + id ,
            type: 'GET'
        }).promise().then(function(data) {
            toastr.success('Dossier enviado correctamente', '', {timeOut: 3000,positionClass: "toast-top-center"});
        });
        // Stop the forms default action method
        event.preventDefault();
    });

</script>