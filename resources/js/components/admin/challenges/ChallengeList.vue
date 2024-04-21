<template>
    <div class="content-header">
      <div class="container-fluid">
      <div class="row mb-2">
      <div class="col-sm-6">
      <h1 class="m-0">Challenges</h1>
      <br>
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
                      <table class="table table-bordered" v-if="challenges.length > 0">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Description</th>
              <th>Reward points</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="challengeItem in challenges" :key="challengeItem.id">
              <td>{{ challengeItem.id }}</td>
              <td>{{ challengeItem.name }}</td>
              <td>{{ challengeItem.description }}</td>
              <td>{{ challengeItem.reward_points }}</td>
            </tr>
          </tbody>
        </table>
        <p v-else>No challenges available</p>
      </div>
      </div>
      </div>
      </div>
  
    </template>
    
  <script>
  import axios from 'axios';
  import { useToastr } from '../../../toastr.js';
  
  const toastr = useToastr();
  
  export default {
    data() {
      return {
        challenges: [],
        subjects: []
      };
    },
    created() {
        this.fetchChallenges();
        this.fetchSubjects();
    },
  
    methods: {
        async fetchChallenges() {
        try {
          const response = await axios.get('/api/admin/challenges');
          if (Array.isArray(response.data) && response.data.length > 0) {
            this.challenges = response.data;
            console.log(response.data);
          } else {
            this.challenges = [];
          }
        } catch (error) {
          console.error('Error fetching challenges:', error);
        }
      },
        fetchSubjects() {
          fetch('/api/subjects')
            .then(response => response.json())
            .then(data => {
              this.subjects = data;
            })
            .catch(error => {
              console.error('Error fetching subjects:', error);
            });
        }
    }
  };
  </script>
    


