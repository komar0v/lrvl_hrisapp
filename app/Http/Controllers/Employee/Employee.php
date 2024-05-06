<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee\EmployeeMdls as M_Employee;
use App\Models\Employee\EmployeeImport AS EmployeesImportMDL;
use Maatwebsite\Excel\Facades\Excel;

class Employee extends Controller
{
    public function addNewEmployee(Request $req){
        $requestData = $req->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:employees_tbl,email',
            'phone_number' => 'required|string',
            'address' => 'required|string',
            'date_of_birth' => 'required|date',
            'gender'=>'required|in:Pria,Wanita,Lainnya',
            'marital_status' => 'required|in:Lajang,Menikah,Cerai,Janda,Duda',
        ]);

        $data=[
            'first_name'=>$requestData['first_name'],
            'last_name'=>$requestData['last_name'],
            'email'=>$requestData['email'],
            'phone_number'=>$requestData['phone_number'],
            'address'=>$requestData['address'],
            'date_of_birth'=>$requestData['date_of_birth'], //YYYY-MM-DD
            'marital_status'=>$requestData['marital_status'],
        ];
    
        $employee = M_Employee::create($data);

        return response()->json(['message' => "Employee $employee->first_name $employee->last_name added"], 201);
    }

    public function importNewEmployeeFromFile(Request $req){
        $req->validate([
            'file' => 'required|file|mimes:xls,xlsx',
        ]);
    
        $file = $req->file('file');
    
        $import = new EmployeesImportMDL();
        Excel::import($import, $file);
        $totalRows = $import->getRowCount();
    
        return response()->json(['message' => "$totalRows Employees added successfully"], 201);
    }

    public function showAllEmployees(){
        return response()->json(M_Employee::getAllEmployees(), 200);
    }
}