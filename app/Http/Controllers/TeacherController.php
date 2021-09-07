<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function showTeacher($teacherId){
        $teacher = Teacher::findOrFail($teacherId);

        return view('teachers.show', [
            'teacher' => $teacher,
        ]);
    }
}
