<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    CertificateController,
    ContractFeeController,
    CourseController,
    QuoteController,
    ReportController,
    DocumentUploadController,
    HomeController,
    LeadNoteController,
    ContractStatusController,
    LeadController,
    LeadOriginController,
    LeadStatusController,
    BankController,
    PersonInfController,
    ContractController,
    CompanyController,
    UserController,
    TermController,
    LiquidationLogController,
    EventMasterController,
    LiquidationController,
    ManagementNoteController,
    LiquidationModelController,
    MyProfileController,
    LeadSubStatusController,
    TeacherController,
    EventMasterAdminController,
    FinancialGraphController,
    CourseAreaController,
    OrderController,
    OrderNoteController,
    SpreadsheetController,
    WhatsappTemplateController
};
use App\Models\{LeadSubStatus, LeadStatus};
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
Auth::routes(['register' => false]);

Route::get('/', [HomeController::class, 'index'])->middleware('auth')->name('home');

Route::get('/home', [HomeController::class, 'index'])->middleware('auth')->name('home');

Auth::routes();

/*
|--------------------------------------------------------------------------
| Rutas Clientes
|--------------------------------------------------------------------------
*/
Route::resource('leadcrud', LeadController::class)->middleware('authenticated');
Route::get('/sendDossier/{leadid}', [LeadController::class, 'sendDossier'])->name('lead.senddossier');
Route::get('/sendDossierMatBonf/{leadid}', [LeadController::class, 'sendDossierMatBonf'])->name('lead.senddossiermatbonf');
Route::resource('leadnotecrud', LeadNoteController::class)->except([
    'index'
]);
Route::post('/leads/importExcel', 'LeadController@importExcel')->name('lead.importexcel');
Route::get('/leadsmassdelete', 'LeadController@massremove')->name('leads.massremove');
Route::post('/leadcrudmassassign', [LeadController::class, 'massassign'])->name('leadcrud.massassign');
Route::get('/leads/notes/{id}', [LeadNoteController::class, 'index'])->name('leadnotes.index');
Route::get('/leads/notes/edit/{id}', [LeadNoteController::class, 'edit'])->name('leadnotes.edit');
Route::get('/leads/notes', [LeadNoteController::class, 'story'])->name('leadnotes.story');

Route::get('api/substatus', function () {
    $input = request()->option;
    if (is_null($input)) {
        return array();
    }
    $sub_status = LeadSubStatus::WhereIn('lead_status_id', $input)->orderBy('name', 'desc');
    // dd($sub_status->get(array('id','name')));
    return Response::make($sub_status->get(array('id', 'name')));
});

/*
|--------------------------------------------------------------------------
|  Rutas Origen
|--------------------------------------------------------------------------
*/
Route::resource('origin', LeadOriginController::class)->middleware('authenticated');
Route::post('/originmassdelete', 'LeadOriginController@cancelOrigen')->name('origin.cancel');

/*
|--------------------------------------------------------------------------
|  Estados de LEAD
|--------------------------------------------------------------------------
*/
Route::resource('status', LeadStatusController::class)->middleware('authenticated');
Route::get('/statusmassdelete', 'LeadStatusController@massremove')->name('status.massremove');

/*
|--------------------------------------------------------------------------
|  Estados de CONTRACTS
|--------------------------------------------------------------------------
*/
Route::resource('contractstatus', ContractStatusController::class)->middleware('authenticated');
Route::get('/contractstatusmassdelete', 'ContractStatusController@massremove')->name('contractstatus.massremove');

/*
|--------------------------------------------------------------------------
|  Quotes Manager
|--------------------------------------------------------------------------
*/
Route::resource('quotemanager', QuoteController::class)->middleware('authenticated');
Route::get('/quotemanagermassdelete', 'QuoteController@massremove')->name('quotemanager.massremove');

/*
|--------------------------------------------------------------------------
|  PeopleInf
|--------------------------------------------------------------------------
*/
Route::resource('peopleinfcrud', PersonInfController::class)->middleware('authenticated');
Route::get('/peopleinfcrudmassdelete', 'QuoteController@massremove')->name('peopleinfcrud.massremove');

/*
|--------------------------------------------------------------------------
|  Rutas Bancos 
|--------------------------------------------------------------------------
*/
Route::resource('bank', BankController::class)->middleware('authenticated');
Route::post('/bankmassdelete', 'BankController@cancelBank')->name('bank.cancel');

/*
|--------------------------------------------------------------------------
|  Rutas Alumnos Profesor
|--------------------------------------------------------------------------
*/
Route::resource('tutoringcrud', TeacherController::class)->middleware('authenticated');

/*
|--------------------------------------------------------------------------
|  Rutas Contratos
|--------------------------------------------------------------------------
*/
Route::resource('contractcrud', ContractController::class)->middleware('authenticated');
Route::get('/contractcrudmassdelete', 'ContractController@massremove')->name('contractcrud.massremove');
Route::post('/contractcrudmassassign', 'ContractController@massassign')->name('contractcrud.massassign');
Route::get('/casegeneratecontract', [ContractController::class, 'generateContract'])->name('case.generate');
Route::post('/acceptcontract', [ContractController::class, 'acceptContract'])->name('contract.accept');
Route::post('/opencontract', [ContractController::class, 'openingContract'])->name('contract.open');
Route::get('/sendContract/{contractid}', [ContractController::class, 'sendEmail'])->name('case.email');
Route::get('/generatecopy/{contractid}', [ContractController::class, 'generateContractPDF'])->name('contract.downloadPDF');
Route::get('/dropcontract/{contractid}', [ContractController::class, 'dropContract'])->name('contract.drop');
Route::get('/reactivatelead/{contractid}', [ContractController::class, 'reActivateLead'])->name('contract.reactivatelead');
Route::get('contractdetail/{contractid}/detail', [ContractController::class, 'detail'])->name('contract.detail');
/* Notas de Gestión */
Route::resource('managementnotecrud', ManagementNoteController::class)->except(['index'])->middleware('authenticated');
Route::get('/contracts/managementnotes/{id}', [ManagementNoteController::class, 'index'])->name('managementnotes.index');
Route::get('/contracts/managementnotes/edit/{id}', [ManagementNoteController::class, 'edit'])->name('managementnotes.edit');
/*  Prácticas */
Route::get('contractinternshipscrud', [ContractController::class, 'internships'])->name('contractintership.index');
Route::post('/cancelcontractinternship', [ContractController::class, 'cancelContractInternship'])->name('contractinternship.cancel');
/**Actualizar usuario y contraseña */
Route::post('/contractupdateuserroom', [ContractController::class, 'updateUserRoom'])->name('contract.updateuserroom');
Route::post('/contractupdatepassroom', [ContractController::class, 'updatePassRoom'])->name('contract.updatepassroom');
Route::post('/contractupdateteacher', [ContractController::class, 'updateTeacher'])->name('contract.updateteacher');

/*
|--------------------------------------------------------------------------
|  Rutas Financiero / Liquidaciones
|--------------------------------------------------------------------------
*/
Route::resource('liquidationcrud', LiquidationController::class)->middleware('authenticated');
Route::get('/generateliquidation', [LiquidationController::class, 'generateLiquidation'])->name('liquidation.generate');
Route::post('/closeliquidation', [LiquidationController::class, 'closeLiquidation'])->name('liquidation.close');
Route::post('/openliquidation', [LiquidationController::class, 'openLiquidation'])->name('liquidation.open');
Route::post('/sendliquidation', [LiquidationController::class, 'sendLiquidation'])->name('liquidation.send');
Route::post('/acceptliquidation', [LiquidationController::class, 'acceptLiquidation'])->name('liquidation.accept');
Route::post('/liquidationbonification', [LiquidationController::class, 'updateBonification'])->name('liquidation.updatebonification');
Route::post('/liquidationegretion', [LiquidationController::class, 'updateEgretion'])->name('liquidation.updateegretion');
/*  Historico liquidaciones */
Route::resource('liquidationcrudlog', LiquidationLogController::class)->middleware('authenticated');


/*
|--------------------------------------------------------------------------
|  Rutas Expedientes / Cobros
|--------------------------------------------------------------------------
*/
Route::resource('contractfeecrud', ContractFeeController::class)->middleware('authenticated');
Route::get('/contracts/fee/{contractid}', [ContractFeeController::class, 'viewFeePayments'])->name('contractfeecrud.viewFeePayments');
/*
/*
|--------------------------------------------------------------------------
|  Rutas Modelo de Liquidaciones
|--------------------------------------------------------------------------
*/
Route::resource('liquidationmodel', LiquidationModelController::class)->middleware('authenticated');
Route::post('/liquidationmodelmassdelete', 'LiquidationModelController@cancelLiquidationmodel')->name('liquidationmodel.cancel');
/*
|--------------------------------------------------------------------------
|  Rutas Empresas
|--------------------------------------------------------------------------
*/
Route::resource('company', CompanyController::class)->middleware('authenticated');
Route::post('/companymassdelete', 'CompanyController@cancelCompany')->name('company.cancel');
Route::get('/company/create', [CompanyController::class, 'create'])->name('company.create');

/*
|--------------------------------------------------------------------------
|  Rutas Usuarios
|--------------------------------------------------------------------------
*/
Route::resource('user', UserController::class)->middleware('authenticated');
Route::get('/usermassdelete', 'UserController@cancelUser')->name('user.cancel');
Route::post('user', [UserController::class, 'store'])->name('user.store');

/*
|--------------------------------------------------------------------------
|  Rutas Order
|--------------------------------------------------------------------------
*/

Route::resource('order', OrderController::class);
Route::get('order', [OrderController::class, 'store'])->name('order.store');
Route::get('orderfind/{id}', [OrderController::class, 'find'])->name('order.find');
Route::get('orderfind', [OrderController::class, 'find'])->name('order.find2');
Route::get('order', [OrderController::class, 'index'])->name('order.index');
Route::put('order/{id}', [OrderController::class, 'update'])->name('order.update');
Route::put('order', [OrderController::class, 'update'])->name('order.update2');
Route::delete('order/{id}', [OrderController::class, 'destroy'])->name('order.destroy');
Route::get('order/notes/{id}', [OrderController::class, 'notes'])->name('order.notes');
Route::get('order/notes/', [OrderController::class, 'notes'])->name('order.notes2');

/*
|--------------------------------------------------------------------------
|  Rutas OrderNote
|--------------------------------------------------------------------------
*/

Route::resource('ordernote', OrderNoteController::class);
Route::get('ordernotefind/{id}', [OrderNoteController::class, 'find'])->name('ordernotefind.find');
Route::get('ordernotefind', [OrderNoteController::class, 'find'])->name('ordernotefind.find2');
Route::put('ordernote/{id}', [OrderNoteController::class, 'update'])->name('ordernote.update');
Route::put('ordernote', [OrderNoteController::class, 'update'])->name('ordernote.update2');
Route::delete('ordernote/{id}', [OrderNoteController::class, 'destroy'])->name('ordernote.destroy');
Route::delete('ordernote', [OrderNoteController::class, 'destroy'])->name('ordernote.destroy2');




/*
|--------------------------------------------------------------------------
|  Rutas Profile
|--------------------------------------------------------------------------
*/
Route::resource('profile', MyProfileController::class)->middleware('authenticated');
Route::post('profile', [MyProfileController::class, 'storeNShow'])->name('profile.store')->middleware('authenticated');

/*
|--------------------------------------------------------------------------
|  Rutas Cursos
|--------------------------------------------------------------------------
*/
Route::resource('coursecrud', CourseController::class)->middleware('authenticated');
Route::post('/coursemassdelete', 'CourseController@cancelCourse')->name('course.cancel');
/**Areas de Cursos */
Route::resource('courseareacrud', CourseAreaController::class)->middleware('authenticated');
/*
|--------------------------------------------------------------------------
|  Rutas Certificados
|--------------------------------------------------------------------------
*/
Route::resource('certificatecrud', CertificateController::class)->middleware('authenticated');
Route::get('/generatecertificate/{certid}', [CertificateController::class, 'generateCertificatePDF'])->name('certificate.downloadPDF');
Route::post('/certificatemassdelete', 'CertificateController@cancelCourse')->name('certificate.cancel');
/* Enviar Certificado */
Route::get('/sendCert/{certid}', [CertificateController::class, 'sendCert'])->name('certificate.sendcert');
/*
|--------------------------------------------------------------------------
|  Rutas Reports
|--------------------------------------------------------------------------
*/
//Reportes
Route::resource('reports', ReportController::class);
Route::post('/reportsgenerate', [ReportController::class, 'export'])->name('reports.generate');
/*
|--------------------------------------------------------------------------
|  Rutas Terminos y Condiciones
|--------------------------------------------------------------------------
*/
Route::resource('termscrud', TermController::class)->middleware('authenticated');
Route::post('/termscrudmassdelete', 'TermController@cancelService')->name('termscrud.cancel');
/*/*
|--------------------------------------------------------------------------
|  Rutas Plantillas de Whatsapp
|--------------------------------------------------------------------------
*/
Route::resource('whatsappcrud', WhatsappTemplateController::class)->middleware('authenticated');
Route::post('/whatsappcrudcrudmassdelete', 'WhatsappTemplateController@cancel')->name('whatsappcrud.cancel');
/*
|--------------------------------------------------------------------------
|  Rutas Subir Documentación
|--------------------------------------------------------------------------
*/
Route::get('dropzone', [DocumentUploadController::class, 'dropzone']);
Route::post('dropzone/store', [DocumentUploadController::class, 'dropzoneStore'])->name('dropzone.store');
Route::post('dropzoneliq/store', [DocumentUploadController::class, 'dropzoneLiqStore'])->name('dropzoneliq.store');
Route::get('/dropzoneview', function () {
    return view('cases.modals.case_documents_upload')->render();
});



/*
|--------------------------------------------------------------------------
|  Rutas Calendario
|--------------------------------------------------------------------------
*/
Route::get('/eventmaster', [EventMasterController::class, 'index']);
Route::get('/eventmastercontract', [EventMasterController::class, 'contract']);
Route::post('/eventmaster/create', [EventMasterController::class, 'create'])->name('eventmaster.create');
Route::post('/eventmaster/update', [EventMasterController::class, 'update'])->name('eventmaster.update');
Route::post('/eventmaster/delete', [EventMasterController::class, 'destroy'])->name('eventmaster.delete');
Route::post('/eventmaster/list', [EventMasterController::class, 'getEventsByAgent'])->name('eventmaster.list');
Route::delete('/eventmaster/delete/{id}', [EventMasterController::class, 'destroy'])->name('eventmaster.destroy');
Route::get('/appointments', function () {
    return view('appointment.index');
})->name('appointments')->middleware('auth');

/*
|--------------------------------------------------------------------------
|  Rutas Calendario del Admin
|--------------------------------------------------------------------------
*/
Route::get('/eventmasteradmin', [EventMasterAdminController::class, 'index']);
Route::post('/eventmasteradmin/create', [EventMasterAdminController::class, 'create'])->name('eventmasteradmin.create');
Route::post('/eventmasteradmin/update', [EventMasterAdminController::class, 'update'])->name('eventmasteradmin.update');
Route::post('/eventmasteradmin/delete', [EventMasterAdminController::class, 'destroy'])->name('eventmasteradmin.delete');
Route::delete('/eventmasteradmin/delete/{id}', [EventMasterAdminController::class, 'destroy'])->name('eventmasteradmin.destroy');
Route::get('/appointmentsadmin', function () {
    $pageTitle = 'Calendario Administrativo  - GrupoeFP';
    return view('appointmentadmin.index', compact('pageTitle'));
})->name('appointmentsadmin')->middleware('auth');

/*
|--------------------------------------------------------------------------
|  Rutas Estadísticas y Comparativas
|--------------------------------------------------------------------------
*/
Route::resource('financialgraph', FinancialGraphController::class)->middleware('authenticated');
Route::post('/financialgraphsellsgraph', [FinancialGraphController::class, 'sellsGraph'])->name('financialgraph.sellsgraph');
Route::post('/financialgraphcontgrah', [FinancialGraphController::class, 'contGraph'])->name('financialgraph.contgraph');

/*
|--------------------------------------------------------------------------
|  Rutas Hoja Contable
|--------------------------------------------------------------------------
*/
Route::resource('spreadsheet', SpreadsheetController::class)->middleware('authenticated');

Route::get('/cleanall', function () {
    //Clear Route cache:
    $routeClearRoute = Artisan::call('route:clear');
    //Clear View cache:
    $routeClearView = Artisan::call('view:clear');
    //Clear Config cache:
    $routeClearConfig = Artisan::call('config:cache');
    if ($routeClearRoute == 0 && $routeClearView == 0 && $routeClearConfig == 0) {
        return '<h1>Clean complete <a href="javascript:history.back()" targe="_self">go back</a></h1>';
    } else {
        return '<h1>Clean Not complete</h1>';
    }
});
