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
                    <th scope="col" style="width: 35px">#</th>
                    <th scope="col">Subject Name</th>
                    <th scope="col">Grades</th>
                    <th scope="col">Average Grade</th>
                    </tr>
                </thead>
                <tbody class="table-bordered">
                    <tr v-for="(subject, index) in classOne.subjects" :key="subject.id">
                    <td>{{ index + 1 }}</td>
                    <td :title="'Subject: ' + subject.name">{{ subject.name }}</td>
                    <td>
                        <ul v-if="getSubjectGrades(subject.id).length > 0">
                        <li v-for="grade in getSubjectGrades(subject.id)" :key="grade.id"
                                                     :title="'Grade: ' + grade.grade +'\nDate: ' + grade.grade_date"
                                                     :class="{ 'text-danger': isNaN(parseFloat(grade.grade)) }">
                            {{ grade.grade }}
                        </li>
                        </ul>
                        <p v-else>No grades available</p>
                    </td>
                    <td style="font-weight: bold;">{{ calculateAverage(subject.id) }}</td>
                    </tr>
                </tbody>
                <tfoot>
          <tr>
            <td colspan="3" class="text-right"><b style="font-size: 1.1rem;">Total Average:</b></td>
            <td style="font-weight: bold;">{{ calculateTotalAverage() }}</td>
          </tr>
        </tfoot>
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
    },
    calculateAverage(subjectId) {
        const grades = this.getSubjectGrades(subjectId).filter(grade => !isNaN(parseFloat(grade.grade)));
        if (grades.length === 0) return 0;
        const sum = grades.reduce((acc, grade) => acc + parseFloat(grade.grade), 0);
        return sum / grades.length;
    },
    calculateTotalAverage() {
        const subjectIds = [...new Set(this.studentGradesList.map(grade => grade.subject_id))];
        const subjectAverage = subjectIds.map(subjectId => this.calculateAverage(subjectId));
        const filteredAverages = subjectAverage.filter(average => average !== 0);
        if(filteredAverages.length === 0) return 0;
        const sum = filteredAverages.reduce((acc, average) => acc + average, 0);
        return sum / filteredAverages.length;
    },  
}
};
</script>
  