<script setup>
import axios from 'axios';
import { ref, onMounted, reactive } from 'vue';
import { Form, Field, useResetForm } from 'vee-validate';
import * as yup from 'yup';

const users = ref([]);
const editing = ref(false);
const formValues = ref();
const form = ref(null);
const userIdBeingDeleted = ref(null);


const getUsers = () => {
    axios.get('http://127.0.0.1:8000/api/students')
    .then((response) => {
        users.value = response.data;
    })
}


const createUserSchema = yup.object({
    name: yup.string().required(),
    email: yup.string().email().required(),
    password: yup.string().required().min(8),
});


const editUserSchema = yup.object({
    name: yup.string().required(),
    email: yup.string().email().required(),
    password: yup.string().notRequired().test('password', 'Passwords must be be minimum of 8 characters', function(value) {
                if (!!value) {
                const schema = yup.string().min(8);
                return schema.isValidSync(value);
                }
                return true;
            }),
});



const createUser = (values, { resetForm, setErrors }) => {
    const emailExists = users.value.some(user => user.email === values.email);
    if (emailExists) {
        setErrors({ email: ['This email is already taken.'] });
        return;
    }

    axios.post('http://127.0.0.1:8000/api/students', values)
    .then((response) => {
        users.value.unshift(response.data);
        $('#userFormModal').modal('hide');
        resetForm();
    })
    .catch((error) => {
        setErrors(error.response.data.errors);
    });
};



const addUser = () => {
    editing.value = false;
    form.value.resetForm();
    $('#userFormModal').modal('show');
};


const editUser = (user) => {
    editing.value = true;
    form.value.resetForm();
    $('#userFormModal').modal('show');
    formValues.value = {
        id: user.id,
        name: user.name,
        email: user.email,
    };
    form.value.setValues(formValues.value);
};


const updateUser = (values, {setErrors}) => {
    axios.put('http://127.0.0.1:8000/api/students/' + formValues.value.id, values)
        .then((response) => {
            const index = users.value.findIndex(user => user.id === response.data.id);
            users.value[index] = response.data;
            $('#userFormModal').modal('hide');
        }).catch((error) => {
            setErrors(error.response.data.errors);
            console.log(error);
        });
}


const handleSubmit = (values, actions) => {
    if (editing.value) {
        updateUser(values, actions);
    } else {
        createUser(values, actions);
    }
};


const confirmUserDeletion = (user) => {
    userIdBeingDeleted.value = user.id;
    $('#deleteUserModal').modal('show')
};


const deleteUser = () => {
    axios.delete(`http://127.0.0.1:8000/api/students/${userIdBeingDeleted.value}`)
    .then(() => {
        $('#deleteUserModal').modal('hide');
        users.value = users.value.filter(user => user.id !== userIdBeingDeleted.value);
    });
};



onMounted(() => {
    getUsers();
});
</script>

<template>
    <div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
    <div class="col-sm-6">
    <h1 class="m-0">Students</h1>
    <br>
    <button @click="addUser" type="button" class="mb-2 btn btn-primary">
        <i class="fa fa-plus-circle mr-1"></i>
            Add New Student
    </button>
    </div>
    
    <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active">Students</li>
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
                                <th>Email</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(user, index) in users" :key="user.id">
                                <td>{{ index + 1 }}</td>
                                <td>{{ user.name }}</td>
                                <td>{{ user.email }}</td>
                                <td>
                                    <a href="#" @click.prevent="editUser(user)"><i class="fa fa-edit"></i></a>
                                    <a href="#" @click.prevent="confirmUserDeletion(user)"><i class="fa fa-trash text-danger ml-2"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                    </div>
                </div>
    </div>



    <div class="modal fade" id="userFormModal" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        <span v-if="editing">Edit Student</span>
                        <span v-else>Add new Student</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <Form ref="form" @submit="handleSubmit" :validationSchema="editing ? editUserSchema : createUserSchema" v-slot="{ errors }" :initial-values="formValues">
                <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <Field name="name" class="form-control" :class="{ 'is-invalid': errors.name }" id="name"
                                aria-describedby="nameHelp" placeholder="Enter full name"/>
                                <span class="invalid-feedback">{{ errors.name }}</span>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <Field name="email" type="email" class="form-control" :class="{ 'is-invalid': errors.email }" id="email"
                                aria-describedby="nameHelp" placeholder="Enter email"/>
                                <span class="invalid-feedback">{{ errors.email }}</span>
                        </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <Field name="password" type="password" class="form-control" :class="{ 'is-invalid': errors.password }" id="password"
                            aria-describedby="nameHelp" placeholder="Enter password"/>
                            <span class="invalid-feedback">{{ errors.password }}</span>
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


    <div class="modal fade" id="deleteUserModal" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        <span>Delete Student</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Are you sure you want to delete this student?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button @click.prevent="deleteUser" type="button" class="btn btn-primary">Delete user</button>
                </div>
            </div>
        </div>
    </div>
</template>