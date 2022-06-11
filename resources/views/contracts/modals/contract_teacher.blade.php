{{-- Modal de Usuario de Aula Virtual --}}
<div class="modal modal-wide fade" id="editTeacherModal" data-backdrop="static"  aria-hidden="true" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered mw-100 w-25" role="document"> 
        <div class="modal-content">     
            <div class="modal-header bg-lightblue color-palette">  
                <h4 class="modal-title" id="managementNotesHeading">Asignar Profesor</h4>   
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fad fa-times  text-white"></i></span>
                </button>        
            </div>    
            <div class="modal-body">   
                <form id="editTeacher" name="editTeacher" class="form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" name="teacher_id_case_id" id="teacher_id_case_id">
                    <div class="row d-flex justify-content-center" style="margin:auto;">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label for="name" class="mb-n1">Profesor Asignado  <label class="text-red">(*)</label></label>
									<select style="width:100%;" class="form-control form-control-sm  select_list" id="teacher_id" name="teacher_id">
										<option value="">Seleccione un Profesor</option>
										{{-- @if(Auth::user()->hasRole('comercial'))
											@foreach (App\Models\User::whereId(Auth::user()->id)->get() as $cData)
												<option value="{{$cData->id}}" selected>{{$cData->name}}</option>
											@endforeach
										@else --}}
											@foreach (App\Models\User::from('users as us')
                                                                ->leftJoin('people_inf as pi','us.person_id','=','pi.id')
                                                                ->select('us.*','pi.name as tutor_name','pi.last_name as tutor_last_name')
                                                                ->get()
                                                                ->sortBy('name') as $cData)
												@if ($cData->hasRole('teacher'))
													<option value="{{$cData->id}}">{{$cData->tutor_name}} {{$cData->tutor_last_name}}</option>
												@endif
											@endforeach
										{{-- @endif --}}
									</select>
                            </div>
                        </div>
                    </div>        
                </form>
            </div>
            <div class="modal-footer">
                <div align="right">
                    <button type="submit" href="javascript:void(0)" class="btn bg-lightblue" id="saveBtnTeacher" value="upload-sepa">Guardar Cambios</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>
