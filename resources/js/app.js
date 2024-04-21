import './bootstrap';

import 'admin-lte/plugins/jquery/jquery.min.js';
import 'admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js';
import 'admin-lte/dist/js/adminlte.min.js';
import { createApp } from 'vue/dist/vue.esm-bundler.js';
import { createRouter, createWebHistory } from 'vue-router';
import Routes from './routes.js';

import Test from './components/Test.vue';
import StudentList from './components/admin/students/StudentList.vue';
import SubjectList from './components/admin/subjects/SubjectList.vue';
import ClassList from './components/admin/classes/ClassList.vue';
import TeacherList from './components/admin/teachers/TeacherList.vue';
import ParentList from './components/admin/parents/ParentList.vue';
import AdminList from './components/admin/admins/AdminList.vue';
import TeacherSubjectGrade from './components/teacher/classes/TeacherSubjectGrade.vue';
import GradeList from './components/student/GradeList.vue';
import ChallengeList from './components/admin/challenges/ChallengeList.vue';

const app = createApp({});

const router = createRouter({
    routes: Routes,
    history: createWebHistory(),
});

app.use(router);

app.component('test-component', Test)
    .component('student-list-component', StudentList)
    .component('subject-component', SubjectList)
    .component('class-component', ClassList)
    .component('teacher-list-component', TeacherList)
    .component('parent-list-component', ParentList)
    .component('admin-list-component', AdminList)
    .component('teacher-subject-grading-component', TeacherSubjectGrade)
    .component('student-grades-component', GradeList)
    .component('admin-challenge-component', ChallengeList);

app.mount('#app');
