<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="ajaxModel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-100 w-75" role="document">  
        <div class="modal-content">     
            <div class="modal-header bg-lightblue color-palette">  
                <h4 class="modal-title" id="modalHeadingCertificate">Editar</h4>   
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fad fa-times text-white"></i></span>
                </button>           
            </div>    
            <div class="modal-body">    
                <form id="certificateForm" name="certificateForm" class="form-horizontal" enctype="multipart/form-data">   
                    <input type="hidden" name="cert_id" id="cert_id">
                    <div class="row mt-n2">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Contrato <label class="text-red">(*)</label></label>
                                <select style="width:100%;" class="form-control form-control-sm select_list" id="gencert_contract" name="gencert_contract">
                                    <option value="">Matrícula</option>
                                    @foreach(App\Models\Contract::from('contracts as c')
                                                                    ->leftJoin('people_inf as pi','c.person_id','=','pi.id')
                                                                    ->leftJoin('courses as crse','c.course_id','=','crse.id')
                                                                    ->whereContractStatusId(3)
                                                                    ->orderBy('enrollment_number','DESC')
                                                                    ->select('c.*','pi.name as name','pi.last_name as last_name','crse.name as crse_name')
                                                                    ->get() as $cData)
                                    <option value="{{$cData->id}}">{{$cData->name}}, {{$cData->last_name}} ({{$cData->enrollment_number}} - {{$cData->crse_name}})</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
                {{-- @include('partials.admin.information') --}}
            </div>
            <p class="col-sm-12 text-xs mt-3"><i class="fas fa-exclamation-triangle fa-xs text-red"></i><span class="text-red text-bold">Información</span>. Todos los campos marcados con <label class="text-red">(*)</label> son obligatorios.</p>
            <small>
                <ol id="certificateCreateOrUpdateError">

                </ol>
            </small>
            <div class="modal-footer">
                <button type="submit" class="btn bg-lightblue" id="saveBtn" value="create-product">Generar</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                <p>
                <div class="alert alert-dismissible" style="display: none; background-color: green;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <div id="alert_message_lead" align="center" style="color:white;"></div>
                </div>
            </div>
        </div>
    </div>
</div>