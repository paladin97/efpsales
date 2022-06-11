<script>

    $(document).ready( function () {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
    
        const Toast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true,
          onOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
          }
        })
    
        //Botones de uploadfile
        $("#avatar").filestyle({
            size: 'sm',
            btnClass : 'btn-success',
            text : '&nbsp; Cambiar Foto'
        });
        
        $(".bootstrap-filestyle  input").addClass('form-control-sm');
    
    
        $('#btnSave').click(function (e){
          e.preventDefault();
          $(this).html('Enviando..');
          var formData = new FormData($('#profileForm')[0]);
          $.ajax({ 
            data: formData,      
            url: "{{ route('profile.store') }}",
            type: "POST",
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function (data) {
            // console.log(data);
              if($.isEmptyObject(data.error)){
                if(!($.isEmptyObject(data[0]['success']))){
                  console.log(data[1]['showinformation'].user_name);
                  $('#profileForm').trigger("reset");
                  $('#user_avatar').attr('src',"{{asset('storage/uploads/users/')}}"+'/'+data[1]['showinformation'].user_avatar)
                  $('#user_name').val(data[1]['showinformation'].user_name);
                  $('#last_name').val(data[1]['showinformation'].user_last_name);
                  $('#profile_url').val(data[1]['showinformation'].profile_url);
                  $('#mail').val(data[1]['showinformation'].email);
                  // $('#password').val(data[1]['showinformation'].mail);
                  $('#btnSave').html('<span class="text-bold">Actualizar</span>');
                  toastr.success(data[0]['success'], '', {timeOut: 3000,positionClass: "toast-top-center"});
                }
                else{
                    $('#profileForm').trigger("reset");
                    toastr.error('No se puede actualizar, revise los errores', '', {timeOut: 3000,positionClass: "toast-top-center"});  
                }   
              }
            }
          });
        });
    });
      
    
    </script>