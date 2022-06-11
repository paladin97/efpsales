<div class="modal fade" id="leadMassiveAssignModal" tabindex="-1" role="dialog" aria-labelledby="leadsHeading" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-100 w-75" role="document">     
        <div class="modal-content">   
            <div class="modal-header bg-lightblue color-palette">  
                <h4 class="modal-title" id="leadsHeading">Asignación de Leads</h4>   
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fad fa-times text-white"></i></span>
                </button>        
            </div>  
            <div class="modal-body">    
                <form id="leadAssignForm" name="leadAssignForm" class="form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" name="lead_id" id="lead_id_massive_assign">   
                    <div class="card-deck">
                        <div class="card card-lightblue card-outline col-sm-3">
                            <div class="card-body p-0">
                                <div id="leadinfoassign" class="text-lightblue">
    
                                </div>
                            </div>
                        </div>
                        <div class="card card-lightblue card-outline col-sm-9">
                            <div class="card-body p-0">
                                <ol id="leadassignagent" class="text-lightblue">

                                </ol>
                            </div>
                        </div>
                    </div>    
                    <p class="col-sm-12 text-xs mt-3"><i class="fas fa-exclamation-triangle fa-xs text-red"></i><span class="text-red text-bold">Información</span>. Todos los campos marcados con <label class="text-red">(*)</label> son obligatorios.</p>
                    <small>
                        <ol id="leadAssignError">

                        </ol>
                    </small>
                    <div class="modal-footer">
                        <button type="submit" class="btn bg-lightblue" id="saveAssignLeadBtn" value="create-product">Guardar Cambios</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                        <p>
                        <div class="alert alert-dismissible" style="display: none; background-color: green;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <div id="alert_message_lead" align="center" style="color:white;"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>