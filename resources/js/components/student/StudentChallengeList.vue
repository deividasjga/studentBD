<template>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Challenges</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/home">Home</a></li>
              <li class="breadcrumb-item active">Challenges</li>
            </ol>
          </div>
        </div>
      </div>
    </div>


    <div>
    <ul class="list-group">
        <li v-for="challenge in challenges" :key="challenge.id" class="list-group-item">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3><i class="fas fa-star"></i> {{ challenge.name }}</h3>
                    <p>{{ challenge.description }}</p>
                    <p><strong>Start Date:</strong> {{ formatDate(challenge.start_date) }}</p>
                    <p><strong>End Date:</strong> {{ formatDate(challenge.end_date) }}</p>
                </div>
                <div>
                    <span class="badge bg-primary mx-1 p-2" style="font-size: 1rem;">{{ challenge.reward_points }} <i class="far fa-gem"></i></span>
                    <span v-if="challenge.student_challenges.some(sc => sc.completed)" class="badge bg-success mx-1 p-2" style="font-size: 1rem;">Completed</span>
                    <span v-else class="badge bg-secondary mx-1 p-2" style="font-size: 1rem;">{{ daysLeft(challenge.end_date) }}</span>
                </div>
            </div>
        </li>
    </ul>
</div>



  </template>
  
  <script>
  import axios from "axios";
  import { formatDate, formatDateDash } from '../../helper.js';
  import { isChallengeCompleted, makeChallengeCompleted } from './challengeHelper';
  
  export default {
    props: {
      userId: {
        type: Number,
        required: true,
      },
    },
    data() {
      return {
        student: {},
        challenges: [],
        selectedType: null,
        challengeTypes: [
          { value: 1, description: "Grade average" },
          { value: 2, description: "Grade count" },
          { value: 3, description: "Attendance" },
        ],
      };
    },
    mounted() {
      this.fetchStudent();
      this.fetchChallenges();
      this.checkAndUpdateCompletedChallenges();
    },
    methods: {
      async fetchStudent() {
        try {
          const response = await axios.get(`/api/getStudent/${this.userId}`);
          this.student = response.data;
          await this.fetchStudentGrades(this.userId);
        } catch (error) {
          console.error("Error fetching Student:", error);
        }
      },
      async fetchStudentGrades(userId) {
        try {
            const studentId = userId;
            const response = await axios.get(`/api/getStudentGrades/${studentId}`);
            this.student.studentGrades = response.data;
        } catch (error) {
            console.error("Error fetching Student grades:", error);
        }
        },
      async fetchChallenges() {
        try {
          const response = await axios.get(
            `/api/student/challenges/${this.userId}`
          );
          this.challenges = response.data;
          
        } catch (error) {
          console.error("Error fetching Challenges:", error);
        }
      },
      getChallengeTypeDescription(type) {
        const foundType = this.challengeTypes.find(
          (item) => item.value === type
        );
        return foundType ? foundType.description : "Unknown";
      },
      async checkAndUpdateCompletedChallenges() {
      try {
        const response = await axios.get(`/api/student/challenges/${this.userId}`);
        const challenges = response.data;
        for (const challenge of challenges) {
          if (isChallengeCompleted(this.student, challenge)) {
            await makeChallengeCompleted(this.student, challenge);
          }
        }
      } catch (error) {
        console.error("Error checking completed challenges:", error);
      }
    },
    daysLeft(endDate) {
      const today = new Date();
      const endDay = new Date(endDate);
      const diffTime = endDay - today;
      const diffDays = Math.ceil(diffTime / (1000*60*60*24));
      if (diffDays <= 0) {
        return "Challenge ended";
      } else if (diffDays === 1) {
        return "Time left: " + diffDays + " day";
      } else {
        return "Time left: " + diffDays + " days";
      }
    },
    formatDate(date) {
      return formatDate(date);
    }
  },
  };
  </script>
  