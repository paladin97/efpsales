<!-- Modal -->
<div class="modal fade" id="calendarModal" tabindex="-1" role="dialog" aria-labelledby="leadsHeading" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-100 w-75" role="document">
      <div class="modal-content">
        <div class="modal-header bg-lightblue color-palette">  
            <h4><i class="fad fa-calendar-alt text-bold text-lightblue text-lg"></i> <span id="miniCalendarHeader">Eventos Importantes</span>
              @if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('comercial'))
              <span id="url_header"></span>
              @endif
            </h4> 
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><i class="fad fa-times text-white"></i></span>
            </button>        
        </div>
        <div class="modal-body p-0">
            <div id="calendar"></div>
            
        </div>
      </div>
    </div>
</div>

