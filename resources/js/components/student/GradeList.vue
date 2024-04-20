<template>
    <div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
    <div class="col-sm-6">
    <h1 class="m-0">Class: {{ classOne.name }}</h1>
    </div>
      
    <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active">My grades</li>
    </ol>
    </div>
    </div>
    </div>
    </div>
    <div class="content">
    <div class="container-fluid">
        <div class="card">
        <div class="card-body">

            <div>
            <div class="table-responsive">
                <table class="table table-sm">
                <thead class="thead-dark">
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Subject Name</th>
                    <th scope="col">Grades</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(subject, index) in classOne.subjects" :key="subject.id">
                    <td>{{ index + 1 }}</td>
                    <td>{{ subject.name }}</td>
                    <td>
                        <ul v-if="getSubjectGrades(subject.id).length > 0">
                        <li v-for="grade in getSubjectGrades(subject.id)" :key="grade.id">
                            {{ grade.grade }}
                        </li>
                        </ul>
                        <p v-else>No grades available</p>
                    </td>
                    </tr>
                </tbody>
                </table>
            </div>
            </div>

    </div>
    </div>
    </div>
    </div>
</template>
    
  
<script>
import axios from 'axios';

export default {
props: {
    userId: {
    type: Number,
    required: true
    }
},
data() {
    return {
    student: {},
    classOne: {},
    studentGradesList: []
    };
},
mounted() {
    this.fetchStudent();
    this.fetchStudentGrades();
},
methods: {
    async fetchStudent() {
    try {
        const response = await axios.get(`/api/getStudent/${this.userId}`);
        this.student = response.data;
        await this.fetchClassInfo(this.student.student_class_id);
    } catch (error) {
        console.error('Error fetching Student:', error);
    }
    },
    async fetchClassInfo(classId) {
    try {
        const response = await axios.get(`/api/getClass/${classId}`);
        this.classOne = response.data;
    } catch (error) {
        console.error('Error fetching Class info:', error);
    }
    },
    async fetchStudentGrades() {
    try {
        const response = await axios.get(`/api/getStudentGrades/${this.userId}`);
        if (response.data && Array.isArray(response.data)) {
        this.studentGradesList = response.data;
        console.log('Student grades:', this.studentGradesList);
        } else {
        this.studentGradesList = [];
        console.log('No grades available');
        }
    } catch (error) {
        console.error('Error fetching Student grades:', error);
    }
    },
    getSubjectGrades(subjectId) {
        return this.studentGradesList.filter(grade => grade.subject_id === subjectId);
    }
}
};
</script>
  