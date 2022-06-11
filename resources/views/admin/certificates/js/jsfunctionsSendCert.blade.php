<script>
    $('body').on('click', '.sendCert', function (event) {
        var id= $(this).data('object');
        $.ajax({
            url:"{{url('sendCert/') }}" +'/' + id ,
            type: 'GET'
        }).promise().then(function(data) {
            toastr.success('Certificado enviado correctamente', '', {timeOut: 3000,positionClass: "toast-top-center"});
        });
        // Stop the forms default action method
        event.preventDefault();
    });

</script>