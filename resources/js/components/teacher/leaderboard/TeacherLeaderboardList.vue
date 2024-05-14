<template>

<div>
  <h2 style="margin-left: 10px;">Teacher Classes</h2>
  <label for="classSelect" style="margin-left: 30px;">Select a class:</label>
  <select id="classSelect" v-model="selectedClass" @change="fetchClassLeaderboard">
      <option v-for="classItem in teacherClasses" :value="classItem.id" :key="classItem.id">
          {{ classItem.name }}
      </option>
  </select>


  <div>
  <br>
  <h2 style="margin-left: 10px;">Class Leaderboard</h2>
  <table v-if="students.length > 0" class="table leaderboard-table">
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
  <p v-else style="margin-left: 30px;">No students in this class.</p>
</div>


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
        students: [],
        studentId: '',
        teacherClasses: [],
    };
    },
    mounted() {
      this.fetchClasses();
    },
    methods: {
      async fetchClasses() {
        try {
        const response = await axios.get(`/api/teacher/${this.userId}/classList`);
        this.teacherClasses = response.data;
        console.log(this.teacherClasses);
        } catch (error) {
        console.error('Error fetching teacher classes:', error);
        }
    },
    async fetchClassLeaderboard() {
        try {
        const response = await axios.get(`/api/teacher/${this.selectedClass}/classLeaderboard`);
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