<?php

namespace App\Http\Controllers;

use App\Models\{DocumentUpload,Contract,LiquidationLog};
use Illuminate\Support\Facades\Storage;
use PDF,File;
use Illuminate\Http\Request;

class DocumentUploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Models\DocumentUpload  $documentUpload
     * @return \Illuminate\Http\Response
     */
    public function show(DocumentUpload $documentUpload)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DocumentUpload  $documentUpload
     * @return \Illuminate\Http\Response
     */
    public  function dropzoneStore(Request $request)  
    {  
        if($request->has('request') && $request->all()['request'] == 'fetch'){
            $contract_id = decrypt($request->all()['contract_id']);
            $enrollment_number =Contract::find($contract_id);
            $url = 'storage/contractdocuments/' .$enrollment_number->enrollment_number.'/';
            $fileList = [];
  
            $dir = public_path($url);
            if (is_dir($dir)){
              if ($dh = opendir($dir)){
                while (($file = readdir($dh)) !== false){
                  if($file != '' && $file != '.' && $file != '..'){
                    $file_path = $url.$file;
                    if(!is_dir($file_path)){
                       $size = filesize($file_path);
                       $fileList[] = ['name'=>$file, 'size'=>$size, 'path'=>$file_path];
                    }
                  }
                }
                closedir($dh);
              }
            }
            return response()->json($fileList);
        }
        elseif ($request->has('request') && $request->all()['request'] == 'delete') {
            $contract_id = decrypt($request->all()['contract_id']);
            $enrollment_number =Contract::find($contract_id);
            $path = storage_path().'/app/public/contractdocuments/'.$enrollment_number->enrollment_number.'/'.$request->all()['name'];
            // $path = 'storage/contractdocuments/'
            // dd($path);
            // Storage::delete($path);
            unlink($path);
        }
        else{
            $contract_id = decrypt($request->contract_id_fee_document);
            $enrollment_number =Contract::find($contract_id);
            $path = 'contractdocuments/'.$enrollment_number->enrollment_number;
            if(!Storage::exists($path)){
                File::makeDirectory($path, 0775, true, true);
            }
            $file = $request->file('file');
            $filetext = $request->file('file')->getClientOriginalName();
            $filename = pathinfo($filetext, PATHINFO_FILENAME);
            $extension = pathinfo($filetext, PATHINFO_EXTENSION);
            $fileToSave =  (str_replace(' ', '', $request->contract_id_fee)).'_'.(str_replace(' ', '', $filename)).'_1.'. $extension;
            $file->storeAs($path,$fileToSave, ['disk' => 'public']);

            return response()->json(['success'=>'Archivo subido correctamente']);  
        }
    }  

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DocumentUpload  $documentUpload
     * @return \Illuminate\Http\Response
     */
    public  function dropzoneLiqStore(Request $request)  
    {  
        // dd(decrypt($request->contract_id));
        if($request->has('request') && $request->all()['request'] == 'fetch'){
            $agent_liq_doc = decrypt($request->agent_liq_doc);
            $url = 'storage/liquidationdocuments/'.$agent_liq_doc.'/';
            $fileList = [];
  
            $dir = public_path($url);
            if (is_dir($dir)){
              if ($dh = opendir($dir)){
                while (($file = readdir($dh)) !== false){
                  if($file != '' && $file != '.' && $file != '..'){
                    $file_path = $url.$file;
                    if(!is_dir($file_path)){
                       $size = filesize($file_path);
                       $fileList[] = ['name'=>$file, 'size'=>$size, 'path'=>$file_path];
                    }
                  }
                }
                closedir($dh);
              }
            }
            return response()->json($fileList);
        }
        elseif ($request->has('request') && $request->all()['request'] == 'delete') {
            $agent_liq_doc = decrypt($request->agent_liq_doc);
            $path = storage_path().'/app/public/liquidationdocuments/'.$agent_liq_doc.'/'.$request->all()['name'];
            // $path = 'storage/contractdocuments/'
            // dd($path);
            // Storage::delete($path);
            unlink($path);
        }
        else{
            $agent_liq_doc = decrypt($request->dataliq_agent_document);
            $path = 'liquidationdocuments/'.$agent_liq_doc.'/';
            if(!Storage::exists($path)){
                File::makeDirectory($path, 0775, true, true);
            }
            $file = $request->file('file');
            $filetext = $request->file('file')->getClientOriginalName();
            $filename = pathinfo($filetext, PATHINFO_FILENAME);
            $extension = pathinfo($filetext, PATHINFO_EXTENSION);
            $fileToSave =  (str_replace(' ', '', $agent_liq_doc)).'_'.(str_replace(' ', '', $filename)).'.'. $extension;
            $file->storeAs($path,$fileToSave, ['disk' => 'public']);

            return response()->json(['success'=>'Archivo subido correctamente']);  
        }
    }  

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DocumentUpload  $documentUpload
     * @return \Illuminate\Http\Response
     */
    public function edit(DocumentUpload $documentUpload)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DocumentUpload  $documentUpload
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DocumentUpload $documentUpload)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DocumentUpload  $documentUpload
     * @return \Illuminate\Http\Response
     */
    public function destroy(DocumentUpload $documentUpload)
    {
        //
    }
}
