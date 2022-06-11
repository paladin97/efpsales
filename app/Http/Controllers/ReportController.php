<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User,Report,PersonInf,Lead,Role,Company,AccessCompany,Contract,ContractFee,Liquidation};
use DataTables,Auth,Redirect,Response,Config,DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use PDF,File,Mail;
use DateTime;
use App\Exports\ReportsExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    
    public function index(Request $request)
    {
        $user = Auth::user();
        $companies = User::find($user->id)->companies;
        $pageTitle ='Reportes Rápidos - '.$companies->pluck('name')[0];
        $postMessage = '';
        return view('admin.reports.index',compact('postMessage','pageTitle'));
            
    }

    public function export(Request $request)
    {
        // dd("HOLA");
        $user = Auth::user();
        $companies = User::find($user->id)->companies;
        $pageTitle ='Reportes Rápidos - '.$companies->pluck('name')[0];
        // dd(isset($request->dt_report_from));
        if(isset($request->dt_report_from) && isset($request->dt_report_to) && isset($request->report_list_filter)){
            //Se obtienen los datos del filtro inicial
            $reportCrud = Report::whereId($request->report_list_filter)->get()->first();
            $fileName = $reportCrud->file_name.'.xls';
            $reportHeadings =  explode(',', $reportCrud->query_headings);
            // dd($reportCrud); 

            $data = ['dt_report_from' => $request->dt_report_from,
                        'dt_report_to' => $request->dt_report_to,
                        'report_list_filter' => $request->report_list_filter
            ];
            // dd($data);
            return Excel::download(new ReportsExport($data,$reportHeadings), $fileName);
            
        }
        else{
            $postMessage = '<h3 align="center"><i class="fa fa-warning text-red"></i> Debe seleccionar todos los filtros de búsqueda</h3>';
            return view('admin.reports.index',compact('postMessage','pageTitle'));
        }  
    }

    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function destroy(Report $report)
    {
        //
    }
}
