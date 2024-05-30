<template>
    <div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
    <div class="col-sm-6">
        <h2 style="display: inline-block;">Homework</h2>
        <button @click="isNewReward = true; openHomeworkFormModal(null)" type="button" class="mb-2 btn btn-primary" style="margin-left: 25px;">
            <i class="fa fa-plus-circle mr-1"></i>
                Add New Homework
        </button>
        <div class="dateClassFilters">
            <label for="startDate" style="margin-left: 30px;">Start Date:</label>
            <input type="date" id="startDate" v-model="startDate" class="dateFilter">
            <label for="endDate" style="margin-left: 20px;">End Date:</label>
            <input type="date" id="endDate" v-model="endDate" class="dateFilter">
            <label for="className" style="margin-left: 20px;">Class:</label>
            <select id="className" v-model="className" class="classFilter">
              <option value="">All Classes</option>
              <option v-for="teacherClass in teacherClasses" :key="teacherClass.id" :value="teacherClass.name">{{ teacherClass.name }}</option>
            </select>
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
                <h5 class="teacher-name">(Class: {{ hw.class.name }})</h5>
          <p class="card-text">
            <span class="description">{{ hw.description }}</span>
          </p>
          <div class="action-buttons">
              <a href="#" @click.prevent="openHomeworkFormModal(hw)"><i class="fa fa-edit"></i></a>
              <a href="#" @click.prevent="deleteHomework(hw.id)" class="ml-2"><i class="fa fa-trash text-danger"></i></a>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="editModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">{{ isNewHomework ? 'Create Homework' : 'Edit Homework' }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="homeworkClass" class="form-label">Select Class</label>
                <select v-model="editHomework.class_id" @change="fetchSubjectsFromSelect(editHomework.class_id)" class="form-control" id="homeworkClass">
                  <option v-for="teacherClass in teacherClasses" :key="teacherClass.id" :value="teacherClass.id">{{ teacherClass.name }}</option>
                </select>
              </div>
            </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="homeworkSubject" class="form-label">Select Subject</label>
                <select v-model="editHomework.subject_id" class="form-control" id="homeworkSubject">
                  <option v-for="subjectItem in subjects" :key="subjectItem.id" :value="subjectItem.id">{{ subjectItem.name }}</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="homeworkDescription" class="form-label">Description</label>
                <input type="text" v-model="editHomework.description" class="form-control" id="homeworkDescription">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="homeworkEndDate" class="form-label">End date</label>
                <input type="date" v-model="editHomework.end_date" class="form-control" id="homeworkEndDate">
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button @click="saveChanges" class="btn btn-primary">{{ isNewHomework ? 'Create' : 'Save Changes' }}</button>
</div>
</div>
</div>
</div>
</template>

<script>
import { useToastr } from '../../../toastr.js';
const toastr = useToastr();
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
    endDate: '',
    className: '',
    teacherClasses: [],
    subjects: [],
    homeworks: { 
        description: '',
        end_date: '',
        teacher_id: '',
        class_id: '',
        subject_id: '',
    },
    editHomework: { description: '',
    end_date: '',
        teacher_id: '',
        class_id: '',
        subject_id: '',
     },
    isNewHomework: false
  };
},
mounted() {
    this.fetchHomework();
    this.fetchClasses();
},
computed: {
filteredHomework() {
      const start = this.startDate ? new Date(this.startDate) : null;
      const end = this.endDate ? new Date(this.endDate) : null;
      return this.homework.filter(hw => {
        const dueDate = new Date(hw.end_date);
        const dateInRange = (!start || dueDate >= start) && (!end || dueDate <= end);
        const classMatches = !this.className || hw.class.name === this.className;
        return dateInRange && classMatches;
      });
    }
  },
methods: {
    async fetchHomework() {
    try {
        const response = await axios.get(`/api/teacher/homework/${this.userId}`);
        this.homework = response.data;
    } catch (error) {
        console.error("Error fetching homework:", error);
    }
    },
    async fetchClasses() {
        try {
        const response = await axios.get(`/api/teacher/${this.userId}/classList`);
        this.teacherClasses = response.data;
        } catch (error) {
        console.error('Error fetching teacher classes:', error);
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
    },
    fetchSubjects() {
        if (this.editHomework.class_id) {
            axios.get(`/api/teacher/${this.userId}/${this.editHomework.class_id}/classSubjects`)
                .then(response => {
                    this.subjects = response.data;
                })
                .catch(error => {
                    console.error(error);
                });
        } else {
            this.subjects = [];
        }
    },
    fetchSubjectsFromSelect(selectedClass) {
        console.log(selectedClass);
        axios.get(`/api/teacher/${this.userId}/${selectedClass}/classSubjects`)
                .then(response => {
                    this.subjects = response.data;
                })
                .catch(error => {
                    console.error(error);
                });
    },
    openHomeworkFormModal(homeworkItem) {
            if (homeworkItem) {
            this.isNewHomework = false;
            this.editHomework = { ...homeworkItem };
            this.fetchSubjects();
            } else {
            this.isNewHomework = true;
            this.editHomework = {
                description: '',
                end_date: '',
                teacher_id: this.userId,
                class_id: '',
                subject_id: '',
                };
            }
            $('#editModal').modal('show');
        },
        async saveChanges() {
            try {
                if (this.isNewHomework) {
                    const response = await axios.post('/api/teacher/createHomework', this.editHomework);
                    console.log(this.editHomework);
                    toastr.success('Homework created successfully.');
                } else {
                    await axios.put(`/api/teacher/editHomework/${this.editHomework.id}`, this.editHomework);
                    toastr.success('Homework updated successfully.');
                }
                this.fetchHomework();
                $('#editModal').modal('hide');
            } catch (error) {
                console.error('Error saving changes:', error);
            }
            },
            deleteHomework(id) {
                if (confirm('Are you sure you want to delete this homework?')) {
                axios.delete(`/api/teacher/homework/${id}`)
                .then(() => {
                    this.homework = this.homework.filter(homeworkItem => homeworkItem.id !== id);
                    toastr.success('Homework deleted successfully.');
                })
                .catch(error => {
                    console.error('Error deleting homework:', error);
                });
                }
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

.dateClassFilters {
margin-bottom: 20px;
display: flex;
align-items: center;
}

.dateClassFilters label {
margin-right: 10px;
}

.dateFilter {
border-radius: 5px;
padding: 5px;
border: 1px solid #cccccc;
}

.classFilter {
  border-radius: 5px;
  padding: 5px;
  border: 1px solid #cccccc;
}

</style>
