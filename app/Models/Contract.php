<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;
    protected $fillable = ['enrollment_number', 'dt_created','dt_drop', 'drop_path','drop_reason_id',
                            'lead_id', 'person_id','payer_id', 'company_id',
                            'course_id', 'aux_course_id', 'material_id', 'contract_type_id', 'study_level',
                            'contract_modality_id', 'contract_payment_type_id', 'observations','management_observations'
                            ,'financial_observations',
                            'enroll', 'discount_voucher','fundae','bank_cab', 'sepa_path','id_path','bill_path','payment_completion', 
                            's_payment', 'pp_initial_payment', 'pp_fee_quantity',
                            'pp_fee_value', 'pp_enroll_payment', 'pp_dt_first_payment', 
                            'pp_dt_final_payment','m_payment',
                            'contract_method_type_id','dt_postpone_enroll_start','dt_postpone_enroll_end','enroll_postpone_start','enroll_postpone_end',
                            'cc_number','cc_secret_code','cc_expiry','cc_name',
                            'dd_holder_dni','dd_holder_name','dd_holder_address','dd_iban','dd_bank_name',
                            't_payment_concept','t_iban','internship_dt_enroll',
                            'cash','duration','educational_center_id','internship_studies_status_id','agent_id','request_province_id','dt_course_completion',
                            'dt_approved','dt_paid','client_ip','comercial_ip','contract_status_id','validity','accept_communications',
                            'payer_ip','dt_approved_payer','dt_opening','dt_opening_payer','signature_path','signature_path_payer',
                            'accept_thirdp_com','accept_sepa','enroll_mail','created_at','updated_at','user_classroom','pass_classroom','teacher_id']; 
}
