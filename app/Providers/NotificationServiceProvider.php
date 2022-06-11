<?php

namespace App\Providers;

use App\Models\{User,Contract,Lead,EventMaster};
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use DataTables,Auth,Redirect,Response,Config,DB,Validator;
use Carbon\Carbon;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $dt_ini = Carbon::now()->startOfMonth()->format('Y-m-d');
        $dt_end = Carbon::now()->endOfMonth()->format('Y-m-d');
       
        $queryLeads         = Lead::from('leads as le')
                                ->where('le.lead_status_id','=',1) //abierto
                                ->whereNotIn('le.agent_id',[7])
                                ->select('le.id')
                                ->groupBy('le.id');
        $queryContracts     = Contract::from('contracts as cs')
                                ->where('cs.contract_status_id','=',1) //pendiente aceptar
                                ->select('cs.id')
                                ->groupBy('cs.id');
        $queryEvent         = EventMaster::from('event_masters as em')
                                ->where('em.start','>=',$dt_ini) //fecha inicial
                                ->where('em.end','=<',$dt_end) //fecha final
                                ->select('em.id')
                                ->groupBy('em.id');
        $where_not_exists = '1 FROM certificates as cert WHERE cert.contract_id = cs.id';
        $queryCertificate   = Contract::from('contracts as cs') //pendiente generar certificado
                                ->where('cs.contract_status_id','=',3) //Matriculado
                                ->whereRaw('DATEDIFF(NOW(),DATE_ADD(cs.dt_created,  INTERVAL cs.validity MONTH)) >= cs.validity')
                               
                                ->whereNotExists(function($query) use ($where_not_exists) {
                                    $query->select(DB::raw($where_not_exists));
                                    })
                                ->select('cs.id')
                                ->groupBy('cs.id');
        // dd($queryCertificate->get());
        //<a href="javascript" class="dropdown-item dropdown-footer">Ver Todas Las Notificaciones</a>
        
        $data = [$queryLeads, $queryContracts, $queryEvent, $queryCertificate];
        // dd($data[0]);
        View::share('notificationCenter', $data);
        
    }
}
