<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use App\Models\{Report};
use App\User;
use DataTables,Auth,Redirect,Response,Config,DB;

class ReportsExport implements FromCollection, WithHeadings,WithStrictNullComparison 
{ 
    protected  $data;
    public function __construct($data,$headings)
    {
        $this->data = $data;
        $this->headings = $headings;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $inData = $this->data;
        
        $queryFilter = $inData['report_list_filter'];
        //Obtiene el Query en una Variable
        $reportCrud = Report::whereId($queryFilter)->get()->first();

        //Ejecuta el query con los parametros del formulario
        $reportCrudQuery = preg_replace( '/\r|\n|\t/', '', $reportCrud->query );

        // dd($reportCrudQuery);  
        $querySP = DB::select(DB::raw($reportCrudQuery), 
                        array($inData['dt_report_from'],$inData['dt_report_to'],$inData['report_list_filter']));
        
        // dd($querySP);
        
        // dd(Report::hydrate($querySP));
        return Report::hydrate($querySP);
        // $querySP = User::all();
        // dd($results);
        // dd(collect($querySP));
        // return ($querySP);
                        
    }

    public function headings() : array
    {
        return $this->headings;
    }

    public function startCell(): string
    {
        return 'N2';
    }
}
