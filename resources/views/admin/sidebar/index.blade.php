<div class="p-3">
    <h5>Administración del Sitio</h5>
    <hr class="mb-2">
    <nav class="mt-2">
        <ul style="color:white" class="nav nav-pills nav-sidebar flex-column  text-sm" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link ">
              <i class="nav-icon fad fa-users-cog"></i>
              <p>Usuarios<i class="fad  fa-angle-right right"></i></p>
            </a>
            <ul class="nav nav-treeview ml-3">
              <li class="nav-item">
                <a href="{{ route('user.index') }}" class="nav-link">
                  <i class="fad fa-toggle-on"></i><p> Administrar</p>
                </a>
              </li>
            </ul>
          </li>
          {{-- <li class="nav-item has-treeview">
            <a href="#" class="nav-link ">
              <i class="nav-icon fad fa-id-card"></i>
              <p>Información Personal<i class="fad  fa-angle-right right"></i></p>
            </a>
            <ul class="nav nav-treeview ml-3">
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="fad fa-toggle-on"></i><p> Administrar</p>
                </a>
              </li>
            </ul>
          </li> --}}
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link ">
              <i class="nav-icon fad fa-warehouse-alt"></i>
              <p>Empresas<i class="fad  fa-angle-right right"></i></p>
            </a>
            <ul class="nav nav-treeview ml-3">
              <li class="nav-item">
                <a href="{{ route('company.index') }}" class="nav-link">
                  <i class="fad fa-toggle-on"></i>
                  <p> Administrar</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link ">
              <i class="nav-icon fad fa-id-card"></i>
              <p>Leads<i class="fad  fa-angle-right right"></i></p>
            </a>
            <ul class="nav nav-treeview ml-3">
              <li class="nav-item">
                <a href="{{ route('origin.index') }}" class="nav-link">
                  <i class="fad fa-globe-americas"></i>
                  <p>Origenes del Lead</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('status.index') }}" class="nav-link">
                  <i class="fad  fa-clipboard-list-check"></i>
                  <p>Estados del Lead</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link ">
              <i class="nav-icon fad fa-file-invoice"></i>
              <p>Contratos<i class="fad  fa-angle-right right"></i></p>
            </a>
            <ul class="nav nav-treeview ml-3">
              <li class="nav-item">
                <a href="{{ route('contractstatus.index') }}" class="nav-link">
                  <i class="fad fa-clipboard-list-check"></i>
                  <p>Estados de Contratos</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link ">
              <i class="nav-icon fad fa-users-class"></i>
              <p>Cursos<i class="fad  fa-angle-right right"></i></p>
            </a>
            <ul class="nav nav-treeview ml-3">
              <li class="nav-item">
                <a href="{{ route('coursecrud.index') }}" class="nav-link ">
                  <i class="fad fa-folder-open"></i>
                  <p> Administrar</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link ">
              <i class="nav-icon fad fa-coins"></i>
              <p>Finanzas<i class="fad  fa-angle-right right"></i></p>
            </a>
            <ul class="nav nav-treeview ml-3">
              <li class="nav-item">
                <a href="{{ route('liquidationmodel.index') }}" class="nav-link">
                  <i class="nav-icon fad fa-file-chart-line"></i>
                  <p>Modelos de Liquidaciones</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview ml-3">
              <li class="nav-item">
                <a href="{{ route('bank.index') }}" class="nav-link">
                  <i class="nav-icon fad  fa-landmark"></i>
                  <p>Bancos</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview ml-3">
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon fad fa-exchange"></i>
                  <p>Cod. Precios Especiales</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link ">
              <i class="nav-icon fad fa-file-spreadsheet"></i>
              <p>Hoja Contable <i class="fad  fa-angle-right right"></i></p>
            </a>
            <ul class="nav nav-treeview ml-3">
              <li class="nav-item">
                <a href="{{ route('spreadsheet.index') }}" class="nav-link">
                  <i class="fad fa-toggle-on"></i>
                  <p> Administrar</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link ">
              <i class="nav-icon fad fa-th"></i>
              <p>Reporting <i class="fad  fa-angle-right right"></i></p>
            </a>
            <ul class="nav nav-treeview ml-3">
              <li class="nav-item">
                <a href="{{ route('reports.index') }}" class="nav-link">
                  <i class="nav-icon fad fa-file-invoice"></i>
                  <p>Informes Rápidos</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link ">
              <i class="nav-icon fad fa-file-alt"></i>
              <p>Términos y Condiciones <i class="fad  fa-angle-right right"></i></p>
            </a>
            <ul class="nav nav-treeview ml-3">
              <li class="nav-item">
                <a href="{{ route('termscrud.index') }}" class="nav-link">
                  <i class="fad fa-toggle-on"></i>
                  <p> Administrar</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link ">
              <i class="nav-icon fad fa-user-friends"></i>
              <p>Información Personal<i class="fad  fa-angle-right right"></i></p>
            </a>
            <ul class="nav nav-treeview ml-3">
              <li class="nav-item">
                <a href="{{ route('peopleinfcrud.index') }}" class="nav-link">
                  <i class="fad fa-toggle-on"></i>
                  <p> Administrar</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link ">
              <i class="nav-icon fad fa-quote-left"></i>
              <p>Administrador de Frases<i class="fad  fa-angle-right right"></i></p>
            </a>
            <ul class="nav nav-treeview ml-3">
              <li class="nav-item">
                <a href="{{ route('quotemanager.index') }}" class="nav-link">
                  <i class="fad fa-toggle-on"></i>
                  <p> Administrar</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link ">
              <i class="nav-icon fad fa-tags"></i>
              <p>Promociones<i class="fad  fa-angle-right right"></i></p>
            </a>
            <ul class="nav nav-treeview ml-3">
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="fad fa-toggle-on"></i>
                  <p> Administrar</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link ">
              <i class="nav-icon fab fa-whatsapp"></i>
              <p>Plantillas de Whatsapp<i class="fad  fa-angle-right right"></i></p>
            </a>
            <ul class="nav nav-treeview ml-3">
              <li class="nav-item">
                <a href="{{route('whatsappcrud.index')}}" class="nav-link">
                  <i class="fad fa-toggle-on"></i>
                  <p> Administrar</p>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </nav>
</div>