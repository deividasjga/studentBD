import './bootstrap';

import 'admin-lte/plugins/jquery/jquery.min.js';
import 'admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js';
import 'admin-lte/dist/js/adminlte.min.js';
import { createApp } from 'vue/dist/vue.esm-bundler.js';
import { createRouter, createWebHistory } from 'vue-router';
import Routes from './routes.js';

import HomePage from './components/HomePage.vue';
import StudentList from './components/admin/students/StudentList.vue';
import SubjectList from './components/admin/subjects/SubjectList.vue';
import ClassList from './components/admin/classes/ClassList.vue';
import TeacherList from './components/admin/teachers/TeacherList.vue';
import ParentList from './components/admin/parents/ParentList.vue';
import AdminList from './components/admin/admins/AdminList.vue';
import TeacherSubjectGrade from './components/teacher/classes/TeacherSubjectGrade.vue';
import GradeList from './components/student/GradeList.vue';
import ChallengeList from './components/admin/challenges/ChallengeList.vue';
import StudentChallengeList from './components/student/StudentChallengeList.vue';
import RewardList from './components/admin/rewards/RewardList.vue';
import StudentRewardList from './components/student/StudentRewardList.vue';
import StudentLeaderboardList from './components/student/StudentLeaderboardList.vue';
import TeacherLeaderboardList from './components/teacher/leaderboard/TeacherLeaderboardList.vue';
import TeacherHomework from './components/teacher/homework/TeacherHomework.vue';
import ParentGrades from './components/parent/parentGrades.vue';
import ParentHomework from './components/parent/ParentHomework.vue';
import ParentLeaderboard from './components/parent/ParentLeaderboard.vue';
import StudentHomework from './components/student/StudentHomework.vue';

const app = createApp({});

const router = createRouter({
    routes: Routes,
    history: createWebHistory(),
});

app.use(router);

app.component('home-page-component', HomePage)
    .component('student-list-component', StudentList)
    .component('subject-component', SubjectList)
    .component('class-component', ClassList)
    .component('teacher-list-component', TeacherList)
    .component('parent-list-component', ParentList)
    .component('admin-list-component', AdminList)
    .component('teacher-subject-grading-component', TeacherSubjectGrade)
    .component('student-grades-component', GradeList)
    .component('admin-challenge-component', ChallengeList)
    .component('student-challenges-component', StudentChallengeList)
    .component('admin-reward-component', RewardList)
    .component('student-rewards-component', StudentRewardList)
    .component('student-leaderboard-component', StudentLeaderboardList)
    .component('teacher-leaderboard-component', TeacherLeaderboardList)
    .component('teacher-homework-component', TeacherHomework)
    .component('student-homework-component', StudentHomework)
    .component('parent-grades-component', ParentGrades)
    .component('parent-homework-component', ParentHomework)
    .component('parent-leaderboard-component', ParentLeaderboard);

app.mount('#app');
