<template>
    <div>
        <div class="form-group" style="margin-left: 10px;">
                <label for="studentSelect">Select Student:</label>
                <select v-model="selectedStudentId" class="form-control" id="studentSelect" @change="fetchStudent">
                    <option value="">Select Student</option>
                    <option v-for="myStudent in myStudents" :key="myStudent.id" :value="myStudent.id">{{ myStudent.first_name }} {{ myStudent.last_name }}</option>
                </select>
            </div>
      <h2 style="margin-left: 10px;">Class Leaderboard</h2>
      <table class="table leaderboard-table">
        <thead>
          <tr>
            <th>Place</th>
            <th>Name</th>
            <th>Total Grade Average</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(student, index) in students" :key="student.id" :class="getPlaceNumber(index)">
            <td class="emotePlace">{{ getEmotePlace(index + 1) }}</td>
            <td> {{ student.first_name }} {{ student.last_name }}</td>
            <td>{{ student.total_subject_average.toFixed(2) }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>



<script>
export default {
    props: {
    userId: {
    type: Number,
    required: true,
    },
    },
    data() {
    return {
        selectedStudent: '',
        myStudents: [],
        students: {},
        classOne: {},
        studentId: '',
    };
    },
    mounted() {
    this.fetchParentStudents();
    // this.fetchStudentLeaderboard();
    },
    methods: {
        async fetchParentStudents() {
        try {
            const response = await axios.get(`/api/getParentStudents/${this.userId}`);
            const parentStudents = response.data;
            console.log(response.data);
            
            this.myStudents = parentStudents.map(myStudent => {
                return {
                    id: myStudent.student_id,
                    first_name: myStudent.first_name,
                    last_name: myStudent.last_name
                };
            });
        } catch (error) {
            console.error('Error fetching parent students:', error);
        }
    },
    async fetchStudent() {
        try {
            const selectedStudent = this.myStudents.find(myStudent => myStudent.id === this.selectedStudentId);
            const userId = selectedStudent.id;
            console.log(userId);
            const response = await axios.get(`/api/getSelectedStudent/${userId}`);
            this.myStudent = response.data;
            await this.fetchStudentLeaderboard(userId);
        } catch (error) {
            console.error('Error fetching Student:', error);
        }
    },
    async fetchStudentLeaderboard(userId) {
        try {
        const response = await axios.get(`/api/parent/leaderboardStudents/${userId}`);
        this.students = response.data.sort((a, b) => b.total_subject_average - a.total_subject_average);
        console.log(response.data);
        } catch (error) {
        console.error('Error fetching student leaderboard:', error);
        }
    },
    getPlaceNumber(index) {
      if (index === 0) {
        return 'firstPlace';
      } else if (index === 1) {
        return 'secondPlace';
      } else if (index === 2) {
        return 'thirdPlace';
      }
      return '';
    },
    getEmotePlace(place) {
      switch (place) {
        case 1:
          return '🥇🏆';
        case 2:
          return '🥈';
        case 3:
          return '🥉';
        default:
          return place;
      }
    },

    },
};
</script>

<style scoped>
.leaderboard-table th,
.leaderboard-table td {
  padding: 10px;
  text-align: center;
}


.firstPlace {
  background-color: gold;
}

.secondPlace {
  background-color: silver;
}

.thirdPlace {
  background-color: #cd7f32;
}

.emotePlace {
  font-size: 1.4rem;
}
</style>