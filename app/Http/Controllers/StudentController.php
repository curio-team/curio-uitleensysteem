<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function showStudent($studentId){
        $student = Student::findOrFail($studentId);

        return view('students.show', [
            'student' => $student,
        ]);
    }
}
