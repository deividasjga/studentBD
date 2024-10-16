<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\ParentController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AssignController;
use App\Http\Controllers\Teacher\TeacherClassController;
use App\Http\Controllers\Teacher\StudentGradesController;
use App\Http\Controllers\Student\GradeController;
use App\Http\Controllers\Admin\AdminChallengeController;
use App\Http\Controllers\Student\ChallengeController;
use App\Http\Controllers\Admin\AdminRewardController;
use App\Http\Controllers\Student\RewardController;
use App\Http\Controllers\Student\LeaderboardController;
use App\Http\Controllers\Parent\ParentGradeController;
use App\Http\Controllers\Parent\ParentLeaderboardController;
use App\Http\Controllers\Student\HomeworkController;
use App\Http\Controllers\Teacher\TeacherHomeworkController;
use App\Http\Controllers\Parent\ParentHomeworkController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', function () {
    return view('welcome');
});
Route::get('/home', [HomeController::class, 'index'])->name('home');

Auth::routes();

Route::middleware(['auth'])->group(function(){

    Route::get('/homePage', function () {
        return view('homePage');
    })->name('homePage');
});


Route::middleware(['auth', 'user-role:admin'])->group(function(){
    Route::get('api/students', [StudentController::class, 'index']);
    Route::post('api/students', [StudentController::class, 'store']);
    Route::put('api/students/{user}', [StudentController::class, 'update']);
    Route::delete('api/students/{user}', [StudentController::class, 'destroy']);

    Route::get('api/teachers', [TeacherController::class, 'index']);
    Route::post('api/teachers', [TeacherController::class, 'store']);
    Route::put('api/teachers/{user}', [TeacherController::class, 'update']);
    Route::delete('api/teachers/{user}', [TeacherController::class, 'destroy']);

    Route::get('api/parents', [ParentController::class, 'index']);
    Route::post('api/parents', [ParentController::class, 'store']);
    Route::put('api/parents/{user}', [ParentController::class, 'update']);
    Route::delete('api/parents/{user}', [ParentController::class, 'destroy']);
    Route::post('api/parents/assignStudents/{selectedParentId}', [ParentController::class, 'assignParentStudents']);

    Route::get('api/admins', [AdminController::class, 'index']);
    Route::post('api/admins', [AdminController::class, 'store']);
    Route::put('api/admins/{user}', [AdminController::class, 'update']);
    Route::delete('api/admins/{user}', [AdminController::class, 'destroy']);

    Route::get('api/subjects', [SubjectController::class, 'index']);
    Route::post('api/subjects', [SubjectController::class, 'store']);
    Route::put('api/subjects/{subject}', [SubjectController::class, 'update']);
    Route::delete('api/subjects/{subject}', [SubjectController::class, 'destroy']);

    Route::get('api/classes', [ClassController::class, 'index']);
    Route::get('api/classes/{class}', [ClassController::class, 'show'])->name('classes.show');
    Route::post('api/classes', [ClassController::class, 'store']);
    Route::put('api/classes/{class}', [ClassController::class, 'update']);
    Route::delete('api/classes/{class}', [ClassController::class, 'destroy']);
    Route::get('api/classes/getClass/{id}', [ClassController::class, 'getClass']);

    Route::get('/teachersAssign', [AssignController::class, 'teacherAssignList'])->name('indexAssign');
    Route::get('/teachers/assignClasses/{id}', [AssignController::class, 'assignTeacherClasses'])->name('assignClass');
    Route::post('/teachers/updateClasses/{id}', [AssignController::class, 'updateTeacherClasses'])->name('updateClass');
    Route::get('/teachers/assignSubjects/{teacher_id}/{class_id}', [AssignController::class, 'assignTeacherSubjects'])->name('assignSubjects');
    Route::post('/teachers/updateSubjects/{teacher_id}/{class_id}', [AssignController::class, 'updateTeacherSubjects'])->name('updateSubjects');

    Route::get('/api/admin/challenges', [AdminChallengeController::class, 'index']);
    Route::post('/api/admin/challenges', [AdminChallengeController::class, 'store']);
    Route::put('/api/admin/challenges/{id}', [AdminChallengeController::class, 'update']);
    Route::delete('/api/admin/challenges/{id}', [AdminChallengeController::class, 'destroy']);

    Route::post('/api/admin/assignChallengeToAllStudents', [AdminChallengeController::class, 'assignChallengeToAllStudents']);

    Route::get('/api/admin/rewards', [AdminRewardController::class, 'index']);
    Route::post('/api/admin/rewards', [AdminRewardController::class, 'store']);
    Route::put('/api/admin/rewards/{id}', [AdminRewardController::class, 'update']);
    Route::delete('/api/admin/rewards/{id}', [AdminRewardController::class, 'destroy']);

    Route::post('/api/admin/decrypt-code', [AdminRewardController::class, 'decryptCode']);

    Route::get('/StudentList', function () {
        return view('admin.students.StudentList');
    })->name('StudentList');
    
    Route::get('/TeacherList', function () {
        return view('admin.teachers.TeacherList');
    })->name('TeacherList');
    
    Route::get('/ParentList', function () {
        return view('admin.parents.ParentList');
    })->name('ParentList');
    
    Route::get('/AdminList', function () {
        return view('admin.admins.AdminList');
    })->name('AdminList');
    
    Route::get('/subjects', function () {
        return view('admin.subjects.subjectList');
    })->name('subjectList');
    
    Route::get('/classes', function () {
        return view('admin.classes.classList');
    })->name('classList');

    Route::get('/admin/challenges', function () {
        return view('admin.challenges.challengeList');
    })->name('challengeList');

    Route::get('/admin/rewards', function () {
        return view('admin.rewards.rewardList');
    })->name('rewardList');
});

Route::middleware(['auth', 'user-role:student'])->group(function(){
    Route::get('/student/grades', function () {
        return view('student.grades.studentGrades');
    })->name('studentGrades');
    Route::get('/api/getStudent/{userId}', [GradeController::class, 'getStudent']);
    Route::get('/api/getClass/{classId}', [GradeController::class, 'getClass']);
    Route::get('/api/getStudentGrades/{studentId}', [GradeController::class, 'getStudentGrades']);

    Route::get('/api/student/challenges/{studentId}', [ChallengeController::class, 'getMyChallenges']);
    Route::put('/api/student/challenges/{studentChallengeId}', [ChallengeController::class, 'updateStudentChallenge']);

    Route::get('/api/student/rewards', [RewardController::class, 'index']);
    Route::get('/api/student/points/{userId}', [RewardController::class, 'getStudentPoints']);
    Route::post('/api/student/points/subtract', [RewardController::class, 'subtractPoints']);
    Route::post('/api/student/decrypt-code', [RewardController::class, 'decryptCode']);
    Route::get('/api/student/purchases/{userId}', [RewardController::class, 'getRewardPurchaseHistory']);

    Route::get('/api/student/leaderboardStudents/{userId}', [LeaderboardController::class, 'getStudents']);
    Route::get('/api/student/homework/{userId}', [HomeworkController::class, 'index']);

    Route::get('/student/challenges', function () {
        return view('student.challenges.studentChallengeList');
    })->name('studentChallengeList');

    Route::get('/student/rewards', function () {
        return view('student.rewards.studentRewardList');
    })->name('studentRewardList');

    Route::get('/student/leaderboard', function () {
        return view('student.leaderboard.studentLeaderboardList');
    })->name('studentLeaderboardList');

    Route::get('/student/homework', function () {
        return view('student.homework.studentHomework');
    })->name('studentHomework');
});

Route::middleware(['auth', 'user-role:teacher'])->group(function(){
    Route::get('/api/teacher/{teacher_id}/classes', [TeacherClassController::class, 'getTeacherClasses']);
    Route::get('/api/teacher/{teacher_id}/classList', [TeacherClassController::class, 'getTeacherClassesJson']);
    Route::get('/teacher-classes/{user_id}/{class_id}/{subject_id}', [TeacherClassController::class, 'subjectGradeList'])
        ->name('subjectGrading');
    Route::get('/teacher-classes', [TeacherClassController::class, 'getTeacherClasses'])->name('teacherClassList');
    Route::get('/api/teacher-classes/{class_id}/{subject_id}/students', [StudentGradesController::class, 'getStudents']);
    Route::get('/api/teacher-classes/{class_id}/{subject_id}/studentsGrades', [StudentGradesController::class, 'getStudentsGrades']);
    Route::post('/api/save-grades',  [StudentGradesController::class, 'saveGrades']);

    Route::get('/api/teacher/{selectedClass}/classLeaderboard', [TeacherClassController::class, 'getClassLeaderboard']);
    Route::get('/api/teacher/homework/{userId}', [TeacherHomeworkController::class, 'index']);

    Route::get('/api/teacher/{teacher_id}/{class_id}/classSubjects', [TeacherClassController::class, 'getTeacherClassSubjects']);
    Route::post('/api/teacher/createHomework', [TeacherHomeworkController::class, 'store']);
    Route::put('/api/teacher/editHomework/{id}', [TeacherHomeworkController::class, 'update']);
    Route::delete('/api/teacher/homework/{id}', [TeacherHomeworkController::class, 'destroy']);

    Route::get('/teacher/leaderboard', function () {
        return view('teacher.leaderboard.teacherLeaderboardList');
    })->name('teacherLeaderboardList');

    Route::get('/teacher/homework', function () {
        return view('teacher.homework.homeworkList');
    })->name('teacherHomework');
});

Route::middleware(['auth', 'user-role:parent'])->group(function(){
    Route::get('/api/getParentStudents/{userId}', [ParentGradeController::class, 'getParentStudents']);
    Route::get('/api/getSelectedStudent/{userId}', [ParentGradeController::class, 'getSelectedStudent']);
    Route::get('/api/getSelectedClass/{classId}', [ParentGradeController::class, 'getSelectedClass']);
    Route::get('/api/getSelectedStudentGrades/{studentId}', [ParentGradeController::class, 'getSelectedStudentGrades']);
    Route::get('/api/parent/leaderboardStudents/{userId}', [ParentLeaderboardController::class, 'getLeaderboardStudents']);
    Route::get('/api/getClassHomework/{classId}', [ParentHomeworkController::class, 'getClassHomework']);


    Route::get('/parent/grades', function () {
        return view('parent.grades.studentGrades');
    })->name('parentGrades');

    Route::get('/parent/homework', function () {
        return view('parent.homework.studentHomework');
    })->name('parentHomework');

    Route::get('/parent/leaderboard', function () {
        return view('parent.leaderboard.studentLeaderboardList');
    })->name('parentLeaderboard');
});


Route::get('{view}', ApplicationController::class)->where('view', '(.*)')->middleware('auth');

