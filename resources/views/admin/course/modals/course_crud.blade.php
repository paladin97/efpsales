<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="ajaxModel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-100 w-75" role="document">  
        <div class="modal-content">     
            <div class="modal-header bg-lightblue color-palette">  
                <h4 class="modal-title" id="modalHeadingCourse">Editar</h4>   
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fad fa-times text-white"></i></span>
                </button>           
            </div>    
            <div class="modal-body">    
                <form id="courseForm" name="courseForm" class="form-horizontal" enctype="multipart/form-data">   
                    <input type="hidden" name="course_id" id="course_id">
                    <div class="row mt-n2">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Nombre del Curso <label class="text-red">(*)</label></label>
                                <input type="text" class="form-control form-control-sm" id="newcrse_name" name="newcrse_name" placeholder="Ingrese el nombre" value="" maxlength="250" required="">
                                <small style="font-size: 70%" id="name_error" class="text-danger"></small>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Grupo <label class="text-red">(*)</label></label>
                                <select style="width:100%;" class="form-control form-control-sm select_list" id="newcrse_area" name="newcrse_area">
                                    <option value="">Categoría del Curso</option>
                                    @foreach(App\Models\CourseArea::all()->sortBy('name') as $cData)
                                        <option value="{{$cData->id}}">{{$cData->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Empresa <label class="text-red">(*)</label></label>
                                <select style="width:100%;" class="form-control form-control-sm select_list" id="newcrse_company" name="newcrse_company" required="">
                                    {{-- <option value="">Seleccione la empresa asociada</option> --}}
                                    @foreach(App\Models\Company::all()->sortBy('name') as $cData)
                                        <option value="{{$cData->id}}">{{$cData->name}}</option>
                                    @endforeach
                                    <small style="font-size: 70%" id="user_club_error" class="text-danger"></small>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-n2">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Tipo <label class="text-red">(*)</label></label>
                                <select style="width:100%;" class="form-control form-control-sm select_list" id="newcrse_tipo" name="newcrse_tipo">
                                    <option value="">Tipo de Curso</option>
                                    @foreach(App\Models\CourseType::all()->sortBy('name') as $cData)
                                        <option value="{{$cData->id}}">{{$cData->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Duración <label class="text-red">(*)</label></label>
                                <input type="text" class="form-control form-control-sm" id="newcrse_duration" name="newcrse_duration" placeholder="Ingrese la duración" value="" maxlength="250" required="">
                                <small style="font-size: 70%" id="name_error" class="text-danger"></small>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="name" class="mb-n1">PVP <label class="text-red">(*)</label></label>
                                <input type="text" class="form-control form-control-sm" id="newcrse_pvp" name="newcrse_pvp" placeholder="Ingrese el precio" value="" maxlength="250" required="">
                                <small style="font-size: 70%" id="name_error" class="text-danger"></small>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Dossier <label class="text-red">&nbsp;</label></label>
                                <input type="file"  class="form-control  form-control-sm" id="fee_path" name="fee_path" placeholder="" value="" maxlength="250">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-n2">
                        <div class="col-md-12">
                            <div class="row mt-n2">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="name" class="mb-n1">Temario del Curso <label class="text-red">(*)</label> &nbsp;&nbsp; <span id="dossier_download" class="d-none"><a id="dossier_url" href="" target="_blank"><i class="fad fa-file-pdf text-lightblue"></i> Descargar Dossier</a></span></label>
                                        <textarea id="summernote" name="editordata"></textarea>
                                        <small style="font-size: 70%" id="editordata_error" class="text-danger"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                {{-- @include('partials.admin.information') --}}
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn bg-lightblue" id="saveBtn" value="create-product">Guardar Cambios</button>
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