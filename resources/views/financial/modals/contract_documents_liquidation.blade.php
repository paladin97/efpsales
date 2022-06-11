<div class="modal modal-wide fade" id="caseDocumentModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered mw-100 w-75" role="document">
        <div class="modal-content">   
            <div class="modal-header bg-lightblue color-palette">  
                <h4 class="modal-title" id="modelDocumentHeadingCase">Gestión Documental Liquidaciones</h4>   
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fad fa-times text-white"></i></span>
                </button>        
            </div>
            <div class="modal-body">
                <div class="row" style="margin:auto;">
                    <div class="col-sm-12" id="dropzoneempty">
                        {{-- <form action="{{ route('dropzone.store') }}" method="post" enctype="multipart/form-data" class="dropzone" id="my-awesome-dropzone">
                            <input type="hidden" name="dataliq_agent_document" id="dataliq_agent_document">  
                        </form> --}}
                    </div>
                </div>
                
			</div>
			<span class="col-sm-12 text-xs mt-3">
                <i class="fas fa-exclamation-triangle fa-xs text-red"></i>
                <span class="text-red text-bold">Información</span>. <label class="text-red">(*)</label><br>
                <ul class="text-red">
                    <li>Solo se permiten archivos en formato PDF, Imagen o Audio</li>
                    <li>Solo se puede subir un máximo de 15 archivos</li>
                    <li>Tamaño máximo permitido por archivo, 3MB</li>
                    <li>Para visualizar un documento, solo haga click sobre el logotipo</li>
                </ul>
            </span>
			<small><ol id="leadCaseError"></ol></small>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
			</div>
        </div>
    </div>
</div>