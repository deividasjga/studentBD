<template>
    <div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
    <div class="col-sm-6">
    <h1 class="m-0">Challenges</h1>
    <br>
    <button @click="isNewChallenge = true; openClassFormModal(null)" type="button" class="mb-2 btn btn-primary">
        <i class="fa fa-plus-circle mr-1"></i>
            Add New Challenge
    </button>
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
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="challengeItem in challenges" :key="challengeItem.id">
            <td>{{ challengeItem.id }}</td>
            <td>{{ challengeItem.name }}</td>
            <td>{{ challengeItem.description }}</td>
            <td>{{ challengeItem.reward_points }}</td>
            <td>
            <a href="#" @click.prevent="openClassFormModal(challengeItem)"><i class="fa fa-edit"></i></a>
            <a href="#" @click.prevent="deleteChallenge(challengeItem.id)"><i class="fa fa-trash text-danger ml-2"></i></a>
            </td>
        </tr>
        </tbody>
    </table>
    <p v-else>No challenges available</p>
    </div>
    </div>
    </div>
    </div>



<div class="modal fade" id="editModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">{{ isNewChallenge ? 'Create Challenge' : 'Edit Challenge' }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="challengeName" class="form-label">Challenge Name</label>
                <input type="text" v-model="editChallenge.name" class="form-control" id="challengeName">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="challengeType" class="form-label">Challenge Type</label>
                <select v-model="editChallenge.challenge_type" class="form-control" id="challengeType">
                    <option value="1">Grade Average</option>
                    <option value="2">Grade Count</option>
                    <option value="3">Attendance</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="challengeDescription" class="form-label">Description</label>
            <input type="text" v-model="editChallenge.description" class="form-control" id="challengeDescription">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="challengeRewardPoints" class="form-label">Reward points</label>
            <input type="number" v-model="editChallenge.reward_points" class="form-control" id="challengeRewardPoints">
        </div>
    </div>
</div>

<div v-if="editChallenge.challenge_type === '1' || editChallenge.challenge_type === '2' || isNewChallenge"  class="mb-3">
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="challengeMinimumGrade" class="form-label">Minimum grade</label>
            <input type="number" v-model="editChallenge.minimum_grade" class="form-control" id="challengeMinimumGrade">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="challengeGradeCount" class="form-label">Grade count</label>
            <input type="number" v-model="editChallenge.grade_count" class="form-control" id="challengeGradeCount">
        </div>
    </div>
</div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="challengeStartDate" class="form-label">Start date</label>
            <input type="date" v-model="editChallenge.start_date" class="form-control" id="challengeStartDate">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="challengeEndDate" class="form-label">End date</label>
            <input type="date" v-model="editChallenge.end_date" class="form-control" id="challengeEndDate">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <div v-if="editChallenge.challenge_type === '1' || editChallenge.challenge_type === '2'" class="mb-3">
                <div class="form-group">
                    <label for="subject" class="form-label">Subject</label>
                    <select v-model="editChallenge.subject_id" class="form-control" id="subject">
                        <option v-for="subject in subjects" :key="subject.id" :value="subject.id">{{ subject.name }}</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button @click="saveChanges" class="btn btn-primary">{{ isNewChallenge ? 'Create' : 'Save Changes' }}</button>
</div>
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
    challenges: { 
        name: '',
        description: '',
        challenge_type: '',
        subject_id: '',
        grade_count: null,
        minimum_grade: null,
        start_date: '',
        end_date: '',
        reward_points: null
    },
    subjects: [],
    editChallenge: { name: '' },
    isNewChallenge: false
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
    },
    openClassFormModal(challengeItem) {
        if (challengeItem) {
        this.isNewChallenge = false;
        this.editChallenge = { ...challengeItem,
            challenge_type: challengeItem.challenge_type.toString() };
        } else {
        this.isNewChallenge = true;
        this.editChallenge = { name: '' };
        }
        $('#editModal').modal('show');
    },
    async saveChanges() {
    try {
        if (this.isNewChallenge) {
            const response = await axios.post('/api/admin/challenges', this.editChallenge);
            console.log(this.editChallenge);
            toastr.success('Challenge created successfully.');
        } else {
            await axios.put(`/api/admin/challenges/${this.editChallenge.id}`, this.editChallenge);
            toastr.success('Challenge updated successfully.');
        }
        this.fetchChallenges();
        $('#editModal').modal('hide');
    } catch (error) {
        console.error('Error saving changes:', error);
    }
    },
    deleteChallenge(id) {
        if (confirm('Are you sure you want to delete this challenge?')) {
        axios.delete(`/api/admin/challenges/${id}`)
        .then(() => {
            this.challenges = this.challenges.filter(challengeItem => challengeItem.id !== id);
            toastr.success('Challenge deleted successfully.');
        })
        .catch(error => {
            console.error('Error deleting challenge:', error);
        });
        }
    }
}
};
</script>
    


