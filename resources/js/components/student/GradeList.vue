<template>
    <div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
    <div class="col-sm-6">
    <h1 class="m-0">Class: {{ classOne.name }}</h1>
    </div>
      
    <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="/home">Home</a></li>
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
                    <th scope="col" style="width: 35px; text-align: center;">#</th>
                    <th scope="col" style="text-align: center;">Subject Name</th>
                    <th scope="col" style="text-align: center;">Grades</th>
                    <th scope="col" style="width: 250px; text-align: center;">Average Grade</th>
                    </tr>
                </thead>

                <tbody class="table-bordered">
                    <tr v-for="(subject, index) in classOne.subjects" :key="subject.id">
                    <td>{{ index + 1 }}</td>
                    <td :title="'Subject: ' + subject.name">{{ subject.name }}</td>
                    <td>
                        <ul v-if="getSubjectGrades(subject.id).length > 0" class="gradesList">
                        <li v-for="grade in getSubjectGrades(subject.id)" :key="grade.id"
                            :class="['grade', { 'red-text': grade.grade === 'n' }]"
                            :title="'Grade: ' + grade.grade + '\nDate: ' + grade.grade_date">
                            {{ grade.grade }}
                        </li>
                        </ul>
                        <p v-else>No grades available</p>
                    </td>
                    <td class="averageGrade">{{ calculateAverage(subject.id) }}</td>
                    </tr>
                </tbody>

                <tfoot>
                <tr>
                    <td colspan="3"></td>
                    <td colspan="3" style="text-align: center;"><b style="font-size: 1.1rem;">Total Average Grade: {{ calculateTotalAverage() }}</b></td>
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
        } else {
        this.studentGradesList = [];
        console.log('No grades available');
        }
    } catch (error) {
        console.error('Error fetching Student grades:', error);
    }
    },
    getSubjectGrades(subjectId) {
        return this.studentGradesList.filter(grade => grade.subject_id === subjectId)
            .sort((a, b) => new Date(a.grade_date) - new Date(b.grade_date));
    },
    
    calculateAverage(subjectId) {
        const grades = this.getSubjectGrades(subjectId).filter(grade => !isNaN(parseFloat(grade.grade)));
        if(grades.length === 0) return 0;
        const sum = grades.reduce((acc, grade) => acc + parseFloat(grade.grade), 0);
        return (sum / grades.length).toFixed(2);
    },

    calculateTotalAverage() {
        const subjectIds = [...new Set(this.studentGradesList.map(grade => grade.subject_id))];
        const subjectAverage = subjectIds.map(subjectId => this.calculateAverage(subjectId));
        const filteredAverages = subjectAverage.filter(average => average !== 0);
        if(filteredAverages.length === 0) return 0;
        const sum = filteredAverages.reduce((acc, average) => acc + parseFloat(average), 0);
        const totalAverage = sum / filteredAverages.length;
        return isNaN(totalAverage) ? 0 : totalAverage.toFixed(2);
    },
}
};
</script>
  
<style scoped>
  .gradesList {
    padding: 0;
    margin: 0;
  }
  .gradesList li {
    display: inline-block;
    margin-right: 5px;
    padding: 5px 7px;
    border-radius: 6px;
    border: 1px solid #ccc;
  }
  .grade {
    font-weight: bold;
  }
  .averageGrade {
    background-color: #f0f0f0;
    text-align: center;
    font-weight: bold;
  }

  .red-text {
    color: red;
}
</style>
