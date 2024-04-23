<template>
    <div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
    <div class="col-sm-6">
    <h1 class="m-0">Rewards</h1>
    </div>
    
    <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active">Rewards</li>
    </ol>
    </div>
    </div>
    </div>
    </div>

    
    <div class="content">
    <div class="container-fluid">
        <div class="card">
        <div class="card-body">
            <div class="points-container align-items-center mb-3">
                <span class="mr-2" style="font-size: 18px;">My points:</span>
                <span style="font-size: 18px;">{{ points }}</span>
                <i class="far fa-gem" style="font-size: 18px;"></i> 
            </div>

        <table class="table table-bordered" v-if="rewards.length > 0">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Valid until</th>
            <th>Price</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="rewardItem in rewards" :key="rewardItem.id">
            <td>{{ rewardItem.id }}</td>
            <td>{{ rewardItem.name }}</td>
            <td>{{ rewardItem.description }}</td>
            <td>{{ rewardItem.valid_until }}</td>
            <td>{{ rewardItem.points_price }} <i class="far fa-gem"></i></td>
            <td>
                <a href="#" @click.prevent="openConfirmRewardModal(rewardItem)"><i class="fa fa-shopping-cart"></i></a>
            </td>
        </tr>
        </tbody>
    </table>
    <p v-else>No rewards available</p>
    </div>
    </div>
    </div>
    </div>


<div class="modal fade" id="editModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">{{ isNewReward ? 'Create Reward' : 'Edit Reward' }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="rewardName" class="form-label">Reward Name</label>
                <input type="text" v-model="editReward.name" class="form-control" id="rewardName">
            </div>
        </div>
        <div class="col-md-6">
        <div class="form-group">
            <label for="rewardDescription" class="form-label">Description</label>
            <input type="text" v-model="editReward.description" class="form-control" id="rewardDescription">
        </div>
    </div>
        
    </div>

    <div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="rewardPointsPrice" class="form-label">Points price</label>
            <input type="number" v-model="editReward.points_price" class="form-control" id="rewardPointsPrice">
        </div>
    </div>
    <div class="col-md-6">
            <div class="form-group">
                <label for="rewardCode" class="form-label">Reward code</label>
                <input type="text" v-model="editReward.code" class="form-control" id="rewardCode">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="rewardValidUntil" class="form-label">Valid until</label>
                <input type="date" v-model="editReward.valid_until" class="form-control" id="rewardValidUntil">
            </div>
        </div>  
</div>


</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button @click="saveChanges" class="btn btn-primary">{{ isNewReward ? 'Create' : 'Save Changes' }}</button>
</div>
</div>
</div>
</div>

</template>
    
<script>
import axios from 'axios';
import { useToastr } from '../../toastr.js';

const toastr = useToastr();

export default {
props: {
    userId: {
    type: Number,
    required: true,
    },
},
data() {
    return {
    rewards: { 
        name: '',
        description: null,
        points_price: '',
        code: '',
        valid_until: '',
    },
    editReward: { name: '' },
    isNewReward: false,
    points: 0
    };
},
created() {
    this.fetchRewards();
    this.fetchPoints();
},

methods: {
    async decryptCode() {
        try {
            const response = await axios.post('/api/decrypt-code', { code: this.givenCode });
            this.editReward.code = response.data;
        } catch (error) {
            console.error('Error decrypting code:', error);
        }
    },
    async fetchRewards() {
    try {
        const response = await axios.get('/api/student/rewards');
        if (Array.isArray(response.data) && response.data.length > 0) {
        this.rewards = response.data;

        } else {
        this.rewards = [];
        }
    } catch (error) {
        console.error('Error fetching rewards:', error);
    }
    },
    async fetchPoints() {
    try {
        const response = await axios.get(`/api/student/points/${this.userId}`);
        this.points = response.data.points;
    } catch (error) {
        console.error('Error fetching points:', error);
    }
    }
}
};
</script>

<style>
    .points-container {
        display: flex;
        align-items: center;
    }

    .points-container span {
        margin-right: 5px;
        margin-left: 5px;
    }
</style>