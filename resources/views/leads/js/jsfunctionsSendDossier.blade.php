<script>
    $('body').on('click', '.sendDossier', function (event) {
        var id= $(this).data('object');
        $.ajax({
            url:"{{url('sendDossier/') }}" +'/' + id ,
            type: 'GET'
        }).promise().then(function(data) {
            toastr.success('Dossier enviado correctamente', '', {timeOut: 3000,positionClass: "toast-top-center"});
        });
        // Stop the forms default action method
        event.preventDefault();
    });

</script>