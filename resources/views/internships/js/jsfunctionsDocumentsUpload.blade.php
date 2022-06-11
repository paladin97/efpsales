
<script>
    $(document).ready( function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        // Subir Documentos
        $('body').on('click', '.caseDocuments', function () {
            $('#contract_id_fee_document').val($(this).data('object'));
            $('#caseDocumentModal').modal('show');
        });
        
        //Inicia DropZone
        $('#caseDocumentModal').on('hidden.bs.modal', function(){
            location.reload();
        });
    
        //Inicia DropZone
        $('#caseDocumentModal').on('shown.bs.modal', function (e) {
            // $("#my-awesome-dropzone").remove();
            // <input type="hidden" name="contract_id_fee_document" id="contract_id_fee_document">
            // var div = document.getElementById('dropzoneempty');
            // var form = document.createElement('form');
            // var action = "{{ route('dropzone.store') }}";
            // form.className = "dropzone";
            // form.setAttribute('id', 'my-awesome-dropzone');
            // form.setAttribute('enctype', 'multipart/form-data');
            // form.setAttribute('action', action);
            // form.setAttribute('method', 'POST');
            // /*-----------*/
            // var input1 = document.createElement('input');
            // input1.setAttribute('type', 'hidden');
            // input1.setAttribute('name', 'contract_id_fee_document');
            // input1.setAttribute('id', 'contract_id_fee_document');
            // form.appendChild(input1);
            // div.appendChild(form);

            var CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
            // var exists = dropzoneExists('#my-awesome-dropzone');
            // if(exists) return;
            Dropzone.autoDiscover = false;
            var md = $("#my-awesome-dropzone").dropzone({
                maxFilesize: 3,  // 3 mb
                acceptedFiles: ".jpeg,.jpg,.png,.pdf",
                maxFiles: 10,
                parallelUploads : 1,
                dictDefaultMessage: '<p><i style="font-size: 5em" class="fad text-lightblue fa-cloud-upload fa-10x"></i></p><strong>Arrastre aqui los archivos para subir</strong>',
                dictRemoveFile : "Eliminar archivo",
                dictRemoveFileConfirmation : '¿Está seguro de que desea eliminar este archivo?',
                // acceptedFiles: 'application/pdf',
                dictInvalidFileType: 'Solo puede subir archivos en formato PDF o Imagen (JPG, JPEG, PNG)',
                dictCancelUpload : "Cancelar proceso",
                dictFallbackMessage : "Tu explorador no soporta esta funcionalidad.",
                addRemoveLinks: true,
                init: function() { 
                    myDropzone = this;
                    myDropzone.on("sending", function(file, xhr, formData) {
                        formData.append("_token", CSRF_TOKEN);
                    }); 
                    $.ajax({
                        url: "{{ route('dropzone.store') }}",
                        type: 'post',
                        data: {request: 'fetch',contract_id: $('#contract_id_fee_document').val()},
                        dataType: 'json',
                        success: function(response){
                            var src = "{{asset('storage/uploads/pdf.png')}}";
                            // console.log(src);
                            // console.log(response[0].path);
                            $.each(response, function(key,value) {
                                // console.log(value);
                                var mockFile = { name: value.name, size: value.size};
                                myDropzone.createThumbnailFromUrl(mockFile, 'https://efpsales.com/storage/uploads/pdf.png');
                                myDropzone.emit("addedfile", mockFile);
                                // myDropzone.emit("thumbnail", mockFile, value.path);
                                myDropzone.emit("complete", mockFile);

                            });
                            $('.dz-details').each(function(index, element) {
                                (function(index) {
                                    $(element).attr('id', "filename_" + index);
                                    var selectFile = document.querySelector("#filename_" + index);
                                    selectFile.addEventListener("click", function () {
                                    window.open(response[index].path);
                                    // window.open("http://localhost:8080/<<contextpath>>/pathtofile/" +  $('#filename_' + index + '> div > span').text());
                                    });
                                }(index));
                            });
                        }
                    });
                },
                accept: function(file, done) {
                    console.log(file);
                    var thumbnail = $('.dropzone .dz-preview.dz-file-preview .dz-image:last');
                    thumbnail.css('background', 'url(https://abogadoscrm.online/storage/uploads/pdf.png)');
                    // location.reload();
                    done();
                },
                removedfile: function(file) {
                    // console.log(file);
                    $.ajax({
                    url: "{{ route('dropzone.store') }}",
                    type: 'post',
                    data: {name: file.name, request: 'delete',contract_id: $('#contract_id_fee_document').val()},
                    success: function(response){
                        var _ref;
                        return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
                        }
                    });
                }
            });
        });
        
    });
</script>