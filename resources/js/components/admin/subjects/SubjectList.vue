<template>
    <div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
    <div class="col-sm-6">
    <h1 class="m-0">Subjects</h1>
    <br>
    <button @click="addSubject" type="button" class="mb-2 btn btn-primary">
        <i class="fa fa-plus-circle mr-1"></i>
            Add New Subject
    </button>
    </div>
    
    <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="/home">Home</a></li>
    <li class="breadcrumb-item active">Subjects</li>
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
                            <tr v-for="(subject, index) in subjects" :key="subject.id">
                                <td>{{ index + 1 }}</td>
                                <td>{{ subject.name }}</td>
                                <td>
                                    <a href="#" @click.prevent="editSubject(subject)"><i class="fa fa-edit"></i></a>
                                    <a href="#" @click.prevent="confirmSubjectDeletion(subject)"><i class="fa fa-trash text-danger ml-2"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                    </div>
                </div>
    </div>


    <div class="modal fade" id="subjectFormModal" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        <span v-if="editing">Edit Subject</span>
                        <span v-else>Add new Subject</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <Form ref="form" @submit="handleSubmit" :validationSchema="editing ? editSubjectSchema : createSubjectSchema" v-slot="{ errors }" :initial-values="formValues">
                <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <Field name="name" class="form-control" :class="{ 'is-invalid': errors.name }" id="name"
                                aria-describedby="nameHelp" placeholder="Enter name"/>
                                <span class="invalid-feedback">{{ errors.name }}</span>
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


    <div class="modal fade" id="deleteSubjectModal" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        <span>Delete Subject</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Are you sure you want to delete this subject?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button @click.prevent="deleteSubject" type="button" class="btn btn-primary">Delete subject</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import axios from 'axios';
import { ref, onMounted, reactive } from 'vue';
import { Form, Field, useResetForm } from 'vee-validate';
import * as yup from 'yup';
import { useToastr } from '../../../toastr.js';
import { formatDate } from '../../../helper.js';

const toastr = useToastr();
const subjects = ref([]);
const editing = ref(false);
const formValues = ref();
const form = ref(null);
const subjectIdBeingDeleted = ref(null);


const getSubjects = () => {
    axios.get('http://127.0.0.1:8000/api/subjects')
    .then((response) => {
        subjects.value = response.data;
    })
}

const createSubjectSchema = yup.object({
    name: yup.string().required(),
});

const editSubjectSchema = yup.object({
    name: yup.string().required(),
});


const createSubject = (values, { resetForm, setErrors }) => {
    const subjectNameExists = subjects.value.some(subject => subject.name === values.name);
    if (subjectNameExists) {
        setErrors({ name: ['This name is already taken.'] });
        return;
    }

    axios.post('http://127.0.0.1:8000/api/subjects', values)
    .then((response) => {
        subjects.value.unshift(response.data);
        $('#subjectFormModal').modal('hide');
        resetForm();
        toastr.success('Subject created successfully.')
    })
    .catch((error) => {
        setErrors(error.response.data.errors);
    });
};

const addSubject = () => {
    editing.value = false;
    form.value.resetForm();
    $('#subjectFormModal').modal('show');
};

const editSubject = (subject) => {
    editing.value = true;
    form.value.resetForm();
    $('#subjectFormModal').modal('show');
    formValues.value = {
        id: subject.id,
        name: subject.name,
    };
    form.value.setValues(formValues.value);
};

const updateSubject = (values, {setErrors}) => {
    axios.put('http://127.0.0.1:8000/api/subjects/' + formValues.value.id, values)
        .then((response) => {
            const index = subjects.value.findIndex(subject => subject.id === response.data.id);
            subjects.value[index] = response.data;
            $('#subjectFormModal').modal('hide');
            toastr.success('Subject updated successfully.');
        }).catch((error) => {
            setErrors(error.response.data.errors);
            console.log(error);
        });
}

const handleSubmit = (values, actions) => {
    if (editing.value) {
        updateSubject(values, actions);
    } else {
        createSubject(values, actions);
    }
};

const confirmSubjectDeletion = (subject) => {
    subjectIdBeingDeleted.value = subject.id;
    $('#deleteSubjectModal').modal('show')
};

const deleteSubject = () => {
    axios.delete(`http://127.0.0.1:8000/api/subjects/${subjectIdBeingDeleted.value}`)
    .then(() => {
        $('#deleteSubjectModal').modal('hide');
        subjects.value = subjects.value.filter(subject => subject.id !== subjectIdBeingDeleted.value);
        toastr.success('Subject deleted successfully.');
    });
};

onMounted(() => {
    getSubjects();
});
</script>
