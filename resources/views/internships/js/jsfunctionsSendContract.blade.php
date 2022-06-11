<script>
    $('body').on('click', '.sendContract', function (event) {
        var id= $(this).data('object');
        $.ajax({
            url:"{{url('sendContract/') }}" +'/' + id ,
            type: 'GET'
        }).promise().then(function(data) {
            toastr.success('Correo enviado correctamente', '', {timeOut: 3000,positionClass: "toast-top-center"});
        });
        // Stop the forms default action method
        event.preventDefault();
    });

</script>