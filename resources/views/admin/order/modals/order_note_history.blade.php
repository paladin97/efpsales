<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="historyNotesOrderModal"
    data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-100 w-50" role="document">
        <div class="modal-content">
            <div class="modal-header bg-lightblue color-palette">
                <h4 class="modal-title" id="modalHeadingHistoryNotes">Notas</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fad fa-times text-white"></i></span>
                </button>
            </div>
            <div class="modal-body">

                <div class="card card-lightblue card-outline">
                    <div class="card-body">
                        <h3 align="center">Histórico de Notas</h3>
                        <div class="row" style="margin:auto;">
                            <div class="col-sm-12">
                                <table id="notes_table"
                                    class="small-table table-sm stripe row-border order-column compact table table-striped"
                                    cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th><input name="select_all" value="1" type="checkbox"></th>
                                            <th>Notas</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>

                <div class="alert alert-dismissible" style="display: none; background-color: green;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <div id="alert_message_lead" style="color:white;"></div>
                </div>
            </div>

        </div>
    </div>
</div>

