<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\DB;

class EmployeeMdls extends Model
{
    protected $table = 'employees_tbl';
    use HasUuids;
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'date_of_birth',
        'address',
        'gender',
        'marital_status'
    ];

    protected function getAllEmployees()
    {
        return DB::table($this->table)->get();
    }
}
