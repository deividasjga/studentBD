<?php

namespace App\Http\Controllers\Teacher;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassModel;
use App\Models\SubjectModel;

class TeacherClassController extends Controller
{
    public function getTeacherClasses()
    {
        $teacher = auth()->user();
        $teacherClasses = $teacher->teacherClasses()->with('subjects')->get();
        return view('teacher.classes.teacherClassList', compact('teacherClasses'));
    }

    public function getTeacherClassesJson()
    {
        $teacher = auth()->user();
        $teacherClasses = $teacher->teacherClasses()->with('subjects')->get();
        return response()->json($teacherClasses);
    }

    public function getTeacherClassSubjects($userId, $classId)
    {
        $teacher = User::findOrFail($userId);
        $subjects = SubjectModel::whereHas('teachers', function($query) use ($userId, $classId) {
            $query->where('teacher_id', $userId)
                  ->where('class_id', $classId);
        })->get();

        return response()->json($subjects);
    }

    public function subjectGradeList($userId, $classId, $subjectId)
    {
        $user = User::findOrFail($userId);
        $class = ClassModel::findOrFail($classId);
        $subject = SubjectModel::findOrFail($subjectId);

        return view('teacher.classes.subjectGrading', compact('userId', 'classId', 'subjectId'));
    }


    public function getClassLeaderboard($classId)
    {
        $students = User::where('role', 'student')
            ->where('student_class_id', $classId)
            ->with('studentGrades')
            ->get();
    
        $studentsWithAverages = [];
    
        foreach ($students as $student) {
            $grades = $student->studentGrades;
            $gradeSum = 0;
            $gradeCount = 0;
            $subjectAverages = [];
    
            foreach ($grades as $grade) {
                if ($grade->grade != 'n') {
                    $gradeValue = floatval($grade->grade);
                    $gradeSum += $gradeValue;
                    $gradeCount++;
    
                    if (!isset($subjectAverages[$grade->subject_id])) {
                        $subjectAverages[$grade->subject_id] = [
                            'total' => $gradeValue,
                            'count' => 1
                        ];
                    } else {
                        $subjectAverages[$grade->subject_id]['total'] += $gradeValue;
                        $subjectAverages[$grade->subject_id]['count']++;
                    }
                }
            }
    
            $studentAverage = $gradeCount > 0 ? $gradeSum / $gradeCount : 0;
    
            foreach ($subjectAverages as $subjectId => $data) {
                $subjectAverage = $data['total'] / $data['count'];
                $subjectAverages[$subjectId]['average'] = $subjectAverage;
            }
    
            $totalSubjectAverage = 0;
            $subjectCount = count($subjectAverages);
    
            foreach ($subjectAverages as $data) {
                $totalSubjectAverage += $data['average'];
            }
    
            $totalSubjectAverage = $subjectCount > 0 ? $totalSubjectAverage / $subjectCount : 0;
    
            $studentWithAverages = [
                'student_id' => $student->id,
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'average_grade' => $studentAverage,
                'subject_averages' => $subjectAverages,
                'total_subject_average' => $totalSubjectAverage
            ];
    
            $studentsWithAverages[] = $studentWithAverages;
        }
    
        return response()->json($studentsWithAverages);
    }
    
}