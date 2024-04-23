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


<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmModalLabel">{{ purchased ? 'Purchased' : 'Confirm Purchase' }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div v-if="!purchased">
          <p style="margin-bottom: 20px">Are you sure you want to buy this reward for {{ rewardItem ? rewardItem.points_price : '-' }} <i class="far fa-gem"></i> ?</p>
          <p><strong>Name:</strong> {{ rewardItem ? rewardItem.name : '-' }}</p>
          <p><strong>Description:</strong> {{ rewardItem ? rewardItem.description : '-' }}</p>
        </div>
        <div v-else>
          <div class="alert alert-success" role="alert">
            Successfully purchased!
          </div>
          <div class="reward-code-container">
            <h5>{{ rewardItem.name }} code:</h5>
            <div class="reward-code">{{ rewardItem.code }}</div>
            <p style="margin-top: 10px">Valid until: {{ rewardItem.valid_until }}</p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button v-if="!purchased" type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button v-else type="button" class="btn btn-secondary" @click="closeModal">Close</button>
        <button v-if="!purchased" type="button" class="btn btn-primary" @click="buyReward">Buy</button>
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
    points: 0,
    rewardItem: null,
    purchased: false
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
            this.rewardItem.code = response.data;
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
    },
    openConfirmRewardModal(rewardItem) {
        this.rewardItem = rewardItem;
        const requiredPoints = rewardItem.points_price;
        if (this.points >= requiredPoints) {
            this.purchased = false;
            $('#confirmModal').modal('show');
        } else {
            toastr.info('Not enough points');
        }
    },
    async buyReward() {
    try {
        const response = await axios.post('/api/student/points/subtract', {
            userId: this.userId,
            points_price: this.rewardItem.points_price
        });

        if (response.status === 200) {
            this.givenCode = this.rewardItem.code;
            this.fetchPoints();
            await this.decryptCode();
            this.purchased = true;
        } else {
            toastr.error('Purchase failed');
        }
    } catch (error) {
        console.error('Error buying reward:', error);
    }
    },
    closeModal() {
      $('#confirmModal').modal('hide');
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


    .reward-code-container {
    text-align: center;
    margin-top: 24px;
    }

    .reward-code {
    font-size: 25px;
    padding: 12px 22px;
    border: 3px solid #3fe1e3;
    border-radius: 8px;
    }

</style>