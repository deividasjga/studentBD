<template>
    <div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
    <div class="col-sm-6">
    <h1 class="m-0">Rewards</h1>
    <br>
    <button @click="isNewReward = true; openRewardFormModal(null)" type="button" class="mb-2 btn btn-primary">
        <i class="fa fa-plus-circle mr-1"></i>
            Add New Reward
    </button>
    </div>
    
    <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="/home">Home</a></li>
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
        <table class="table table-bordered" v-if="rewards.length > 0">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Valid until</th>
            <th>Price</th>
            <th>Options</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="rewardItem in rewards" :key="rewardItem.id">
            <td>{{ rewardItem.id }}</td>
            <td>{{ rewardItem.name }}</td>
            <td>{{ rewardItem.description }}</td>
            <td>{{ rewardItem.valid_until }}</td>
            <td>{{ rewardItem.points_price }} points</td>
            <td>
                <a href="#" @click.prevent="openRewardFormModal(rewardItem)"><i class="fa fa-edit"></i></a>
                <a href="#" @click.prevent="deleteReward(rewardItem.id)" class="ml-2"><i class="fa fa-trash text-danger"></i></a>
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
import { useToastr } from '../../../toastr.js';

const toastr = useToastr();

export default {
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
    isNewReward: false
    };
},
created() {
    this.fetchRewards();
},

methods: {
    async decryptCode() {
        try {
            const response = await axios.post('/api/admin/decrypt-code', { code: this.givenCode });
            this.editReward.code = response.data;
        } catch (error) {
            console.error('Error decrypting code:', error);
        }
    },
    async fetchRewards() {
    try {
        const response = await axios.get('/api/admin/rewards');
        if (Array.isArray(response.data) && response.data.length > 0) {
        this.rewards = response.data;

        } else {
        this.rewards = [];
        }
    } catch (error) {
        console.error('Error fetching rewards:', error);
    }
    },
    async openRewardFormModal(rewardItem) {
    try {
        if (rewardItem) {
            this.isNewReward = false;
            this.editReward = { ...rewardItem };

            this.givenCode = this.editReward.code;
            await this.decryptCode();

        } else {
            this.isNewReward = true;
            this.editReward = { name: '' };
        }
        $('#editModal').modal('show');
    } catch (error) {
        console.error('Error opening reward form modal:', error);
    }
    },
    async saveChanges() {
    try {
        if (this.isNewReward) {
            const response = await axios.post('/api/admin/rewards', this.editReward);
            console.log(this.editReward);
            toastr.success('Reward created successfully.');
        } else {
            await axios.put(`/api/admin/rewards/${this.editReward.id}`, this.editReward);
            toastr.success('Reward updated successfully.');
        }
        this.fetchRewards();
        $('#editModal').modal('hide');
    } catch (error) {
        console.error('Error saving changes:', error);
    }
    },
    deleteReward(id) {
        if (confirm('Are you sure you want to delete this reward?')) {
        axios.delete(`/api/admin/rewards/${id}`)
        .then(() => {
            this.rewards = this.rewards.filter(rewardItem => rewardItem.id !== id);
            toastr.success('Reward deleted successfully.');
        })
        .catch(error => {
            console.error('Error deleting reward:', error);
        });
        }
    }
}
};
</script>
    


