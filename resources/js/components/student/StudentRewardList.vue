<template>
    <div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
    <div class="col-sm-6">
    <h1 class="m-0">Rewards</h1>
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
            
            <button class="open-modal-btn btn btn-primary" @click="openRewardPurchaseModal">
                <i class="fas fa-history"></i> View Purchase History
            </button>

            <div class="points-container align-items-center mb-3">
                <span class="mr-2" style="font-size: 18px;">My points:</span>
                <span style="font-size: 18px;">{{ points }}</span>
                <i class="far fa-gem" style="font-size: 18px;"></i> 
            </div>

        <table class="table table-bordered" v-if="rewards.length > 0">
        <thead>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="rewardItem in rewards" :key="rewardItem.id">
            <td>{{ rewardItem.name }}</td>
            <td>{{ rewardItem.description }}</td>
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



<div class="modal fade" id="purchaseHistoryModal" tabindex="-1" role="dialog" aria-labelledby="purchaseHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="purchaseHistoryModalLabel">Reward Purchase History</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul>
                    <li v-for="purchase in rewardPurchases" :key="purchase.id">
                        <div class="purchase-item">
                            <div>{{ purchase.name }}</div>
                            <button class="view-button" @click="viewRewardCode(purchase)">View</button>
                            <div v-if="purchase.showCode" class="reward-code-container">
                                <h5>{{ purchase.name }} code:</h5>
                                <div class="reward-code">{{ purchase.decryptedCode }}</div>
                                <p style="margin-top: 10px">Valid until: {{ purchase.valid_until }}</p>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
</template>
    
<script>
import axios from 'axios';
import { useToastr } from '../../toastr.js';
import { isChallengeCompleted, makeChallengeCompleted } from './challengeHelper';

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
    purchased: false,
    rewardPurchases: [],


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
created() {
    this.fetchStudent();
    this.fetchChallenges();
    this.checkAndUpdateCompletedChallenges();

    this.fetchRewards();
    this.fetchPoints();
},

methods: {
    async decryptCode() {
        try {
            const response = await axios.post('/api/student/decrypt-code', { code: this.givenCode });
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
            points_price: this.rewardItem.points_price,
            reward_id: this.rewardItem.id
        });

        if (response.status === 200) {
            this.givenCode = this.rewardItem.code;
            this.fetchPoints();
            await this.decryptCode();
            this.purchased = true;
            const index = this.rewards.findIndex(reward => reward.id === this.rewardItem.id);
            if (index !== -1) {
                this.rewards.splice(index, 1);
            }

        } else {
            toastr.error('Purchase failed');
        }
    } catch (error) {
        console.error('Error buying reward:', error);
    }
    },
    closeModal() {
      $('#confirmModal').modal('hide');
    },


    async openRewardPurchaseModal() {
      try {
        const response = await axios.get(`/api/student/purchases/${this.userId}`);
        this.rewardPurchases = response.data;
        $('#purchaseHistoryModal').modal('show');
        console.log(this.rewardPurchases);
      } catch (error) {
        console.error('Error fetching purchase history:', error);
      }
    },
    async viewRewardCode(purchase) {
        purchase.showCode = !purchase.showCode;
        this.givenCode = purchase.code;
        await this.decryptCodeInHistory(purchase);
    },
    async decryptCodeInHistory(purchase) {
        try {
            const response = await axios.post('/api/student/decrypt-code', { code: this.givenCode });
            purchase.decryptedCode = response.data;
        } catch (error) {
            console.error('Error decrypting code:', error);
        }
    },

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

  .open-modal-btn {
    float: right;
    margin-right: 10px;
    margin-bottom: 25px;
  }

  .purchase-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    border-bottom: 1px solid #ccc;
  }

  .view-button {
    background-color: #1785fa;
    color: #fff;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
  }

  .view-button:hover {
    background-color: #1b6ec6;
  }

  .reward-code-container {
    border: 1px solid #ccccccfa;
    padding: 10px;
    margin-top: 10px;
  }


</style>

