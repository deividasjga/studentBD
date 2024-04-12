<script setup>
import axios from 'axios';
import { ref, onMounted, reactive } from 'vue';
import { Form, Field, useResetForm } from 'vee-validate';
import * as yup from 'yup';
import { useToastr } from '../../../toastr.js';
import { formatDate } from '../../../helper.js';

const toastr = useToastr();
const classes = ref([]);
const editing = ref(false);
const formValues = ref();
const form = ref(null);
const classIdBeingDeleted = ref(null);


const getClasses = () => {
    axios.get('http://127.0.0.1:8000/api/classes')
    .then((response) => {
        classes.value = response.data;
    })
}

const subjects = ref([]);
const selectedSubjects = ref([]);

const getSubjects = () => {
    axios.get('http://127.0.0.1:8000/api/subjects')
    .then((response) => {
        subjects.value = response.data;
    })
    .catch((error) => {
        console.error('Error fetching subjects:', error);
    });
};


const createClassSchema = yup.object({
    name: yup.string().required(),
    subjects: yup.array().min(1, 'At least one subject is required').nullable(),
});


const editClassSchema = yup.object({
    name: yup.string().required(),
    subjects: yup.array().min(1, 'At least one subject is required').nullable(),
});



const createClass = (values, { resetForm, setErrors }) => {
    const classNameExists = classes.value.some(classOne => classOne.name === values.name);
    if (classNameExists) {
        setErrors({ name: ['This name is already taken.'] });
        return;
    }

    axios.post('http://127.0.0.1:8000/api/classes', values)
    .then((response) => {
        classes.value.unshift(response.data);
        $('#classFormModal').modal('hide');
        resetForm();
        toastr.success('Class created successfully.')
    })
    .catch((error) => {
        setErrors(error.response.data.errors);
    });
};



const addClass = () => {
    editing.value = false;
    form.value.resetForm();
    $('#classFormModal').modal('show');
};


const editClass = (classOne) => {
    editing.value = true;
    form.value.resetForm();
    $('#classFormModal').modal('show');
    formValues.value = {
        id: classOne.id,
        name: classOne.name,
    };
    form.value.setValues(formValues.value);
};


const updateClass = (values, {setErrors}) => {
    axios.put('http://127.0.0.1:8000/api/classes/' + formValues.value.id, values)
        .then((response) => {
            const index = classes.value.findIndex(classOne => classOne.id === response.data.id);
            classes.value[index] = response.data;
            $('#classFormModal').modal('hide');
            toastr.success('Class updated successfully.');
        }).catch((error) => {
            setErrors(error.response.data.errors);
            console.log(error);
        });
}


const handleSubmit = (values, actions) => {
    if (editing.value) {
        updateClass(values, actions);
    } else {
        createClass(values, actions);
    }
};


const confirmClassDeletion = (classOne) => {
    classIdBeingDeleted.value = classOne.id;
    $('#deleteClassModal').modal('show')
};


const deleteClass = () => {
    axios.delete(`http://127.0.0.1:8000/api/classes/${classIdBeingDeleted.value}`)
    .then(() => {
        $('#deleteClassModal').modal('hide');
        classes.value = classes.value.filter(classOne => classOne.id !== classIdBeingDeleted.value);
        toastr.success('Class deleted successfully.');
    });
};


onMounted(() => {
    getClasses();
    getSubjects();
});
</script>

<template>
    <div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
    <div class="col-sm-6">
    <h1 class="m-0">Classes</h1>
    <br>
    <button @click="addClass" type="button" class="mb-2 btn btn-primary">
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
                                <th style="width: 35px">#</th>
                                <th>Name</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(classOne, index) in classes" :key="classOne.id">
                                <td>{{ index + 1 }}</td>
                                <td>{{ classOne.name }}</td>
                                <td>
                                    <a href="#" @click.prevent="editClass(classOne)"><i class="fa fa-edit"></i></a>
                                    <a href="#" @click.prevent="confirmClassDeletion(classOne)"><i class="fa fa-trash text-danger ml-2"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                    </div>
                </div>
    </div>


    <div class="modal fade" id="classFormModal" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        <span v-if="editing">Edit Class</span>
                        <span v-else>Add new Class</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <Form ref="form" @submit="handleSubmit" :validationSchema="editing ? editClassSchema : createClassSchema" v-slot="{ errors }" :initial-values="formValues">
                <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <Field name="name" class="form-control" :class="{ 'is-invalid': errors.name }" id="name"
                                aria-describedby="nameHelp" placeholder="Enter name"/>
                                <span class="invalid-feedback">{{ errors.name }}</span>
                        </div>


                        <div class="form-group">
                        <label for="subjects">Subjects</label>
                        <div v-for="subject in subjects" :key="subject.id">
                            <input type="checkbox" :id="'subject_' + subject.id" :value="subject.id">
                            <label :for="'subject_' + subject.id">{{ subject.name }}</label>
                        </div>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
                </Form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="deleteClassModal" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        <span>Delete Class</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Are you sure you want to delete this class?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button @click.prevent="deleteClass" type="button" class="btn btn-primary">Delete Class</button>
                </div>
            </div>
        </div>
    </div>



</template>
