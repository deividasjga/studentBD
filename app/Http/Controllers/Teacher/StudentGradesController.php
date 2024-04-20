<?php

namespace App\Http\Controllers\Teacher;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassModel;
use App\Models\SubjectModel;
use App\Models\GradeModel;
use Illuminate\Support\Facades\Validator;

class StudentGradesController extends Controller
{
    public function getStudents($classId, $subjectId)
    {
        $students = User::where('student_class_id', $classId)->get();
        return response()->json($students);
    }

    public function getStudentsGrades($classId, $subjectId)
    {
        $grades = GradeModel::where('class_id', $classId)
                            ->where('subject_id', $subjectId)
                            ->get();

        if ($grades->isEmpty()){
            return response()->json(['message' => 'No grades found for the specified class and subject.'], 404);
        }

        return response()->json($grades);
    }


    public function saveGrades(Request $request)
    {
        $gradesData = $request->all();
        
        try {
            $studentId = $gradesData[0]['student_id'];
            $subjectIds = array_unique(array_column($gradesData, 'subject_id'));
            $classIds = array_unique(array_column($gradesData, 'class_id'));
            
            GradeModel::where('student_id', $studentId)
                ->whereIn('subject_id', $subjectIds)
                ->whereIn('class_id', $classIds)
                ->delete();
    
            foreach ($gradesData as $gradeData) {
                $teacherId = $gradeData['teacher_id'];
                $classId = $gradeData['class_id'];
                $subjectId = $gradeData['subject_id'];
                $gradeDate = $gradeData['grade_date'];
                $grade = $gradeData['grade'];
                
                GradeModel::create([
                    'teacher_id' => $teacherId,
                    'student_id' => $studentId,
                    'class_id' => $classId,
                    'subject_id' => $subjectId,
                    'grade_date' => $gradeDate,
                    'grade' => $grade
                ]);
            }
    
            return response()->json(['message' => 'Grades saved successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to save grades', 'message' => $e->getMessage()], 500);
        }
    }
    
    
    


}