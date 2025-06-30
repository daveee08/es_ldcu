<?php

namespace App\Http\Controllers\HRControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Crypt;
use PDF;
class SetupController extends Controller
{
    public function requirementssetup($id,Request $request)
    {

        date_default_timezone_set('Asia/Manila');

        if($id == 'dashboard'){

            $gettypes = Db::table('employee_credentialtypes')
                ->where('deleted','0')
                ->get();

            return view('hr.settings.settingscredentialtypes')
                ->with('types', $gettypes);

        }
        elseif($id == 'addnew'){

            $createdby = DB::table('teacher')
                ->where('userid', auth()->user()->id)
                ->first();

            foreach($request->get('description') as $newcredential){

                $checkifExists = Db::table('employee_credentialtypes')
                    ->where('description','like','%'.$newcredential)
                    ->where('deleted','0')
                    ->get();
                
                if(count($checkifExists) == 0){

                    DB::table('employee_credentialtypes')
                        ->insert([
                            'description'       => strtoupper($newcredential),
                            'createdby'         => $createdby->id,
                            'createddatetime'   => date('Y-m-d H:i:s')
                        ]);

                }

            }

            return back();

        }
        else{

            if(Crypt::decrypt($id) == 'edit'){

                // return $request->all();
                DB::table('employee_credentialtypes')
                    ->where('id',$request->get('typeid'))
                    ->update([
                        'description'   => $request->get('description')
                    ]);

                return back();
    
            }
            elseif(Crypt::decrypt($id) == 'delete'){

                DB::table('employee_credentialtypes')
                    ->where('id',$request->get('typeid'))
                    ->update([
                        'deleted'   => '1'
                    ]);

                return back();

            }

        }


    }

    public function getDepartments(Request $request){
        $id = $request->get('id');

        if($id){
            $departments = DB::table('hr_departments')
                ->leftJoin('hr_departmentheads','hr_departments.id','=','hr_departmentheads.deptid')
                ->leftJoin('teacher','hr_departmentheads.deptheadid','=','teacher.id')
                ->where('hr_departments.deleted','0')
                ->where('hr_departments.id',$id)
                ->select(
                    'hr_departments.department', 
                    'hr_departments.id',
                    DB::raw('CONCAT(teacher.firstname, IFNULL(CONCAT(" ", teacher.middlename), ""), " ", teacher.lastname) as headname')

                    )
                ->get();
        }else{
            $departments = DB::table('hr_departments')
                ->leftJoin('hr_departmentheads','hr_departments.id','=','hr_departmentheads.deptid')
                ->leftJoin('teacher','hr_departmentheads.deptheadid','=','teacher.id')
                ->where('hr_departments.deleted','0')
                ->select(
                    'hr_departments.department',
                    'hr_departments.id',
                    DB::raw('CONCAT(teacher.firstname, IFNULL(CONCAT(" ", teacher.middlename), ""), " ", teacher.lastname) as headname')
                    )
                ->get();
        }
        
        
        return $departments;

    }   

    public function addDepartment(Request $request){
        $department = $request->get('department');
        $departmenthead = $request->get('departmenthead');

        $checkifexists = DB::table('hr_departments')
                ->where('department','like','%'.$department.'%')
                ->where('deleted','0')
                ->get();
                
        if(count($checkifexists) == 0)
        {
            $deptid = DB::table('hr_departments')
                ->insertGetId([
                    'department'        => $department,
                    'createdby'         => auth()->user()->id,
                    'createddatetime'   => \Carbon\Carbon::now('Asia/Manila')
                ]);

            if($departmenthead !=null)
            {
                DB::table('hr_departmentheads')
                        ->insert([
                            'deptid'            => $deptid,
                            'deptheadid'        => $request->get('employeeid'),
                            'createdby'         => auth()->user()->id,
                            'createddatetime'   => \Carbon\Carbon::now('Asia/Manila')   
                        ]);
            }

            
        }else{
            return 'exists';
        }
    }

    public function updateDepartment(Request $request){
        $id = $request->get('id');
        $department = $request->get('department');
        $departmenthead = $request->get('departmenthead');

         $checkifexists = DB::table('hr_departments')
                ->where('department','like','%'.$department.'%')
                ->where('id', '!=', $id)
                ->where('deleted', 0)
                ->get();
        dd($checkifexists);
        if(count($checkifexists) == 0)
        {
            DB::table('hr_departments')
                    ->where('id', $id)
                    ->update([
                        'department' => $department,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                    ]);
            $checkifexists = DB::table('hr_departmentheads')
                    ->where('deptid', $id)
                    ->where('deleted','0')
                    ->get();

            if($departmenthead != null){
                if(count($checkifexists) == 0){

                    DB::table('hr_departmentheads')
                            ->insert([
                                'deptid'            => $id,
                                'deptheadid'        => $departmenthead,
                                'createdby'         => auth()->user()->id,
                                'createddatetime'   => \Carbon\Carbon::now('Asia/Manila')   
                            ]);

                }else{
                    DB::table('hr_departmentheads')
                        ->where('deptid', $id)
                        ->update([
                            'deptheadid' => $departmenthead,
                            'updatedby' => auth()->user()->id,
                            'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);
                }
            }
        }else{
            return 'exists';
        }
       

    }

    public function deleteDepartment(Request $request){
        $id = $request->get('id');
        
        $checkifused = DB::table('hr_offices')
                    ->where('deptid', $id)
                    ->where('deleted','0')
                    ->get();

        if(count($checkifused) > 0){
            return 'used';
        }else{
            DB::table('hr_departmentheads')
                ->where('deptid', $id)
                ->update([
                    'deleted' => 1,
                    'deletedby' => auth()->user()->id,
                    'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
            ]);

            DB::table('hr_departments')
                    ->where('id', $id)
                    ->update([
                        'deleted' => 1,
                        'deletedby' => auth()->user()->id,
                        'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
            ]);
        }
        
        
           
    }

    public function addOffice(Request $request)
    {
        $office_name = $request->get('office_name');
        $office_head = $request->get('office_head');
        $department = $request->get('department');

        $checkifexists = DB::table('hr_offices')
                ->where('officename','like','%'.$office_name.'%')
                ->where('deptid',$department)
                ->where('deleted','0')
                ->get();

        if(count($checkifexists) == 0){
            $officeid = DB::table('hr_offices')
                ->insertGetId([
                    'officename'        => $office_name,
                    'deptid'            => $department,
                    'createdby'         => auth()->user()->id,
                    'createddatetime'   => \Carbon\Carbon::now('Asia/Manila')
                ]);

            if($office_head !=null)
            {
                DB::table('hr_officesemp')
                    ->insert([
                        'employeeid'        => $office_head,
                        'officeid'          => $officeid,
                        'createdby'         => auth()->user()->id,
                        'createddatetime'   => \Carbon\Carbon::now('Asia/Manila')   
                    ]);
            }
        }else{
            return 'exists';
        }

    }

    public function getOffices(Request $request){
        $id = $request->get('id');

        if($id){
            $offices = DB::table('hr_offices')
                ->leftJoin('hr_officesemp','hr_offices.id','=','hr_officesemp.officeid')
                ->leftJoin('teacher','hr_officesemp.employeeid','=','teacher.id')
                ->join('hr_departments','hr_offices.deptid','=','hr_departments.id')
                ->where('hr_offices.deleted','0')
                ->where('hr_offices.id',$id)
                ->select(
                    'hr_offices.officename', 
                    'hr_offices.id',
                    'hr_departments.id as departmentid',
                    DB::raw('CONCAT(teacher.firstname, IFNULL(CONCAT(" ", teacher.middlename), ""), " ", teacher.lastname) as headname')
                    )
                ->get();
        }else{
             $offices = DB::table('hr_offices')
                ->leftJoin('hr_officesemp','hr_offices.id','=','hr_officesemp.officeid')
                ->leftJoin('teacher','hr_officesemp.employeeid','=','teacher.id')
                ->join('hr_departments','hr_offices.deptid','=','hr_departments.id')
                ->where('hr_offices.deleted','0')
                ->select(
                    'hr_offices.officename', 
                    'hr_offices.id',
                    'hr_departments.id as departmentid',
                    'hr_departments.department',
                    DB::raw('CONCAT(teacher.firstname, IFNULL(CONCAT(" ", teacher.middlename), ""), " ", teacher.lastname) as headname')
                    )
                ->get();
        }
        
        
        return $offices;
    }

    public function updateOffice(Request $request){
        $id = $request->get('id');
        $office_name = $request->get('office_name');
        $office_head = $request->get('office_head');
        $department = $request->get('department');

        $checkifexists = DB::table('hr_offices')
                ->where('officename','like','%'.$department.'%')
                ->where('id', '!=', $id)
                ->where('deptid', $department)
                ->where('deleted', 0)
                ->get();
        if(count($checkifexists) == 0)
        {
            DB::table('hr_offices')
                    ->where('id', $id)
                    ->update([
                        'officename' => $office_name,
                        'deptid' => $department,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                    ]);
            $checkifexists = DB::table('hr_officesemp')
                    ->where('officeid', $id)
                    ->where('deleted','0')
                    ->get();

            if($office_head != null){
                if(count($checkifexists) == 0){

                    DB::table('hr_officesemp')
                            ->insert([
                                'officeid'          => $id,
                                'employeeid'        => $office_head,
                                'createdby'         => auth()->user()->id,
                                'createddatetime'   => \Carbon\Carbon::now('Asia/Manila')   
                            ]);

                }else{
                    DB::table('hr_officesemp')
                        ->where('officeid', $id)
                        ->update([
                            'employeeid' => $office_head,
                            'updatedby' => auth()->user()->id,
                            'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);
                }
            }
        }else{
            return 'exists';
        }
       

    }

    public function deleteOffice(Request $request){
        $id = $request->get('id');

        DB::table('hr_officesemp')
            ->where('officeid', $id)
            ->update([
                'deleted' => 1,
                'deletedby' => auth()->user()->id,
                'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
            ]);

        DB::table('hr_offices')
            ->where('id', $id)
            ->update([
                'deleted' => 1,
                'deletedby' => auth()->user()->id,
                'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
            ]);
    }

    public function addDesignation(Request $request){
        $designation = $request->get('designation');

        $checkifexists = DB::table('usertype')
                ->where('utype','like','%'.$designation.'%')
                ->where('deleted',0)
                ->get();
                
        if(count($checkifexists) == 0)
        {
            $usertypeid = DB::table('usertype')
                ->insertGetId([
                    'utype'             => strtoupper($designation),
                    'created_by'        => auth()->user()->id,
                    'created_on'        => \Carbon\Carbon::now('Asia/Manila'),
                ]);

            // DB::table('hr_designation')
            //     ->insert([
            //         'designation'       => strtoupper($designation),
            //         'created_by'        => auth()->user()->id,
            //         'created_on'        => \Carbon\Carbon::now('Asia/Manila'),
            //         'usertypeid'        => $usertypeid
            //     ]);
        }else{
            return 'exists';
        }
    }

    public function getDesignation(Request $request){
        $id = $request->get('id');

        if($id){
            // $designations = DB::table('hr_designation')
            //     ->join('users','hr_designation.created_by','=','users.id')
            //     ->where('hr_designation.id',$id)
            //     ->select(
            //         'hr_designation.designation', 
            //         'hr_designation.id',
            //         'users.name'
            //         )
            //     ->get();

            $designations = DB::table('usertype')
                ->leftjoin('users','usertype.created_by','=','users.id')
                ->where('usertype.deleted','0')
                ->where('usertype.id',$id)
                ->select(
                    'utype as designation',
                    'usertype.id',
                    'users.name',
                )
                ->get();

        }else{
            // $designations = DB::table('hr_designation')
            //     ->join('users','hr_designation.created_by','=','users.id')
            //     ->whereNull('hr_designation.deleted')
            //     ->select(
            //         'hr_designation.designation', 
            //         'hr_designation.id',
            //         'users.name'
            //         )
            //     ->get();

            $designations = DB::table('usertype')
                ->leftjoin('users','usertype.created_by','=','users.id')
                ->leftjoin('users as assigned','usertype.id','=','assigned.type')
                ->where('usertype.deleted','0')
                ->select(
                    'utype as designation',
                    'usertype.id',
                    'users.name',
                    'usertype.constant',
                    'assigned.id as assigned',
                )
                ->groupBy('usertype.id')
                ->get();
        }

        return $designations;
    }

    public function updateDesignation(Request $request){
        $id = $request->get('id');
        $designation = $request->get('designation');

        $checkifexists = DB::table('usertype')
                ->where('utype','like','%'.$designation.'%')
                ->where('id', '!=', $id)
                ->whereNull('deleted')
                ->get();

        if(count($checkifexists) == 0)
        {
            DB::table('usertype')
                ->where('id', $id)
                ->update([
                    'utype' => $designation,
                    'updated_by' => auth()->user()->id,
                    'updated_on' => \Carbon\Carbon::now('Asia/Manila')
                ]);
        }else{
            return 'exists';
        }

    }

    public function deleteDesignation(Request $request){
        $id = $request->get('id');

        DB::table('usertype')
            ->where('id', $id)
            ->update([
                'deleted' => 1,
            ]);


    }

    public function addRequirement(Request $request){
        $requirement = $request->get('requirement');

        $checkifexists = DB::table('hr_requirements')
                ->where('requirement_name','like','%'.$requirement.'%')
                ->where('deleted','0')
                ->get();

        if(count($checkifexists) == 0)
        {
            DB::table('hr_requirements')
                ->insert([
                    'requirement_name'       => $requirement,
                    'createdby'        => auth()->user()->id,
                    'created_at'        => \Carbon\Carbon::now('Asia/Manila'),
                ]);
        }else{
            return 'exists';
        }
    }

    public function getRequirement(Request $request){
        $id = $request->get('id');

        if($id){
            $requirements = DB::table('hr_requirements')
                ->leftjoin('users','hr_requirements.createdby','=','users.id')
                ->where('hr_requirements.deleted','0')
                ->where('hr_requirements.id',$id)
                ->select(
                    'requirement_name', 
                    'hr_requirements.id',
                    'users.name'
                    )
                ->get();
        }else{
             $requirements = DB::table('hr_requirements')
                ->leftjoin('users','hr_requirements.createdby','=','users.id')
                ->where('hr_requirements.deleted','0')
                ->select(
                    'requirement_name', 
                    'hr_requirements.id',
                    'users.name'
                    )
                ->get();
        }
        
        
        return $requirements;
    }

    public function updateRequirement(Request $request){
        $id = $request->get('id');
        $requirement = $request->get('requirement');

        $checkifexists = DB::table('hr_requirements')
                ->where('requirement_name','like','%'.$requirement.'%')
                ->where('id', '!=', $id)
                ->where('deleted', 0)
                ->get();

        if(count($checkifexists) == 0)
        {
            DB::table('hr_requirements')
                ->where('id', $id)
                ->update([
                    'requirement_name' => $requirement,
                ]);
        }else{
            return 'exists';
        }

    }

    public function deleteRequirement(Request $request){
        $id = $request->get('id');

        DB::table('hr_requirements')
            ->where('id', $id)
            ->update([
                'deleted' => 1,
            ]);


    }

    public function officessetup($action, Request $request)
    {
        
        date_default_timezone_set('Asia/Manila');

        $my_id = DB::table('teacher')
            ->select('id')
            ->where('userid',auth()->user()->id)
            ->where('isactive','1')
            ->first();

        if($request->has('syid'))
        {
            $syid = $request->get('syid');

        }else{
            $syid = DB::table('sy')
                ->where('isactive','1')
                ->first()->id;
        }

        if($action == 'dashboard'){

            $departments = Db::table('hr_school_department')
                ->select(
                    'hr_school_department.*',
                    'teacher.lastname',
                    'teacher.firstname',
                    'teacher.middlename',
                    'teacher.suffix'
                )
                ->leftJoin('teacher','hr_school_department.created_by','=','teacher.userid')
                ->where('hr_school_department.deleted','0')
                ->orderByDesc('created_on')
                ->get();
            
            return view('hr.settings.offices')
                ->with('departments',$departments);

        }
        elseif($action == 'index'){
            $offices = Db::table('hr_offices')
                ->where('syid',$syid)
                ->where('deleted','0')
                ->get();
            return view('hr.settings.offices.index')
                ->with('syid',$syid)
                ->with('offices',$offices);
        }
        elseif($action == 'getemployees'){

            $employees = DB::table('hr_officesemp')
                ->select('hr_officesemp.*','teacher.firstname','teacher.middlename','teacher.lastname','teacher.suffix','teacher.picurl','teacher.usertypeid','employee_personalinfo.gender')
                ->join('teacher','hr_officesemp.employeeid','=','teacher.id')
                ->leftJoin('employee_personalinfo','teacher.id','=','employee_personalinfo.employeeid')
                ->where('officeid', $request->get('id'))
                ->where('hr_officesemp.deleted','0')
                ->get();

            if(count($employees)>0)
            {
                foreach($employees as $employee)
                {
                    if($employee->presposition == null)
                    {
                        $presposition = DB::table('usertype')
                            ->where('id', $employee->usertypeid)
                            ->first();

                        if($presposition)
                        {
                            $employee->presposition = $presposition->utype;
                        }else{
                            $employee->presposition = null;
                        }
                    }
                }
            }

            $unassigned = DB::table('teacher')
                ->where('deleted','0')
                ->where('isactive','1')
                ->get();

            $officeinfo = DB::table('hr_offices')
                ->where('id',$request->get('id'))
                ->first();

            return view('hr.settings.offices.employees')
                ->with('officeid',$request->get('id'))
                ->with('officeinfo',$officeinfo)
                ->with('employees',$employees)
                ->with('unassigned',$unassigned);
        }
        elseif($action == 'addpersonnel')
        {
            $employeeids = json_decode($request->get('personnels'));
            foreach($employeeids as $employeeid)
            {
                $checkifexists = DB::table('hr_officesemp')
                    ->where('employeeid', $employeeid)
                    ->where('officeid',$request->get('officeid'))
                    ->where('deleted','0')
                    ->first();
                
                if(!$checkifexists)
                {
                    DB::table('hr_officesemp')
                    ->insert([
                        'employeeid'        => $employeeid,
                        'officeid'         => $request->get('officeid'),
                        'deleted'           => 0,
                        'createdby'         => auth()->user()->id,
                        'createddatetime'   => date('Y-m-d H:i:s')
                    ]);
                }

            }
            return 1;
        }
        elseif($action == 'updatepersonnel')
        {
            DB::table('hr_officesemp')
                ->where('id',$request->get('officeempid'))
                ->update([
                    'title'                 => $request->get('title'),
                    'majorin'               => $request->get('major'),
                    'degreewhere'           => $request->get('where'),
                    'ma_mba'                => $request->get('mamba'),
                    'ma_mbawhere'           => $request->get('mambawhere'),
                    'doctoratedegree'       => $request->get('doctorate'),
                    'doctoratedegreewhere'  => $request->get('doctoratewhere'),
                    'prevposition'          => $request->get('prevposition'),
                    'prevpositionexp'       => $request->get('prevpositionexp'),
                    'presposition'          => $request->get('presposition'),
                    'prespositionexp'       => $request->get('prespositionexp'),
                    'updatedby'             => auth()->user()->id,
                    'updateddatetime'       => date('Y-m-d H:i:s')
                ]);
            return 1;
        }
        elseif($action == 'addoffice')
        {
            $checkifexists = Db::table('hr_offices')
                ->where('officename','like','%'.$request->get('newoffice'))
                ->where('syid',$request->get('selectedsyid'))
                ->where('deleted','0')
                ->get();
                
            if(count($checkifexists) == 0){

                $getid = DB::table('hr_offices')
                    ->insertGetId([
                        'officename'        => $request->get('newoffice'),
                        'deleted'           => 0,
                        'syid'              => $request->get('selectedsyid'),
                        'createdby'         => auth()->user()->id,
                        'createddatetime'   => date('Y-m-d H:i:s')
                    ]);

                $info = DB::table('hr_offices')
                    ->where('id', $getid)
                    ->first();

                return collect($info);

            }else{
                return '0';
            }
            // $checkifexists = Db::table('hr_school_department')
            //     ->where('department','like','%'.$request->get('department'))
            //     ->where('deleted','0')
            //     ->get();
                
            // if(count($checkifexists) == 0){

            //     DB::table('hr_school_department')
            //         ->insert([
            //             'department'    => $request->get('department'),
            //             'deleted'       => 0,
            //             'created_by'    => auth()->user()->id,
            //             'created_on'    => date('Y-m-d H:i:s')
            //         ]);

            //     return redirect()->back()->with("messageAdded", $request->get('department').' department added successfully!');

            // }
            // else{

            //     return redirect()->back()->with("messageExists", $request->get('department').' already exists!');

            // }

        }
        elseif($action == 'updateoffice')
        {
            DB::table('hr_offices')
                ->where('id',$request->get('id'))
                ->update([
                    'officename'             => $request->get('newofficename'),
                    'updatedby'             => auth()->user()->id,
                    'updateddatetime'       => date('Y-m-d H:i:s')
                ]);
            return $request->get('newofficename');
        }
        elseif($action == 'deleteoffice')
        {
            DB::table('hr_offices')
                ->where('id',$request->get('id'))
                ->update([
                    'deleted'               => 1,
                    'deletedby'             => auth()->user()->id,
                    'deleteddatetime'       => date('Y-m-d H:i:s')
                ]);
            return $request->get('newofficename');
        }     
        elseif($action == 'exportoffices')
        {
            $offices = DB::table('hr_offices')
                ->where('syid',$request->get('syid'))
                ->where('deleted','0')
                ->get();
                
            if(count($offices)>0)
            {
                foreach($offices as $eachoffice)
                {                    
                    $employees = DB::table('hr_officesemp')
                        ->select('hr_officesemp.*','teacher.title as prefix','teacher.firstname','teacher.middlename','teacher.lastname','teacher.suffix','teacher.picurl','teacher.usertypeid','employee_personalinfo.gender')
                        ->join('teacher','hr_officesemp.employeeid','=','teacher.id')
                        ->leftJoin('employee_personalinfo','teacher.id','=','employee_personalinfo.employeeid')
                        ->where('officeid', $eachoffice->id)
                        ->where('hr_officesemp.deleted','0')
                        ->get();

                    if(count($employees)>0)
                    {
                        foreach($employees as $employee)
                        {
                            if($employee->presposition == null)
                            {
                                $presposition = DB::table('usertype')
                                    ->where('id', $employee->usertypeid)
                                    ->first();
        
                                if($presposition)
                                {
                                    $employee->presposition = $presposition->utype;
                                }else{
                                    $employee->presposition = null;
                                }
                            }
                        }
                    }
                    $eachoffice->employees = $employees;
                }
            }
            
            $pdf = PDF::loadView('hr/settings/offices/pdf_staffprofile', compact('offices','syid'));
            return $pdf->stream('Staff Profile - '.DB::table('sy')->where('id', $syid)->first()->sydesc.'.pdf'); 
        }   
        elseif($action == 'editoffice'){
            
            Db::update('update hr_school_department set department = ?, updated_by = ?, updated_on = ? where id = ?',[$request->get('department'),$my_id->id,date('Y-m-d H:i:s'),$request->get('departmentid')]);

            return redirect()->back()->with("messageEdited", $request->get('department').' department updated successfully!');

        }
        // elseif($action == 'deleteoffice'){
            
        //     Db::update('update hr_school_department set deleted = ?, updated_by = ?, updated_on = ? where id = ?',['1',$my_id->id,date('Y-m-d H:i:s'),$request->get('departmentid')]);

        //     return redirect()->back()->with("messageDeleted", $request->get('department').' department deleted successfully!');

        // }

    }
    
    public function designationssetup($action, Request $request)
    {
        date_default_timezone_set('Asia/Manila');

        $my_id = DB::table('teacher')
            ->select('id')
            ->where('userid',auth()->user()->id)
            ->where('isactive','1')
            ->first();

        if($action == 'dashboard'){

            $departments = Db::table('hr_school_department')
                ->where('deleted','0')
                ->get();

            $designations = Db::table('usertype')
                ->select(
                    'usertype.id',
                    'usertype.utype as designation',
                    'departmentid',
                    'usertype.constant',
                    'hr_school_department.department as departmentname',
                    'teacher.lastname',
                    'teacher.firstname',
                    'teacher.middlename',
                    'teacher.suffix'
                    )
                ->where('usertype.deleted','0')
                ->leftJoin('teacher','usertype.created_by','=','teacher.userid')
                ->leftJoin('hr_school_department','usertype.departmentid','=','hr_school_department.id')
                ->where('usertype.utype','!=','PARENT')
                ->where('usertype.utype','!=','STUDENT')
                ->where('usertype.utype','!=','SUPER ADMIN')
                ->get();
                
            foreach($designations as $designation){

                if($designation->departmentid == null){

                    $designation->departmentid = 0;
                    $designation->departmentname = "";

                }
                
            }
            
            return view('hr.settings.designations')
                ->with('departments',$departments)
                ->with('designations',$designations);

        }
        elseif($action == 'adddesignation'){
            
            $checkifexists = Db::table('usertype')
                ->where('utype','like','%'.$request->get('designation'))
                ->where('deleted','0')
                ->get();
                
            if(count($checkifexists) == 0){

                $refid = DB::table('usertype')
                    ->insertGetId([
                        'utype'         => strtoupper($request->get('designation')),
                        'departmentid'  => $request->get('departmentid'),
                        'constant'      => 0,
                        'deleted'       => 0,
                        'created_by'    => auth()->user()->id,
                        'created_on'    => date('Y-m-d H:i:s')
                    ]);
                
                // DB::table('usertype')
                //     ->where('id', $refid)
                //     ->update([
                //         'refid'     => $refid
                //     ]);

                return redirect()->back()->with("messageAdded", $request->get('designation').' designation added successfully!');

            }
            else{

                return redirect()->back()->with("messageExists", $request->get('designation').' already exists!');

            }

        }
        elseif($action == 'editdesignation'){
            
            DB::table('usertype')
                ->where('id',$request->get('designationid'))
                ->update([
                    'utype'             => $request->get('designation'),
                    'updated_by'        => $my_id->id,
                    'updated_on'        => date('Y-m-d H:i:s'),
                ]);
            // Db::update('update hr_designation set designation = ?, updated_by = ?, updated_on = ? where id = ?',[$request->get('designation'),$my_id->id,date('Y-m-d H:i:s'),$request->get('designationid')]);

            return redirect()->back()->with("messageEdited", $request->get('designation').' designation updated successfully!');

        }
        elseif($action == 'deletedesignation'){

            DB::table('usertype')
                ->where('id',$request->get('designationid'))
                ->update([
                    'deleted'           => '1',
                    'updated_by'        => $my_id->id,
                    'updated_on'        => date('Y-m-d H:i:s'),
                ]);
            
            // Db::update('update hr_designation set deleted = ?, updated_by = ?, updated_on = ? where id = ?',['1',$my_id->id,date('Y-m-d H:i:s'),$request->get('designationid')]);

            return redirect()->back()->with("messageDeleted", $request->get('department').' department deleted successfully!');

        }
        elseif($action == 'editdepartment'){
            // return $request->all();
            DB::table('usertype')
                ->where('id',$request->get('designationid'))
                ->update([
                    'departmentid'      => $request->get('departmentid'),
                    'updated_by'        => $my_id->id,
                    'updated_on'        => date('Y-m-d H:i:s'),
                ]);
            // Db::update('update hr_designation set departmentid = ?, updated_by = ?, updated_on = ? where id = ?',[$request->get('departmentid'),$my_id->id,date('Y-m-d H:i:s'),$request->get('designationid')]);

            return redirect()->back()->with("messageEdited", $request->get('designation')."'s department updated successfully!");

        }

    }
    public function standarddeductions($id,Request $request)
    {
        
        $id = Crypt::decrypt($id);

        if($id == 'dashboard'){
            $departments = Db::table('hr_school_department')
            ->where('deleted',0)
            ->get();

            $tardinesstype = Db::table('deduction_tardinesssetup')
                ->where('deleted','0')
                ->get();

            $tardinessdetails = Db::table('deduction_tardinessdetail')
                ->where('deduction_tardinessdetail.deleted','0')
                ->get();

            $tardinesscomputations = array();

            foreach($tardinessdetails as $tardinessdetail){
                
                $tardinessdetail->modifiedamount = number_format($tardinessdetail->amount,2,'.',',');

                $tardinessdetail->modifiedpercentage = $tardinessdetail->dailyratepercentage.' %';

                if($tardinessdetail->specific == '1'){

                    $getdepartments = Db::table('deduction_tardinessapplication')
                        ->join('hr_school_department','deduction_tardinessapplication.departmentid','=','hr_school_department.id')
                        ->where('deduction_tardinessapplication.tardinessdetailid', $tardinessdetail->id)
                        ->where('deduction_tardinessapplication.deleted', '0')
                        ->get();

                    array_push($tardinesscomputations,(object)array(
                        'computationinfo'   => $tardinessdetail,
                        'computationdepartments'   => $getdepartments
                    ));

                }else{

                    array_push($tardinesscomputations,(object)array(
                        'computationinfo'   => $tardinessdetail,
                        'computationdepartments'   => 'All'
                    ));

                }

            }

            $deductiontypes = Db::table('deduction_standard')
                ->where('deleted','0')
                ->get();
            
            return view('hr.deductiontypes')
                ->with('deductiontypes',$deductiontypes)
                ->with('departments',$departments)
                ->with('tardinesstype',$tardinesstype)
                ->with('tardinesscomputations',$tardinesscomputations);

        }

    }
    public function bracketing(Request $request)
    {
        // return $request->all();
        if(strtolower($request->get('type')) == 'pag-ibig'):

            $brackets = Db::table('hr_bracketpi')
            ->where('deleted','0')
                        ->get();

            return view('hr.brackets.bracketpagibig')
                        ->with('type', $request->get('type'))
                        ->with('brackets',$brackets);

        elseif(strtolower($request->get('type')) == 'philhealth'):
            
            $brackets = Db::table('hr_bracketph')
                        ->select(
                            'hr_bracketphdetail.id',
                            'hr_bracketphdetail.rangefrom',
                            'hr_bracketphdetail.rangeto',
                            'hr_bracketphdetail.premiumrate',
                            'hr_bracketphdetail.fixedamount'
                        )
                        ->join('hr_bracketphdetail', 'hr_bracketph.id','=','hr_bracketphdetail.bracketphid')
                        ->where('hr_bracketph.year', date('Y'))
                        ->where('hr_bracketph.deleted','0')
                        ->where('hr_bracketphdetail.deleted','0')
                        ->get();
            return view('hr.brackets.bracketphilhealth')
                        ->with('type', $request->get('type'))
                        ->with('brackets',$brackets);

        elseif(strtolower($request->get('type')) == 'sss'):
            
            $brackets = Db::table('hr_bracketss')
                ->where('deleted','0')
                        ->get();

            return view('hr.brackets.bracketsss')
                        ->with('type', $request->get('type'))
                        ->with('brackets',$brackets);

        elseif(strtolower($request->get('type')) == 'withholding tax'):
            
            $brackets = Db::table('hr_bracketwt')
                        ->get();

            return view('hr.brackets.bracketwithholdingtax')
                        ->with('type', $request->get('type'))
                        ->with('brackets',$brackets);

        endif;
    }
    public function bracketadd(Request $request)
    {
        
        if(strtolower($request->get('type')) == 'pag-ibig'):
            $id = DB::table('hr_bracketpi')
                ->insertGetId([
                    'rangefrom' => $request->get('bracketfrom'),
                    'rangeto'   => $request->get('bracketto'),
                    'eescrate'  => $request->get('bracketcontemployee'),
                    'erscrate'  => $request->get('bracketcontemployer')
                ]);

            return $id;

        elseif(strtolower($request->get('type')) == 'philhealth'):

            $id = DB::table('hr_bracketpi')
                ->insertGetId([
                    'rangefrom'     => $request->get('bracketfrom'),
                    'rangeto'       => $request->get('bracketto'),
                    'premiumrate'   => $request->get('bracketrate')
                ]);

            return $id;

        elseif(strtolower($request->get('type')) == 'sss'):
            
            $id = DB::table('hr_bracketss')
                ->insertGetId([
                    'rangefrom'             => $request->get('bracketfrom'),
                    'rangeto'               => $request->get('bracketto'),
                    'monthlysalarycredit'   => $request->get('bracketcredit'),
                    'ersamount'             => $request->get('bracketcontemployee'),
                    'eesamount'             => $request->get('bracketcontemployer')
                ]);

            return $id;
        endif;
    }
    public function bracketedit(Request $request)
    {
        // return $request->all();
        if(strtolower($request->get('type')) == 'pag-ibig'):
            
            DB::table('hr_bracketpi')
                ->where('id', $request->get('id'))
                ->update([
                    'rangefrom' => $request->get('rangefrom'),
                    'rangeto'   => $request->get('rangeto'),
                    'eescrate'  => $request->get('eescrate'),
                    'erscrate'  => $request->get('erscrate')
                ]);

        elseif(strtolower($request->get('type')) == 'philhealth'):
            
            DB::table('hr_bracketphdetail')
                ->where('id', $request->get('id'))
                ->update([
                    'rangefrom'     => $request->get('rangefrom'),
                    'rangeto'       => $request->get('rangeto'),
                    'premiumrate'   => $request->get('premiumrate'),
                    'fixedamount'   => $request->get('fixedamount'),
                    'updatedby'     => auth()->user()->id,
                    'updateddatetime' => date('Y-m-d H:i:s')
                ]);

        elseif(strtolower($request->get('type')) == 'sss'):
            DB::table('hr_bracketss')
                ->where('id', $request->get('id'))
                ->update([
                    'rangefrom'             => $request->get('rangefrom'),
                    'rangeto'               => $request->get('rangeto'),
                    'monthlysalarycredit'   => $request->get('monthlysalarycredit'),
                    'ersamount'             => $request->get('ersamount'),
                    'eesamount'             => $request->get('eesamount')
                ]);
        endif;

        return back();
    }
    public function bracketdelete(Request $request)
    {
        if(strtolower($request->get('type')) == 'pag-ibig'):
            
            DB::table('hr_bracketpi')
                ->where('id', $request->get('id'))
                ->update([
                    'deleted'             => 1,
                    'deletedby'               => auth()->user()->id,
                    'deleteddatetime'   => date('Y-m-d H:i:s')
                ]);

        elseif(strtolower($request->get('type')) == 'philhealth'):

            DB::table('hr_bracketphdetail')
                ->where('id', $request->get('id'))
                ->update([
                    'deleted'             => 1,
                    'deletedby'               => auth()->user()->id,
                    'deleteddatetime'   => date('Y-m-d H:i:s')
                ]);

        elseif(strtolower($request->get('type')) == 'sss'):
            DB::table('hr_bracketss')
                ->where('id', $request->get('id'))
                ->update([
                    'deleted'             => 1,
                    'deletedby'               => auth()->user()->id,
                    'deleteddatetime'   => date('Y-m-d H:i:s')
                ]);
        endif;

    }
    public function updatedeductions($id,Request $request){

        $id = Crypt::decrypt($id);
        
        if($id == 'adddeduction'){
            // return $request->all();

            $checkifExists = Db::table('deduction_standard')
                ->where('description','like','%'.$request->get('deductiontype'))
                ->where('deleted','0')
                ->get();

            if($request->get('brackettype') == null){

                $withbracket    = 0;

                $brackettype    = "";

            }else{

                $withbracket    = 1;

                $brackettype    = $request->get('brackettype');

            }
            if(count($checkifExists) == 0){

                Db::table('deduction_standard')
                    ->insert([
                        'description' => $request->get('deductiontype'),
                        'withbracket' => $withbracket,
                        'brackettype' => $brackettype
                    ]);

            }

            return back();

        }
        if($id == 'editdeduction'){
            
            Db::table('deduction_standard')
                ->where('id', $request->get('deductiontype'))
                ->update([
                    'description' => $request->get('editeddeductiontype')
                ]);

            return back();

        }
        if($id == 'deletedeductiontype'){
            
            Db::table('deduction_standard')
                ->where('id', $request->get('deductiontypeid'))
                ->update([
                    'deleted' => '1'
                ]);

            return 'success';
        }

    }
    public function tardinessdeduction($id,Request $request){

        if($id == 'dashboard'){

            $departments = Db::table('hr_school_department')
                ->where('deleted',0)
                ->get();
    
            $tardinesstype = Db::table('deduction_tardinesssetup')
                ->where('deleted','0')
                ->get();

            $tardinessdetails = Db::table('deduction_tardinessdetail')
                ->where('deduction_tardinessdetail.deleted','0')
                ->get();

            $tardinesscomputations = array();

            foreach($tardinessdetails as $tardinessdetail){
                
                $tardinessdetail->modifiedamount = number_format($tardinessdetail->amount,2,'.',',');

                $tardinessdetail->modifiedpercentage = $tardinessdetail->dailyratepercentage.' %';

                if($tardinessdetail->specific == '1'){

                    $getdepartments = Db::table('deduction_tardinessapplication')
                        ->join('hr_school_department','deduction_tardinessapplication.departmentid','=','hr_school_department.id')
                        ->where('deduction_tardinessapplication.tardinessdetailid', $tardinessdetail->id)
                        ->where('deduction_tardinessapplication.deleted', '0')
                        ->get();

                    array_push($tardinesscomputations,(object)array(
                        'computationinfo'   => $tardinessdetail,
                        'computationdepartments'   => $getdepartments
                    ));

                }else{

                    array_push($tardinesscomputations,(object)array(
                        'computationinfo'   => $tardinessdetail,
                        'computationdepartments'   => 'All'
                    ));

                }

            }
            // return $tardinesscomputations;
            return view('hr.tardiness')
                ->with('departments',$departments)
                ->with('tardinesstype',$tardinesstype)
                ->with('tardinesscomputations',$tardinesscomputations);
        }
        elseif($id == 'changecomputationtype'){
            $computationtypes = Db::table('deduction_tardinesssetup')
                ->get();
            foreach($computationtypes as $computationtype){
                if($computationtype->id == $request->get('computationtypeid')){
                    Db::table('deduction_tardinesssetup')
                        ->where('id',$request->get('computationtypeid'))
                        ->update([
                            'status'    => 1
                        ]);
                }else{
                    Db::table('deduction_tardinesssetup')
                        ->where('id','!=',$request->get('computationtypeid'))
                        ->update([
                            'status'    => 0
                        ]);
                }
            }
            return back();
        }
    } 
    public function addtardinesscomputation(Request $request)
    {
        // return $request->all();
        if($request->get('durationtype') == 'minutes'){
            $minutesbasis = 1;
            $hoursbasis = 0;
        }
        elseif($request->get('durationtype') == 'hours'){
            $minutesbasis = 0;
            $hoursbasis = 1;
        }

        if($request->get('deductionbasis') == 'fixedamount'){
            $amount = $request->get('amountdeducted');
            $percentage = 0;
            $basisfixedamount = 1;
            $basispercentage = 0;
        }
        elseif($request->get('deductionbasis') == 'dailyratepercentage'){
            $amount = 0;
            $percentage = $request->get('percentage');
            $basisfixedamount = 0;
            $basispercentage = 1;
        }

        if($request->get('applicationtype') == 'specific'){
            $specific = 1;
            $all = 0;
        }
        elseif($request->get('applicationtype') == 'all'){
            $specific = 0;
            $all = 1;
        }

        if($request->get('allowanceduration') != null || $request->get('allowanceduration') != 0){
            $allowanceduration = $request->get('allowanceduration');
            if($request->get('allowancedurationtype') == 'minutes'){
                $allowancedurationtype = 1;
            }
            elseif($request->get('allowancedurationtype') == 'hours'){
                $allowancedurationtype = 2;
            }
    
        }else{
            $allowanceduration = 0;
            $allowancedurationtype = 1;
        }
        // return $amount;
        $tardinesscomputationid = DB::table('deduction_tardinessdetail')
                ->insertGetId([
                    'lateduration'          =>     $request->get('timeduration'),
                    'minutes'               =>     $minutesbasis,
                    'hours'                 =>     $hoursbasis,
                    'amount'                =>     $amount,
                    'dailyratepercentage'   =>     $percentage,
                    'basisfixedamount'      =>     $basisfixedamount,
                    'basispercentage'       =>     $basispercentage,
                    'timeallowance'         =>     $allowanceduration,
                    'timeallowancetype'     =>     $allowancedurationtype,
                    'specific'              =>     $specific,
                    'all'                   =>     $all
                ]);

        if($all == 1){

            $departments = Db::table('hr_departments')
                ->where('deleted',0)
                ->get();

            foreach($departments as $department){

                DB::table('deduction_tardinessapplication')
                    ->insert([
                        'tardinessdetailid'          =>     $tardinesscomputationid,
                        'departmentid'               =>     $department->id
                    ]);

            }

        }

        if($specific == 1){

            foreach($request->get('departments') as $selecteddepartment){

                DB::table('deduction_tardinessapplication')
                    ->insert([
                        'tardinessdetailid'          =>     $tardinesscomputationid,
                        'departmentid'               =>     $selecteddepartment
                    ]);

            }

        }
        
        return back();
    }
    public function edittardinesscomputation(Request $request)
    {
        
        if($request->get('durationtype') == 'hours'){
            $hours = 1;
            $minutes = 0;
        }else{
            $hours = 0;
            $minutes = 1;
        }

        if($request->get('deductionbasis') == 'dailyratepercentage'){
            $dailyratepercentage = 1;
            $basisfixedamount = 0;
            $amount = 0;
            $percentage = $request->get('percentage');
        }else{
            $dailyratepercentage = 0;
            $basisfixedamount = 1;
            $amount = $request->get('amountdeducted');
            $percentage = 0;
        }

        if($request->get('editapplicationtype') == 'all'){
            $all = 1;
            $specific = 0;
        }else{
            $all = 0;
            $specific = 1;
        }

        DB::table('deduction_tardinessdetail')
            ->where('id',$request->get('computationid'))
            ->where('deleted','0')
            ->update([
                'lateduration'          =>  $request->get('timeduration'),
                'minutes'               =>  $minutes,
                'hours'                 =>  $hours,
                'amount'                =>  $amount,
                'dailyratepercentage'   =>  $percentage,
                'basisfixedamount'      =>  $basisfixedamount,
                'basispercentage'       =>  $dailyratepercentage,
                'specific'              =>  $specific,
                'all'                   =>  $all,
                'deductfromrate'        =>  $request->get('deductfromrate')
            ]);
        if($request->get('editapplicationtype') == 'all'){
            // DB::table('deduction_tardinessapplication')
            //     ->where('tardinessdetailid', $request->get('computationid'))
            //     ->update([
            //         'deleted'   => 1
            //     ]);
        }
        elseif($request->get('editapplicationtype') == 'specific'){
            $checkifExists = Db::table('deduction_tardinessapplication')
                ->where('tardinessdetailid', $request->get('computationid'))
                ->where('deleted', 0)
                ->get();
            // return $checkifExists;
            if(count($checkifExists) == 0){
                foreach($request->get('departments') as $department){

                    DB::table('deduction_tardinessapplication')
                        ->insert([
                            'tardinessdetailid'     => $request->get('computationid'),
                            'departmentid'          => $department
                        ]);
                }
            }else{
                foreach($checkifExists as $department){
                    
                    if(in_array($department->departmentid,$request->get('departments'))){
                        $checkifExistsinapplication = Db::table('deduction_tardinessapplication')
                            ->where('tardinessdetailid', $request->get('computationid'))
                            ->where('departmentid', $department->departmentid)
                            ->where('deleted', '0')
                            ->get();
                        if(count($checkifExistsinapplication) == 0){
                            DB::table('deduction_tardinessapplication')
                                ->insert([
                                    'tardinessdetailid'     => $request->get('computationid'),
                                    'departmentid'          => $department->departmentid
                                ]);
                        }
                    }else{
                        // array_push()
                            DB::table('deduction_tardinessapplication')
                                ->where('tardinessdetailid', $request->get('computationid'))
                                ->where('departmentid', $department->departmentid)
                                ->where('deleted', '0')
                                ->update([
                                    'deleted'   => '1'
                                ]);
                    }
                }
                $checkifExistsarray = array();
                
                foreach($checkifExists as $olddepartment){
                    array_push($checkifExistsarray, $olddepartment->departmentid);
                }
                foreach($request->get('departments') as $tobeinserteddepartment){
                    
                    if(in_array($tobeinserteddepartment,$checkifExistsarray)){
                    }else{
                        DB::table('deduction_tardinessapplication')
                            ->insert([
                                'tardinessdetailid'     => $request->get('computationid'),
                                'departmentid'          => $tobeinserteddepartment
                            ]);
                    }
                }
                
            }
        }
        return back();
    }
    public function deletetardinesscomputation(Request $request){
        // return $request->all();
        DB::table('deduction_tardinessdetail')
            ->where('id', $request->get('tardinesscomputationid'))
            ->update([
                'deleted'   => 1
            ]);
        Db::table('deduction_tardinessapplication')
            ->where('tardinessdetailid', $request->get('tardinesscomputationid'))
            ->update([
                'deleted'   => 1
            ]);
        return back();
    }
    public function standardallowances($id,Request $request){
        
        $id = Crypt::decrypt($id);

        if($id == 'dashboard'){

            $standardallowances = Db::table('allowance_standard')
                ->where('deleted','0')
                ->get();

            return view('hr.standardallowances')
                ->with('standardallowances',$standardallowances);

        }

    }
    public function updateallowances($id,Request $request){

        $id = Crypt::decrypt($id);
        
        if($id == 'addallowance'){

            foreach($request->get('descriptions') as $description){

                $checkifExists = Db::table('allowance_standard')
                    ->where('description','like','%'.$description)
                    ->where('deleted','0')
                    ->get();

                if(count($checkifExists) == 0){

                    Db::table('allowance_standard')
                        ->insert([
                            'description' => $description
                        ]);

                }

            }

            return back();

        }

        if($id == 'editallowance'){
            
            Db::table('allowance_standard')
                ->where('id', $request->get('allowanceid'))
                ->update([
                    'description' => $request->get('editallowance')
                ]);

            return back();

        }

        if($id == 'deleteallowance'){
            
            Db::table('allowance_standard')
                ->where('id', $request->get('allowanceid'))
                ->update([
                    'deleted' => '1'
                ]);

            return 'success';

        }

    }
    public function holidays()
    {

        $syid = DB::table('sy')
            ->where('isactive','1')
            ->first()
            ->id;
            
        date_default_timezone_set('Asia/Manila');

        $holidays = Db::table('schoolcal')
            ->select(
                'schoolcal.description',
                'schoolcal.datefrom',
                'schoolcal.dateto',
                'schoolcaltype.typename',
                'schoolcal.noclass'
                )
            ->join('schoolcaltype','schoolcal.type','=','schoolcaltype.id')
            ->where('schoolcal.deleted', '0')
            ->where('schoolcal.syid', $syid)
            ->get();
            
        foreach($holidays as $holiday){

            foreach($holiday as $key => $value){

                if($key == 'datefrom'){

                    $holiday->datefrom = date('M d, Y', strtotime($value));

                }

                if($key == 'dateto'){

                    $holiday->dateto = date('M d, Y', strtotime($value));
                    
                }

            }

        }

        $holidaytypes = Db::table('schoolcaltype')
            ->where('type','1')
            ->where('typename','!=','OTHER HOLIDAY')
            ->where('deleted','0')
            ->get();

        // $holidayrates = Db::table('holidayrates')
        //     ->get();
            
        return view('hr.holidays')
            ->with('holidays',$holidays)
            ->with('holidaytypes',$holidaytypes);
            // ->with('holidayrates',$holidayrates);

    }
    public function addholidaytypes(Request $request)
    {
        
        $checkifExists = Db::table('schoolcaltype')
            ->where('typename','like','%'.$request->get('typename'))
            ->get();

        if(count($checkifExists) == 0){

            DB::table('schoolcaltype')
                ->insert([
                    'typename'                =>   strtoupper($request->get('typename')),
                    'ratepercentagenowork'    =>   $request->get('newnowork'),
                    'ratepercentageworkon'    =>   $request->get('newworkon'),
                    'type'                    =>    1
                ]);

        }

        return back();

    }
    public function updateholidayrates(Request $request)
    {
        
        date_default_timezone_set('Asia/Manila');

        $updatorid = Db::table('teacher')
            ->where('userid', auth()->user()->id)
            ->first();

        foreach($request->get('fixedholidayids') as $fixedholidayidkey => $fixedholidayidvalue){
        
            Db::table('schoolcaltype')
                ->where('id', $fixedholidayidvalue)
                ->update([
                    'typename'                =>   $request->get('fixedholidaydescription')[$fixedholidayidkey],
                    'ratepercentagenowork'    =>   $request->get('fixedratepercentagenowork')[$fixedholidayidkey],
                    'ratepercentageworkon'    =>   $request->get('fixedratepercentageworkon')[$fixedholidayidkey]
                ]);

        }

        return back();

    }
    public function deleteholidaytype(Request $request)
    {
        
        DB::table('schoolcaltype')
            ->where('id',$request->get('deletetypeid'))
            ->update([
                'deleted'                =>   1
            ]);

        return back()->with('messageDelete','Holiday Type: '.$request->get('deletetypename').' deleted successfully!');

    }
    
    public function leavesettings(Request $request)
    {
        // return $request->all();
        if($request->get('action') == 'index')
        {
            
            $employees = DB::table('teacher')
                ->select('id','userid','title','lastname','firstname','middlename','suffix')
                ->where('isactive','1')
                ->where('deleted','0')
                // ->where('userid','!=',auth()->user()->id)
                ->orderBy('lastname','asc')
                ->get();
                
            if(count($employees)>0)
            {
                foreach($employees as $employee)
                {
                    if($employee->middlename != null)
                    {
                        $employee->middlename = $employee->middlename[0].'.';
                    }
                }
            }
    
            $approvals = DB::table('hr_leavesappr')
                ->select('hr_leavesappr.id','teacher.id as employeeid','teacher.title','teacher.lastname','teacher.firstname','teacher.middlename','teacher.suffix')
                ->join('teacher','hr_leavesappr.employeeid','=','teacher.id')
                ->where('hr_leavesappr.deleted','0')
                ->get();
                
            if(count($approvals)>0)
            {
                foreach($approvals as $approval)
                {
                    if($approval->middlename != null)
                    {
                        $approval->middlename = $approval->middlename[0].'.';
                    }
                }
            }
            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc')
            {
                return view('hr.settings.leavesettings')
                    ->with('approvals',$approvals)
                    ->with('employees',$employees);
            }else{
                return view('hr.settings.leavesettings.index')
                    ->with('approvals',$approvals)
                    ->with('employees',$employees);
            }
                
        }elseif($request->get('action') == 'load')
        {
            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc')
            {
                $leaves = DB::table('hr_leaves')
                    ->where('deleted','0')
                    ->get();
            }else{
                $leaves = DB::table('hr_leaves')
                    ->where('deleted','0')
                    ->where('lyear',$request->get('lyear'))
                    ->get();
            }
            
            if(count($leaves)>0)
            {
                foreach($leaves as $leave)
                {
                    $dates = DB::table('hr_leavedates')
                        ->where('leaveid', $leave->id)
                        ->where('deleted','0')
                        ->where('ldatefrom','!=', null)
                        ->where('ldateto','!=', null)
                        ->get();

                    $leave->dates = $dates;

                    $employees = DB::table('hr_leaveemployees')
                        ->select('hr_leaveemployees.*','teacher.lastname','teacher.firstname','teacher.middlename','teacher.suffix')
                        ->join('teacher','hr_leaveemployees.employeeid','=','teacher.id')
                        ->where('leaveid', $leave->id)
                        ->where('hr_leaveemployees.deleted','0')
                        ->get();

                    $leave->employees = $employees;
                }
            }
            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc')
            {
                return view('hr.settings.leavesettingsload')
                    ->with('leaves',$leaves);

            }else{
                return view('hr.settings.leavesettings.results')
                    ->with('leaves',$leaves);

            }
        }
        elseif($request->get('action') == 'create')
        {
            $leavetype = $request->get('leave_type');
            $lyear = $request->get('lyear');
            $noofapplications = $request->get('noofapplications');
            $statuspay = $request->get('statuspay');
            $yearstoavail = $request->get('yearstoavail');
            $cash = $request->get('cash');
            
            $checkifexists = DB::table('hr_leaves')
                ->where('leave_type','like','%'.$leavetype.'%')
                ->where('deleted','0')
                ->first();

            if(!$checkifexists)
            {
                $leaveid = DB::table('hr_leaves')
                    ->insertGetId([
                        'leave_type'        => $leavetype,
                        'lyear'        => $lyear,
                        'days'        => $noofapplications,
                        'withpay'        => $statuspay,
                        'yos'        => $yearstoavail,
                        'cash'        => $cash,
                        'createdby'         => auth()->user()->id,
                        'createddatetime'   => date('Y-m-d H:i:s')                    
                    ]);
            }

        }
        elseif($request->get('action') == 'deleteleave')
        {
            DB::table('hr_leaves')
                ->where('id', $request->get('id'))
                ->update([
                    'deleted'           => 1,
                    'deletedby'         => auth()->user()->id,
                    'deleteddatetime'   => date('Y-m-d H:i:s')
                ]);
        }
        elseif($request->get('action') == 'addmoredays')
        {
            $leavedates = json_decode($request->get('leavedates'));
            
            if(count($leavedates)>0)
            {
                foreach($leavedates as $leavedate)
                {
                    $checkifexists = DB::table('hr_leavedates')
                        ->where('leaveid',$request->get('id'))
                        ->where('ldatefrom', $leavedate->datefrom)
                        ->where('ldateto', $leavedate->dateto)
                        ->where('deleted','0')
                        ->count();

                    if($checkifexists == 0)
                    {
                        DB::table('hr_leavedates')
                            ->insert([
                                'leaveid'           => $request->get('id'),
                                'ldatefrom'         => $leavedate->datefrom,
                                'ldateto'           => $leavedate->dateto,
                                'createdby'         => auth()->user()->id,
                                'createddatetime'   => date('Y-m-d H:i:s')                    
                            ]);
                    }
                }
            }
        }
        elseif($request->get('action') == 'addmoreemployees')
        {
            $leaveemployees = json_decode($request->get('leaveemployees'));
            $eachapprovals = json_decode($request->get('eachapprovals'));
            if(count($leaveemployees)>0)
            {
                foreach($leaveemployees as $leaveemployee)
                {
                    $checkifexists = DB::table('hr_leaveemployees')
                        ->where('leaveid',$request->get('id'))
                        ->where('employeeid', $leaveemployee)
                        ->where('deleted','0')
                        ->first();

                    if($checkifexists)
                    {
                        $id = $checkifexists->id;
                    }else{
                        $id = DB::table('hr_leaveemployees')
                            ->insertGetId([
                                'leaveid'           => $request->get('id'),
                                'employeeid'             => $leaveemployee,
                                'createdby'         => auth()->user()->id,
                                'createddatetime'   => date('Y-m-d H:i:s')                    
                            ]);
                    }
                    
                    if(count($eachapprovals) == 0)
                    {
                            DB::table('hr_leaveemployeesappr')
                            ->where('headerid', $id)
                            ->update([
                                'deleted'           => 1,
                                'deletedby'         => auth()->user()->id,
                                'deleteddatetime'   => date('Y-m-d H:i:s')                    
                            ]);
                    }else{
                        foreach($eachapprovals as $eachapproval)
                        {
                            $checkifexistsappr = DB::table('hr_leaveemployeesappr')
                                ->where('headerid', $id)
                                ->where('appuserid', $eachapproval)
                                ->where('deleted','0')
                                ->first();

                            if(!$checkifexistsappr)
                            {
                                 DB::table('hr_leaveemployeesappr')
                                    ->insert([
                                        'headerid'          => $id,
                                        'appuserid'         => $eachapproval,
                                        'createdby'         => auth()->user()->id,
                                        'createddatetime'   => date('Y-m-d H:i:s')                    
                                    ]);
                            }

                        }
                    }
                    
                }
            }
        }
        elseif($request->get('action') == 'getapprovals')
        {
            $employees = DB::table('teacher')
                ->select('id','userid','title','lastname','firstname','middlename','suffix')
                ->where('isactive','1')
                ->where('deleted','0')
                ->orderBy('lastname','asc')
                ->get();
                

            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc')
            {
                $approvals = DB::table('hr_leaveemployeesappr')
                    ->select('hr_leaveemployeesappr.id as apprid', 'teacher.lastname','teacher.firstname','teacher.middlename')
                    ->join('teacher','hr_leaveemployeesappr.appuserid','=','teacher.userid')
                    ->where('hr_leaveemployeesappr.headerid', $request->get('leaveempid'))
                    ->where('hr_leaveemployeesappr.deleted','0')
                    ->get();
            }else{
                $approvals = DB::table('hr_leavesappr')
                    ->select('hr_leavesappr.id as apprid', 'teacher.lastname','teacher.firstname','teacher.middlename')
                    ->join('teacher','hr_leavesappr.appuserid','=','teacher.userid')
                    ->where('hr_leavesappr.leaveid', $request->get('leaveid'))
                    ->where('hr_leavesappr.deleted','0')
                    ->get();
            }
                
                
            return view('hr.settings.leavesettingsapprovals')
                ->with('approvals',$approvals)
                ->with('employees',$employees);
            
        }
        elseif($request->get('action') == 'addmoreapprovals')
        {
            // return $request->all();
            $moreapprovals = json_decode($request->get('moreapprovals'));
            $leaveempid = $request->get('leaveempid');
            $leaveid = $request->get('leaveid');
            
            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc')
            {
                if(count($moreapprovals)>0)
                {
                    foreach($moreapprovals as $eachapproval)
                    {
                        $checkifexistsappr = DB::table('hr_leaveemployeesappr')
                            ->where('headerid', $leaveempid)
                            ->where('appuserid', $eachapproval)
                            ->where('deleted','0')
                            ->first();
        
                        if(!$checkifexistsappr)
                        {
                             DB::table('hr_leaveemployeesappr')
                                ->insert([
                                    'headerid'          => $leaveempid,
                                    'appuserid'         => $eachapproval,
                                    'createdby'         => auth()->user()->id,
                                    'createddatetime'   => date('Y-m-d H:i:s')                    
                                ]);
                        }
        
                    }
                }
            }else{
                if(count($moreapprovals)>0)
                {
                    foreach($moreapprovals as $eachapproval)
                    {
                        $checkifexistsappr = DB::table('hr_leavesappr')
                            ->where('leaveid', $leaveid)
                            ->where('appuserid', $eachapproval)
                            ->where('deleted','0')
                            ->first();
        
                        if(!$checkifexistsappr)
                        {
                             DB::table('hr_leavesappr')
                                ->insert([
                                    'leaveid'          => $leaveid,
                                    'appuserid'         => $eachapproval,
                                    'createdby'         => auth()->user()->id,
                                    'createddatetime'   => date('Y-m-d H:i:s')                    
                                ]);
                        }
        
                    }
                }
            }
        }
        elseif($request->get('action') == 'deleteapproval')
        {
            // DB::table('hr_leaveemployeesappr') 
            DB::table('hr_leavesappr') // change by gian
                ->where('id', $request->get('approvalid'))
                ->update([
                    'deleted'           => 1,
                    'deletedby'         => auth()->user()->id,
                    'deleteddatetime'   => date('Y-m-d H:i:s')                    
                ]);
        }
        elseif($request->get('action') == 'updateleavename')
        {
            DB::table('hr_leaves')
                ->where('id', $request->get('id'))
                ->update([
                    'leave_type'        => $request->get('leavename'),
                    'updatedby'         => auth()->user()->id,
                    'updateddatetime'   => date('Y-m-d H:i:s')
                ]);
        }
        elseif($request->get('action') == 'updateleavedays')
        {
            DB::table('hr_leaves')
                ->where('id', $request->get('id'))
                ->update([
                    'days'        => $request->get('leavedays'),
                    'updatedby'         => auth()->user()->id,
                    'updateddatetime'   => date('Y-m-d H:i:s')
                ]);
        }
        elseif($request->get('action') == 'updatewithpay')
        {
            DB::table('hr_leaves')
                ->where('id', $request->get('id'))
                ->update([
                    'withpay'        => $request->get('withpay'),
                    'updatedby'         => auth()->user()->id,
                    'updateddatetime'   => date('Y-m-d H:i:s')
                ]);
        }
        elseif($request->get('action') == 'updateyearofservice')
        {
            DB::table('hr_leaves')
                ->where('id', $request->get('id'))
                ->update([
                    'yos'        => $request->get('years'),
                    'updatedby'         => auth()->user()->id,
                    'updateddatetime'   => date('Y-m-d H:i:s')
                ]);
        }
        elseif($request->get('action') == 'updatetocash')
        {
            DB::table('hr_leaves')
                ->where('id', $request->get('id'))
                ->update([
                    'cash'        => $request->get('tocash'),
                    'updatedby'         => auth()->user()->id,
                    'updateddatetime'   => date('Y-m-d H:i:s')
                ]);
        }
        
        elseif($request->get('action') == 'deletedate')
        {
            DB::table('hr_leavedates')
                ->where('id', $request->get('id'))
                ->update([
                    'deleted'           => 1,
                    'deletedby'         => auth()->user()->id,
                    'deleteddatetime'   => date('Y-m-d H:i:s')
                ]);
        }
        elseif($request->get('action') == 'deleteemployee')
        {
            DB::table('hr_leaveemployees')
                ->where('id', $request->get('id'))
                ->update([
                    'deleted'           => 1,
                    'deletedby'         => auth()->user()->id,
                    'deleteddatetime'   => date('Y-m-d H:i:s')
                ]);
        }

    }
    public function leaveapprovals(Request $request)
    {
        // return $request->all();
        if($request->get('action') == 'add')
        {
            if(count($request->get('employeeids')) > 0)
            {
                foreach($request->get('employeeids') as $employeeid)
                {
                    $checkifexists = DB::table('hr_leavesappr')
                        ->where('employeeid', $employeeid)
                        ->where('deleted','0')
                        ->first();
    
                    if(!$checkifexists)
                    {
                        DB::table('hr_leavesappr')
                            ->insert([
                                'employeeid'        => $employeeid,
                                'createdby'         => auth()->user()->id,
                                'createddatetime'   => date('Y-m-d H:i:s')
                            ]);
                    }
                }
            }
            return back();
        }
        elseif($request->get('action') == 'delete')
        {
            DB::table('hr_leavesappr')
                ->where('id', $request->get('approvalid'))
                ->update([
                    'deleted'           => 1,
                    // 'employeeid'        => $request->get('employeeid'),
                    'deletedby'         => auth()->user()->id,
                    'deleteddatetime'   => date('Y-m-d H:i:s')
                ]);
            
            return back();
        }
    }
    public function leaveapproval($id, Request $request)
    {
        // return $id;
        // return $request->all();
        date_default_timezone_set('Asia/Manila');
        if($id == 'add')
        {
            foreach($request->get('employeeids') as $employeeid)
            {
                $checkifExists = DB::table('hr_leavesappr')
                    ->where('employeeid', $employeeid)
                    ->where('deleted','0')
                    ->get();
    
                if(count($checkifExists) == 0)
                {
                    DB::table('hr_leavesappr')
                        ->insert([
                            'employeeid'        => $employeeid,
                            'createdby'         => auth()->user()->id,
                            'createddatetime'   => date('y-m-d H:i:s')
                        ]);
                }
            }
            return back();
        }elseif($id = 'delete')
        {

            DB::table('hr_leavesappr')
                ->where('id', $request->get('approvalid'))
                ->update([
                    'deleted'           => '1',
                    'deletedby'         => auth()->user()->id,
                    'deleteddatetime'   => date('Y-m-d H:i:s')
                ]);

            return back();
        }
    }
    
    public function overtimesettings(Request $request)
    {
        if($request->get('action') == 'index')
        {
    
            $employees = DB::table('teacher')
                ->select('id','userid','title','lastname','firstname','middlename','suffix')
                ->where('isactive','1')
                ->where('deleted','0')
                // ->where('userid','!=',auth()->user()->id)
                ->orderBy('lastname','asc')
                ->get();
                
            if(count($employees)>0)
            {
                foreach($employees as $employee)
                {
                    if($employee->middlename != null)
                    {
                        $employee->middlename = $employee->middlename[0].'.';
                    }
                }
            }
    
            // $approvals = DB::table('hr_leavesappr')
            //     ->select('hr_leavesappr.id','teacher.id as employeeid','teacher.title','teacher.lastname','teacher.firstname','teacher.middlename','teacher.suffix')
            //     ->join('teacher','hr_leavesappr.employeeid','=','teacher.id')
            //     ->where('hr_leavesappr.deleted','0')
            //     ->get();
                
            // if(count($approvals)>0)
            // {
            //     foreach($approvals as $approval)
            //     {
            //         if($approval->middlename != null)
            //         {
            //             $approval->middlename = $approval->middlename[0].'.';
            //         }
            //     }
            // }
            
            return view('hr.settings.overtimesettings')
                // ->with('approvals',$approvals)
                ->with('employees',$employees);
                
        }elseif($request->get('action') == 'load')
        {
            $leaves = DB::table('hr_leaves')
                ->where('deleted','0')
                ->get();
                
            if(count($leaves)>0)
            {
                foreach($leaves as $leave)
                {
                    $dates = DB::table('hr_leavedates')
                        ->where('leaveid', $leave->id)
                        ->where('deleted','0')
                        ->where('ldatefrom','!=', null)
                        ->where('ldateto','!=', null)
                        ->get();

                    $leave->dates = $dates;

                    $employees = DB::table('hr_leaveemployees')
                        ->select('hr_leaveemployees.*','teacher.lastname','teacher.firstname','teacher.middlename','teacher.suffix')
                        ->join('teacher','hr_leaveemployees.employeeid','=','teacher.id')
                        ->where('leaveid', $leave->id)
                        ->where('hr_leaveemployees.deleted','0')
                        ->get();

                    $leave->employees = $employees;
                }
            }
            return view('hr.settings.leavesettingsload')
                ->with('leaves',$leaves);
        }
        elseif($request->get('action') == 'create')
        {
            $leavetype = $request->get('leave_type');
            
            $leaveid = DB::table('hr_leaves')
                ->insertGetId([
                    'leave_type'        => $leavetype,
                    'createdby'         => auth()->user()->id,
                    'createddatetime'   => date('Y-m-d H:i:s')                    
                ]);

        }
        elseif($request->get('action') == 'deleteleave')
        {
            DB::table('hr_leaves')
                ->where('id', $request->get('id'))
                ->update([
                    'deleted'           => 1,
                    'deletedby'         => auth()->user()->id,
                    'deleteddatetime'   => date('Y-m-d H:i:s')
                ]);
        }
        elseif($request->get('action') == 'addmoredays')
        {
            $leavedates = json_decode($request->get('leavedates'));
            
            if(count($leavedates)>0)
            {
                foreach($leavedates as $leavedate)
                {
                    $checkifexists = DB::table('hr_leavedates')
                        ->where('leaveid',$request->get('id'))
                        ->where('ldatefrom', $leavedate->datefrom)
                        ->where('ldateto', $leavedate->dateto)
                        ->where('deleted','0')
                        ->count();

                    if($checkifexists == 0)
                    {
                        DB::table('hr_leavedates')
                            ->insert([
                                'leaveid'           => $request->get('id'),
                                'ldatefrom'         => $leavedate->datefrom,
                                'ldateto'           => $leavedate->dateto,
                                'createdby'         => auth()->user()->id,
                                'createddatetime'   => date('Y-m-d H:i:s')                    
                            ]);
                    }
                }
            }
        }
        elseif($request->get('action') == 'addmoreemployees')
        {
            $leaveemployees = json_decode($request->get('leaveemployees'));
            $eachapprovals = json_decode($request->get('eachapprovals'));
            if(count($leaveemployees)>0)
            {
                foreach($leaveemployees as $leaveemployee)
                {
                    $checkifexists = DB::table('hr_leaveemployees')
                        ->where('leaveid',$request->get('id'))
                        ->where('employeeid', $leaveemployee)
                        ->where('deleted','0')
                        ->first();

                    if($checkifexists)
                    {
                        $id = $checkifexists->id;
                    }else{
                        $id = DB::table('hr_leaveemployees')
                            ->insertGetId([
                                'leaveid'           => $request->get('id'),
                                'employeeid'             => $leaveemployee,
                                'createdby'         => auth()->user()->id,
                                'createddatetime'   => date('Y-m-d H:i:s')                    
                            ]);
                    }
                    
                    if(count($eachapprovals) == 0)
                    {
                            DB::table('hr_leaveemployeesappr')
                            ->where('headerid', $id)
                            ->update([
                                'deleted'           => 1,
                                'deletedby'         => auth()->user()->id,
                                'deleteddatetime'   => date('Y-m-d H:i:s')                    
                            ]);
                    }else{
                        foreach($eachapprovals as $eachapproval)
                        {
                            $checkifexistsappr = DB::table('hr_leaveemployeesappr')
                                ->where('headerid', $id)
                                ->where('appuserid', $eachapproval)
                                ->where('deleted','0')
                                ->first();

                            if(!$checkifexistsappr)
                            {
                                 DB::table('hr_leaveemployeesappr')
                                    ->insert([
                                        'headerid'          => $id,
                                        'appuserid'         => $eachapproval,
                                        'createdby'         => auth()->user()->id,
                                        'createddatetime'   => date('Y-m-d H:i:s')                    
                                    ]);
                            }

                        }
                    }
                    
                }
            }
        }
        elseif($request->get('action') == 'getapprovals')
        {
    
            $employees = DB::table('teacher')
                ->select('id','userid','title','lastname','firstname','middlename','suffix')
                ->where('isactive','1')
                ->where('deleted','0')
                ->orderBy('lastname','asc')
                ->get();
                

            $approvals = DB::table('hr_leaveemployeesappr')
                ->select('hr_leaveemployeesappr.id as apprid', 'teacher.lastname','teacher.firstname','teacher.middlename')
                ->join('teacher','hr_leaveemployeesappr.appuserid','=','teacher.userid')
                ->where('hr_leaveemployeesappr.headerid', $request->get('leaveempid'))
                ->where('hr_leaveemployeesappr.deleted','0')
                ->get();
                
                
            return view('hr.settings.leavesettingsapprovals')
                ->with('approvals',$approvals)
                ->with('employees',$employees);
            
        }
        elseif($request->get('action') == 'addmoreapprovals')
        {
            $moreapprovals = json_decode($request->get('moreapprovals'));
            $leaveempid = $request->get('leaveempid');
            
            if(count($moreapprovals)>0)
            {
                foreach($moreapprovals as $eachapproval)
                {
                    $checkifexistsappr = DB::table('hr_leaveemployeesappr')
                        ->where('headerid', $leaveempid)
                        ->where('appuserid', $eachapproval)
                        ->where('deleted','0')
                        ->first();
    
                    if(!$checkifexistsappr)
                    {
                         DB::table('hr_leaveemployeesappr')
                            ->insert([
                                'headerid'          => $leaveempid,
                                'appuserid'         => $eachapproval,
                                'createdby'         => auth()->user()->id,
                                'createddatetime'   => date('Y-m-d H:i:s')                    
                            ]);
                    }
    
                }
            }
        }
        elseif($request->get('action') == 'deleteapproval')
        {
            DB::table('hr_leaveemployeesappr')
                ->where('id', $request->get('approvalid'))
                ->update([
                    'deleted'           => 1,
                    'deletedby'         => auth()->user()->id,
                    'deleteddatetime'   => date('Y-m-d H:i:s')                    
                ]);
        }
        elseif($request->get('action') == 'updateleavename')
        {
            DB::table('hr_leaves')
                ->where('id', $request->get('id'))
                ->update([
                    'leave_type'        => $request->get('leavename'),
                    'updatedby'         => auth()->user()->id,
                    'updateddatetime'   => date('Y-m-d H:i:s')
                ]);
        }
        elseif($request->get('action') == 'updateleavedays')
        {
            DB::table('hr_leaves')
                ->where('id', $request->get('id'))
                ->update([
                    'days'        => $request->get('leavedays'),
                    'updatedby'         => auth()->user()->id,
                    'updateddatetime'   => date('Y-m-d H:i:s')
                ]);
        }
        elseif($request->get('action') == 'updatewithpay')
        {
            DB::table('hr_leaves')
                ->where('id', $request->get('id'))
                ->update([
                    'withpay'        => $request->get('withpay'),
                    'updatedby'         => auth()->user()->id,
                    'updateddatetime'   => date('Y-m-d H:i:s')
                ]);
        }
        elseif($request->get('action') == 'updateyearofservice')
        {
            DB::table('hr_leaves')
                ->where('id', $request->get('id'))
                ->update([
                    'yos'        => $request->get('years'),
                    'updatedby'         => auth()->user()->id,
                    'updateddatetime'   => date('Y-m-d H:i:s')
                ]);
        }
        elseif($request->get('action') == 'updatetocash')
        {
            DB::table('hr_leaves')
                ->where('id', $request->get('id'))
                ->update([
                    'cash'        => $request->get('tocash'),
                    'updatedby'         => auth()->user()->id,
                    'updateddatetime'   => date('Y-m-d H:i:s')
                ]);
        }
        elseif($request->get('action') == 'deletedate')
        {
            DB::table('hr_leavedates')
                ->where('id', $request->get('id'))
                ->update([
                    'deleted'           => 1,
                    'deletedby'         => auth()->user()->id,
                    'deleteddatetime'   => date('Y-m-d H:i:s')
                ]);
        }
        elseif($request->get('action') == 'deleteemployee')
        {
            DB::table('hr_leaveemployees')
                ->where('id', $request->get('id'))
                ->update([
                    'deleted'           => 1,
                    'deletedby'         => auth()->user()->id,
                    'deleteddatetime'   => date('Y-m-d H:i:s')
                ]);
        }

    }
    public function undertime(Request $request)
    {
    
        $employees = DB::table('teacher')
            ->select('teacher.id','teacher.userid','title','lastname','firstname','middlename','suffix','utype','usertypeid')
            ->join('usertype','teacher.usertypeid','=','usertype.id')
            ->where('teacher.isactive','1')
            ->where('teacher.deleted','0')
            ->get();
            
        if(!$request->has('action'))
        {
            if(count($employees)>0)
            {
                foreach($employees as $employee)
                {
                    $employee->sortname = $employee->lastname.', '.$employee->firstname;
                    $employee->otherportals = DB::table('faspriv')
                        ->where('userid', $employee->userid)
                        ->join('usertype','faspriv.usertype','=','usertype.id')
                        ->where('faspriv.deleted','0')
                        ->where('faspriv.usertype','!=', $employee->usertypeid)
                        ->get();

                        
                    $employee->approvals = DB::table('undertime_approval')
                        ->select('undertime_approval.id','teacher.lastname','teacher.firstname')
                        ->join('teacher','undertime_approval.appruserid','=','teacher.userid')
                        ->where('employeeid', $employee->id)
                        ->where('undertime_approval.deleted','0')
                        ->get();
                }
            }
            $employees = collect($employees)->sortBy('sortname')->values()->all();
            return view('hr.settings.undertime.index')
                ->with('employees',$employees);

        }else{
            $employeeid = $request->get('employeeid');
            if($request->get('action') == 'addapproval')
            {
                $userids    = json_decode($request->get('userids'));
                
                foreach($userids as $userid)
                {
                    $checkifexists = DB::table('undertime_approval')
                        ->where('employeeid', $employeeid)
                        ->where('appruserid', $userid)
                        ->where('deleted','0')
                        ->first();

                    if(!$checkifexists)
                    {
                        DB::table('undertime_approval')
                            ->insert([
                                'employeeid'        => $employeeid,
                                'appruserid'        => $userid,
                                'createdby'         => auth()->user()->id,
                                'createddatetime'   => date('Y-m-d H:i:s')
                            ]);
                    }
                }

                return 1;
            }elseif($request->get('action') == 'getapprovals')
            {
                $approvals = DB::table('undertime_approval')
                    ->select('undertime_approval.id','teacher.lastname','teacher.firstname')
                    ->join('teacher','undertime_approval.appruserid','=','teacher.userid')
                    ->where('employeeid', $employeeid)
                    ->where('undertime_approval.deleted','0')
                    ->get();

                    return $approvals;
            }elseif($request->get('action') == 'deleteapproval')
            {
                DB::table('undertime_approval')
                    ->where('id', $request->get('appid'))
                    ->update([
                        'deleted'           => 1,
                        'deletedby'         => auth()->user()->id,
                        'deleteddatetime'   => date('Y-m-d H:i:s')
                    ]);

                return 1;
            }

        }
    }

}
