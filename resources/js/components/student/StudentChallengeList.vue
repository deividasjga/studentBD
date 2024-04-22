<template>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Challenges</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Challenges</li>
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
              <h2>My Challenges</h2>
              <ul>
                <li v-for="challenge in challenges" :key="challenge.id">
                  {{ challenge.name }} - {{ challenge.description }}
                  type: ({{ challenge.challenge_type }})
                  - {{ getChallengeTypeDescription(challenge.challenge_type) }}
                  - Reward points: {{ challenge.reward_points }}
                  <ul>
                    <li v-for="studentChallenge in challenge.student_challenges" :key="studentChallenge.id">
                      Progress: {{ studentChallenge.progress }}
                    </li>
                  </ul>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import axios from "axios";
  
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
          const response = await axios.get(
            `/api/student/challenges/${this.userId}`
          );
          const challenges = response.data;
          for (const challenge of challenges) {
            if (this.isChallengeCompleted(challenge)) {
              await this.makeChallengeCompleted(challenge);
            }
          }
        } catch (error) {
          console.error("Error checking completed challenges:", error);
        }
    },
    isChallengeCompleted(challenge) {
        if (challenge.challenge_type === 2) {
            const requiredCount = challenge.grade_count;
            const grades = this.student.studentGrades.filter(
                (grade) => {
                    const gradeValue = parseInt(grade.grade);
                    const meetsCondition = grade.subject_id === challenge.subject_id &&
                                            gradeValue >= challenge.minimum_grade &&
                                            grade.grade_date >= challenge.start_date &&
                                            grade.grade_date <= challenge.end_date;
                    return meetsCondition;
                }
            );
            return grades.length >= requiredCount;
        }
    },
    async makeChallengeCompleted(challenge) {
        const studentChallenge = challenge.student_challenges.find(
            (sc) => sc.student_id === this.userId
        );
        if (studentChallenge && !studentChallenge.completed) {
            studentChallenge.completed = true;
            await axios.put(`/api/student/challenges/${studentChallenge.id}`, studentChallenge);
        }
      },
    },
  };
  </script>
  