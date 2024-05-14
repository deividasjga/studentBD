<?php

namespace App\Http\Controllers\Parent;

use App\Models\ChallengeModel;
use App\Models\StudentChallengeModel;
use App\Models\RewardModel;
use App\Models\PointModel;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ParentLeaderboardController extends Controller
{
    public function getLeaderboardStudents($userId)
    {
        $user = User::findOrFail($userId);
        $studentClassId = $user->student_class_id;

        $students = User::where('role', 'student')
            ->where('student_class_id', $studentClassId)
            ->with('studentGrades')
            ->get();

        $studentsWithAverages = [];
        $gradeSum = 0;
        $gradeCount = 0;

        foreach ($students as $student) {
            $grades = $student->studentGrades;
            $subjectAverages = [];

            foreach ($grades as $grade) {
                if($grade->grade != 'n') {
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
                $totalSubjectAverage = $totalSubjectAverage + $data['average'];
            }
            $totalSubjectAverage = $subjectCount > 0 ? $totalSubjectAverage / $subjectCount : 0;

            $studentWithAverages = [
                'student_id' => $student->id,
                'first_name' => $student->id == $userId ? $student->first_name : '******',
                'last_name' => $student->id == $userId ? $student->last_name : '******',
                'average_grade' => $studentAverage,
                'subject_averages' => $subjectAverages,
                'total_subject_average' => $totalSubjectAverage
            ];
            $studentsWithAverages[] = $studentWithAverages;
        }
        return response()->json($studentsWithAverages);
    }
}
