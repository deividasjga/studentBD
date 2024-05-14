<template>
  <div class="container">
    <div class="grades-table-wrapper" ref="tableWrapper">  
      <div class="grades-table">
        <table>
          <thead>
            <tr>
              <th class="student-info">Student</th>
              <th v-for="date in dates" :key="date" class="date-cell">{{ formatGradeDate(date) }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(student, index) in students" :key="student.id">
              <td class="student-info">{{ index + 1 }}. {{ student.first_name }} {{ student.last_name }}</td>
              <td v-for="date in dates" :key="date">
                <input type="text" v-model="studentGrades[index][date]" placeholder="-">
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <br>
  <div style="display: flex; justify-content: flex-end; margin-right: 80px;">
    <a href="#" @click.prevent="saveGrades(index)" class="save-button">
      <span>Save </span><i class="fas fa-save"></i>
    </a>
  </div>
</template>


<script>
import { useToastr } from '../../../toastr.js';
import { formatGradeDate } from '../../../helper.js';
const toastr = useToastr();

export default {
  props: {
    user_id: {
      type: Number,
      required: true
    },
    class_id: {
      type: Number,
      required: true
    },
    subject_id: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      students: [],
      dates: [],
      studentGrades: []
    };
  },
  mounted() {
    this.fetchStudents();
    this.generateDates();
    this.fetchStudentsGrades();
  },
  methods: {
    async fetchStudents() {
      try {
        const response = await axios.get(`/api/teacher-classes/${this.class_id}/${this.subject_id}/students`);
        this.students = response.data;

        this.studentGrades = this.students.map(student => ({
          id: student.id,
          grades: Object.fromEntries(this.dates.map(date => [date, '']))
        }));
        this.$refs.tableWrapper.scrollLeft = this.$refs.tableWrapper.scrollWidth;
      } catch (error) {
        console.error('Error fetching students:', error);
      }
    },
    generateDates() {
      const today = new Date();
      const dates = [];

      for (let i = 0; i < 90; i++) {
        const date = new Date(today);
        date.setDate(today.getDate() - i);
        const formattedDate = date.toISOString().slice(0, 10);
        dates.unshift(formattedDate);
      }
      this.dates = dates;
    },
    async fetchStudentsGrades() {
      try {
        const response = await axios.get(`/api/teacher-classes/${this.class_id}/${this.subject_id}/studentsGrades`);
        const allStudentsGrades = response.data;
        allStudentsGrades.forEach(studentGrade => {
          const studentIndex = this.students.findIndex(student => student.id === studentGrade.student_id);
          if (studentIndex !== -1) {
            const dateIndex = this.dates.findIndex(date => date === studentGrade.grade_date);
            if (dateIndex !== -1) {
              if (this.studentGrades[studentIndex][this.dates[dateIndex]]) {
                this.studentGrades[studentIndex][this.dates[dateIndex]] += `, ${studentGrade.grade}`;
              } else {
                this.studentGrades[studentIndex][this.dates[dateIndex]] = studentGrade.grade;
              }
            }
          }
        });
      } catch (error) {
        console.error('Error fetching students grades:', error);
      }
    },
    async saveGrades() {
      try {
        const gradeData = [];

        for (let i = 0; i < this.students.length; i++) {
          const student = this.students[i];
          const grades = this.studentGrades[i];

          for (const date in grades) {
            if (grades.hasOwnProperty(date) && date !== 'id' && date !== 'grades') {
              const gradeArray = grades[date].split(',').map(g => g.trim());
              for (const grade of gradeArray) {
                if (grade) {
                  gradeData.push({
                    teacher_id: this.user_id,
                    student_id: student.id,
                    class_id: this.class_id,
                    subject_id: this.subject_id,
                    grade_date: date,
                    grade: grade
                  });
                }
              }
            }
          }
        }
        await axios.post('/api/save-grades', gradeData);
        toastr.success('Grades saved successfully.');
      } catch (error) {
        toastr.error('Failed to save grades.');
      }
    },
    formatGradeDate(date) {
      return formatGradeDate(date);
    }

  }
};
</script>

<style scoped>
.container {
  display: flex;
}

.grades-table-wrapper {
  flex: 1;
  overflow-x: auto;
}

.grades-table {
  width: max-content;
}

table {
  width: 100%;
}

th, td {
  padding: 10px;
}

.student-info, .action-info {
  position: sticky;
}

.student-info {
  left: 0;
  background-color: white;
  z-index: 2;
}

.action-info {
  left: 185px;
  background-color: white;
  z-index: 2;
}

thead th {
  text-align: left;
}

.date-cell {
  font-size: 12px;
  width: 30px;
}

input[type="text"] {
  width: 40px;
  font-size: 15px;
}


.save-button {
  background-color: #0879f1;
  color: #fff;
  padding: 5px 25px;
  border-radius: 10px;
  text-decoration: none;
  transition: background-color 0.2s;
}

.save-button:hover {
  background-color: #024389;
}
</style>