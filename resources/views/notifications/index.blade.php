@if(Auth::user()->hasRole('comercial'))
    @php
        $userId= Auth::user()->id;
        $leadCount = $notificationCenter[0]->where('le.agent_id','=',$userId)->get()->count();
        $contractCount =$notificationCenter[1]->where('cs.agent_id','=',$userId)->get()->count();
        $eventCount = $notificationCenter[2]->where('em.user_id','=',$userId)->get()->count();
        $certCount = $notificationCenter[3]->where('cs.agent_id','=',$userId)->get()->count();
    @endphp
@elseif (Auth::user()->hasRole('admin'))
    @php
        $leadCount = $notificationCenter[0]->leftJoin('users as us', 'le.agent_id', '=','us.id')
                                           ->leftJoin('people_inf as pi', 'us.person_id', '=','pi.id')
                                           ->whereIn('pi.company_id',Auth::user()->companies->pluck('id'))
                                           ->get()->count();
        $contractCount =$notificationCenter[1]->leftJoin('people_inf as pi', 'cs.person_id', '=','pi.id')
                                              ->whereIn('pi.company_id',Auth::user()->companies->pluck('id'))
                                              ->get()->count();
        $eventCount = $notificationCenter[2]->leftJoin('users as us', 'em.user_id', '=','us.id')
                                           ->leftJoin('people_inf as pi', 'us.person_id', '=','pi.id')
                                           ->whereIn('pi.company_id',Auth::user()->companies->pluck('id'))
                                           ->get()->count(); 
        $certCount = $notificationCenter[3]->leftJoin('people_inf as pi', 'cs.person_id', '=','pi.id')
                                            ->whereIn('pi.company_id',Auth::user()->companies->pluck('id'))
                                            ->get()->count();
    @endphp
@else

@php
        $leadCount = $notificationCenter[0]->get()->count();
        $contractCount =$notificationCenter[1]->get()->count();
        $eventCount = $notificationCenter[2]->get()->count();
        $certCount = $notificationCenter[3]->get()->count();
@endphp

@endif
@php
    $totalNotifications = $leadCount + $contractCount +$eventCount + $certCount;
    if($totalNotifications >= 0){
        $totalNotificationsHead= $totalNotifications >9 ? '9+' : $totalNotifications; 
        $notificationBanner = '<span class="badge badge-danger notification-badge animate__animated animate__flash animate__slower animate__infinite infinite">'.$totalNotificationsHead.'</span>';
    }
    else{
        $notificationBanner = '';
    }
@endphp
<li class="nav-item dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="fad fa-bell"></i>
        {!!$notificationBanner!!}
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item dropdown-header"><i class="fad fa-bullhorn  text-lightblue fa-lg"></i> <b class="text-lightblue text-md"> {{$totalNotifications}} Notificaciones</b></span>
        <div class="dropdown-divider"></div>
        <a href="{{route('leadcrud.index')}}" class="dropdown-item">
        <i class="fad fa-id-card  text-lightblue mr-2"></i> {{$leadCount}}  Leads Abiertos
        </a>
        <div class="dropdown-divider"></div>
        <a href="{{route('contractcrud.index')}}" class="dropdown-item">
        <i class="fad fa-file-signature  text-lightblue mr-2"></i>  {{$contractCount}}  Contratos sin Aceptar
        </a>
        <div class="dropdown-divider"></div>
        <a href="{{route('appointments')}}" class="dropdown-item">
        <i class="fad fa-calendar-exclamation text-lightblue mr-2"></i>   {{$eventCount}}  Eventos del mes
        </a>
        <div class="dropdown-divider"></div>
        <a href="{{url('certificatecrud')}}" class="dropdown-item">
        <i class="fad fa-file-certificate text-lightblue mr-2"></i>   {{$certCount}}  Certificados Pendientes
        </a>
    </div>
</li>