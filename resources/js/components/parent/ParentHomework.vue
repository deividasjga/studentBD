<template>
    <div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
    <div class="col-sm-6">
        <h2 style="display: inline-block;">Homework</h2>

        <div class="dateClassFilters">
            <label for="startDate" style="margin-left: 30px;">Start Date:</label>
            <input type="date" id="startDate" v-model="startDate" class="dateFilter">
            <label for="endDate" style="margin-left: 20px;">End Date:</label>
            <input type="date" id="endDate" v-model="endDate" class="dateFilter">

            <label for="studentSelect" style="margin-left: 10px;">Select Student:</label>
            <select v-model="selectedStudentId" class="form-control" id="studentSelect" @change="fetchStudent">
                <option value="">Select Student</option>
                <option v-for="student in students" :key="student.id" :value="student.id">{{ student.first_name }} {{ student.last_name }}</option>
            </select>
        </div>
    </div>
    
    <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active">Homework</li>
    </ol>
    </div>
    </div>

    </div>
    </div>
    <div>
    <div class="homework-container">
        <div v-for="(hw, index) in filteredHomework" :key="index" class="homework-card">
        <div class="card">
            <div class="card-body">
            <h4 class="due-date">Due: {{ hw.end_date }}</h4>
            <h5 class="card-title">{{ hw.subject.name }}</h5>
                    <h5 class="teacher-name">( {{ hw.teacher.first_name }} {{ hw.teacher.last_name }})</h5>
            <p class="card-text">
                <span class="description">{{ hw.description }}</span>
            </p>
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
    selectedStudent: '',
    students: [],
    student: {},
    studentGradesList: [],
    classHomework: [],
    startDate: '',
    endDate: '',
    };
},
mounted() {
    this.fetchParentStudents();
},
computed: {
filteredHomework() {
      const start = this.startDate ? new Date(this.startDate) : null;
      const end = this.endDate ? new Date(this.endDate) : null;
      return this.classHomework.filter(hw => {
        const dueDate = new Date(hw.end_date);
        const dateInRange = (!start || dueDate >= start) && (!end || dueDate <= end);
        return dateInRange;
      });
    }
  },
methods: {
    async fetchParentStudents() {
        try {
            const response = await axios.get(`/api/getParentStudents/${this.userId}`);
            const parentStudents = response.data;
            this.students = parentStudents.map(student => {
                return {
                    id: student.student_id,
                    first_name: student.first_name,
                    last_name: student.last_name
                };
            });
        } catch (error) {
            console.error('Error fetching parent students:', error);
        }
    },
    async fetchStudent() {
        try {
            const selectedStudent = this.students.find(student => student.id === this.selectedStudentId);
            const userId = selectedStudent.id;
            const response = await axios.get(`/api/getSelectedStudent/${userId}`);
            this.student = response.data;
            await this.fetchClassHomework(this.student.student_class_id);
        } catch (error) {
            console.error('Error fetching Student:', error);
        }
    },
    async fetchClassHomework(classId) {
    try {
        const response = await axios.get(`/api/getClassHomework/${classId}`);
        this.classHomework = response.data;
    } catch (error) {
        console.error('Error fetching Class info:', error);
    }
    },
}
};
</script>


<style scoped>
.homework-container {
display: flex;
flex-wrap: wrap;
gap: 0px;
}

.homework-card {
margin-left: 40px;
flex: 0 0 80%;
}

.card {
background-color: #fff;
border: 2px solid #ccc;
border-radius: 5px;
}

.card-body {
padding: 10px;
}

.card-title {
font-size: 1.2rem;
font-weight: bold;
margin-right: 8px;
margin-bottom: 10px;
}

.teacher-name {
font-weight: bold;
font-style: italic;
margin-bottom: 1px;
margin-top: 3px;
margin-left: 15px;
font-size: 1rem;
color: #323232;
}

.description {
margin-left: 15px;
margin-bottom: 5px;
}

.due-date {
float: right;
margin-right: 15px;
font-style: italic;
color: #050000;
}

.dateClassFilters {
margin-bottom: 20px;
display: flex;
align-items: center;
}

.dateClassFilters label {
margin-right: 10px;
}

.dateFilter {
border-radius: 5px;
padding: 5px;
border: 1px solid #cccccc;
}

.classFilter {
  border-radius: 5px;
  padding: 5px;
  border: 1px solid #cccccc;
}
</style>