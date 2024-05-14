<template>
  <div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
    <div class="col-sm-6">
    <h1 class="m-0">Classes</h1>
    <br>
    <button @click="isNewClass = true; openClassFormModal(null)" type="button" class="mb-2 btn btn-primary">
        <i class="fa fa-plus-circle mr-1"></i>
            Add New Class
    </button>
    </div>
    
    <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active">Classes</li>
    </ol>
    </div>
    </div>
    </div>
    </div>
    
    
    <div class="content">
    <div class="container-fluid">
        <div class="card">
        <div class="card-body">
                    <table class="table table-bordered">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Subjects</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="classItem in classes" :key="classItem.id">
            <td>{{ classItem.id }}</td>
            <td>{{ classItem.name }}</td>
            <td>
            <ul>
              <li v-for="subject in classItem.subjects" :key="subject.id">{{ subject.name }}</li>
            </ul>
          </td>
            <td>
              <a href="#" @click.prevent="openClassFormModal(classItem)"><i class="fa fa-edit"></i></a>
              <a href="#" @click.prevent="deleteClass(classItem.id)"><i class="fa fa-trash text-danger ml-2"></i></a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    </div>
    </div>
    </div>


      <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="editModalLabel">{{ isNewClass ? 'Create Class' : 'Edit Class' }}</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="className" class="form-label">Class Name</label>
                <input type="text" v-model="editedClass.name" class="form-control" id="className">
              </div>

              <div class="mb-3">
                <label class="form-label">Subjects</label><br>
                <div v-for="subject in subjects" :key="subject.id" class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" :id="'subject_' + subject.id" :value="subject.id" v-model="editedClass.subjectIds">
                    <label class="form-check-label" :for="'subject_' + subject.id">{{ subject.name }}</label>
                </div>
            </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button @click="saveChanges" class="btn btn-primary">{{ isNewClass ? 'Create' : 'Save Changes' }}</button>
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
      classes: [],
      subjects: [],
      editedClass: { name: '', subjectIds: [] },
      isNewClass: false
    };
  },
  created() {
    this.fetchClasses();
    this.fetchSubjects();
  },

  methods: {
    fetchClasses() {
        fetch('/api/classes')
          .then(response => response.json())
          .then(data => {
            this.classes = data;
          })
          .catch(error => {
            console.error('Error fetching classes:', error);
          });
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
    openClassFormModal(classItem) {
      if (classItem) {
        this.isNewClass = false;
        this.editedClass = { ...classItem, subjectIds: [] };
      } else {
        this.isNewClass = true;
        this.editedClass = { name: '', subjectIds: [] };
      }
      $('#editModal').modal('show');
    },
    async saveChanges() {
    try {
        if (this.isNewClass) {
            const response = await axios.post('/api/classes', {
                name: this.editedClass.name,
                subjects: this.editedClass.subjectIds
            });
            toastr.success('Class created successfully.');
        } else {
            await axios.put(`/api/classes/${this.editedClass.id}`, {
                name: this.editedClass.name,
                subjects: this.editedClass.subjectIds
            });
            toastr.success('Class updated successfully.');
        }
        this.fetchClasses();
        $('#editModal').modal('hide');
    } catch (error) {
        // console.error('Error saving changes:', error);
        toastr.error('Class name cannot be empty.');
    }
    },
    deleteClass(id) {
        if (confirm('Are you sure you want to delete this class?')) {
        axios.delete(`/api/classes/${id}`)
        .then(() => {
            this.classes = this.classes.filter(classItem => classItem.id !== id);
            toastr.success('Class deleted successfully.');
        })
        .catch(error => {
            console.error('Error deleting class:', error);
        });
        }
    }
  }
};
</script>
  