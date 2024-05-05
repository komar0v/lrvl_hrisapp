<?php

namespace App\Models\Employee;

use App\Models\Employee\EmployeeMdls AS M_Employee;
use Maatwebsite\Excel\Concerns\ToModel;

class EmployeeImport implements ToModel
{
    private $rows = 0;
    
    public function model(array $row)
    {
        ++$this->rows;
        return new M_Employee([
            'first_name' => $row[0],
            'last_name' => $row[1],
            'email' => $row[2],
            'phone_number' => $row[3],
            'address' => $row[4],
            'date_of_birth' => $row[5],
            'gender' => $row[6],
            'marital_status' => $row[7],
        ]);
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }
}
