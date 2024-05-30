<template>
    <div class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-6">
    <h2>Homework</h2>
  <div class="filter">
    <label for="start-date" style="margin-left: 30px;">Start Date:</label>
    <input type="date" id="start-date" v-model="startDate" class="date-input">
    <label for="end-date" style="margin-left: 20px;">End Date:</label>
    <input type="date" id="end-date" v-model="endDate" class="date-input">
  </div>
</div>
  
<div class="col-sm-6">
<ol class="breadcrumb float-sm-right">
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item active">Homework</li>
</ol>
</div>
</div>

</div>
</div>
<div>

  <div class="homework-container">
    <div v-for="(hw, index) in filteredHomework" :key="index" class="homework-card">
      <div class="card">
        <div class="card-body">
          <h4 class="due-date">Due: {{ hw.end_date }}</h4>
          <h5 class="card-title">{{ hw.subject.name }}</h5>
                <h5 class="teacher-name">({{ hw.teacher.first_name }} {{ hw.teacher.last_name }})</h5>
          <p class="card-text">
            <span class="description">{{ hw.description }}</span>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
</template>

<script>
export default {
  props: {
    userId: {
    type: Number,
    required: true
    }
},
data() {
  return {
    homework: [],
    startDate: '',
    endDate: ''
  };
},
mounted() {
    this.fetchHomework();
},
computed: {
  filteredHomework() {
    if (!this.startDate || !this.endDate) return this.homework;
    const start = new Date(this.startDate);
    const end = new Date(this.endDate);
    return this.homework.filter(hw => {
      const dueDate = new Date(hw.end_date);
      return dueDate >= start && dueDate <= end;
    });
  }
},
methods: {
  async fetchHomework() {
    try {
      const response = await axios.get(`/api/student/homework/${this.userId}`);
      this.homework = response.data;
    } catch (error) {
      console.error("Error fetching homework:", error);
    }
  },
    
  filterHomework() {
    if (!this.startDate || !this.endDate) return;
    const start = new Date(this.startDate);
    const end = new Date(this.endDate);
    this.filteredHomework = this.homework.filter(hw => {
      const dueDate = new Date(hw.dueDate);
      return dueDate >= start && dueDate <= end;
    });
  }
}
};
</script>

<style scoped>
.homework-container {
display: flex;
flex-wrap: wrap;
gap: 0px;
}

.homework-card {
margin-left: 40px;
flex: 0 0 80%;
}

.card {
background-color: #fff;
border: 2px solid #ccc;
border-radius: 5px;
}

.card-body {
padding: 10px;
}

.card-title {
font-size: 1.2rem;
font-weight: bold;
margin-right: 8px;
margin-bottom: 10px;
}

.teacher-name {
font-weight: bold;
font-style: italic;
margin-bottom: 1px;
margin-top: 3px;
margin-left: 15px;
font-size: 1rem;
color: #323232;
}

.description {
margin-left: 15px;
margin-bottom: 5px;
}

.due-date {
float: right;
margin-right: 15px;
font-style: italic;
color: #050000;
}

.filter {
margin-bottom: 20px;
display: flex;
align-items: center;
}

.filter label {
margin-right: 10px;
}

.date-input {
border-radius: 5px;
padding: 5px;
border: 1px solid #ccc;
}

</style>
