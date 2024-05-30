<template>
    <div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
    <div class="col-sm-6">
    <h1 class="m-0">Parents</h1>
    <br>
    <button @click="addUser" type="button" class="mb-2 btn btn-primary">
        <i class="fa fa-plus-circle mr-1"></i>
            Add New Parent
    </button>
    </div>
    
    <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="/home">Home</a></li>
    <li class="breadcrumb-item active">Parents</li>
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
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Registration Date</th>
                                <th>Students</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(user, index) in users" :key="user.id">
                                <td>{{ index + 1 }}</td>
                                <td>{{ user.first_name + " " + user.last_name }}</td>
                                <td>{{ user.email }}</td>
                                <td>{{ formatDate(user.created_at) }}</td>
                                <td>
                                    <ul>
                                        <li v-if="user.students.length === 0" class="no-students">No students assigned</li>
                                        <li v-for="student in user.students" :key="student.id">{{ student.first_name + " " + student.last_name }}</li>
                                    </ul>
                                </td>
                                <td>
                                    <button @click.prevent="assignStudentsToParent(user.id)" type="button" class="btn btn-sm btn-secondary mr-2" data-dismiss="modal">Assign students</button>
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



    <div class="modal fade" id="userFormModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">
                    <span v-if="editing">Edit Parent</span>
                    <span v-else>Add new Parent</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <Form ref="form" @submit="handleSubmit" :validationSchema="editing ? editUserSchema : createUserSchema" v-slot="{ errors }" :initial-values="formValues">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <Field name="first_name" class="form-control" :class="{ 'is-invalid': errors.first_name }" id="first_name" placeholder="Enter first name"/>
                                <span class="invalid-feedback">{{ errors.first_name }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <Field name="last_name" class="form-control" :class="{ 'is-invalid': errors.last_name }" id="last_name" placeholder="Enter last name"/>
                                <span class="invalid-feedback">{{ errors.last_name }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <Field name="email" type="email" class="form-control" :class="{ 'is-invalid': errors.email }" id="email" placeholder="Enter email"/>
                                <span class="invalid-feedback">{{ errors.email }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Password</label>
                                <Field name="password" type="password" class="form-control" :class="{ 'is-invalid': errors.password }" id="password" placeholder="Enter password"/>
                                <span class="invalid-feedback">{{ errors.password }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address">Address</label>
                                <Field name="address" class="form-control" :class="{ 'is-invalid': errors.address }" id="address" placeholder="Enter address"/>
                                <span class="invalid-feedback">{{ errors.address }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_of_birth">Date of Birth</label>
                                <Field name="date_of_birth" type="date" class="form-control" :class="{ 'is-invalid': errors.date_of_birth }" id="date_of_birth"/>
                                <span class="invalid-feedback">{{ errors.date_of_birth }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gender">Gender</label>
                                <select name="gender" class="form-control" :class="{ 'is-invalid': errors.gender }" id="gender">
                                    <option value="">Select gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                                <span class="invalid-feedback">{{ errors.gender }}</span>
                            </div>
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



    <div class="modal fade" id="parentStudentModal" tabindex="-1" role="dialog" aria-labelledby="assignStudentsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignStudentsModalLabel">Assign Students</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="submitStudentAssignments">
                        <div class="form-group">
                            <label for="students">Students</label>
                            <div v-for="student in allStudents" :key="student.id" class="formCheck">
                                <input 
                                    type="checkbox" 
                                    class="formCheckInput" 
                                    :id="'student-' + student.id" 
                                    :value="student.id"
                                    v-model="selectedStudents">
                                <label class="formCheckLabel" :for="'student-' + student.id">
                                    {{ student.first_name + ' ' + student.last_name }}
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="deleteUserModal" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        <span>Delete Parent</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Are you sure you want to delete this parent?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button @click.prevent="deleteUser" type="button" class="btn btn-primary">Delete user</button>
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
const users = ref([]);
const editing = ref(false);
const formValues = ref({
    id: null,
    first_name: '',
    last_name: '',
    email: '',
    address: '',
    date_of_birth: '',
    gender: '',
});
const form = ref(null);
const userIdBeingDeleted = ref(null);


const getUsers = () => {
    axios.get('http://127.0.0.1:8000/api/parents')
    .then((response) => {
        users.value = response.data;
    })
}


const createUserSchema = yup.object({
    first_name: yup.string().label('First name').required(),
    last_name: yup.string().label('Last name').required(),
    email: yup.string().email().required(),
    password: yup.string().required().min(8),
});


const editUserSchema = yup.object({
    first_name: yup.string().required(),
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

    axios.post('http://127.0.0.1:8000/api/parents', values)
    .then((response) => {
        users.value.unshift(response.data);
        $('#userFormModal').modal('hide');
        resetForm();
        toastr.success('Parent created successfully.')
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
        first_name: user.first_name,
        last_name: user.last_name,
        email: user.email,
        address: user.address,
        date_of_birth: user.date_of_birth,
        gender: user.gender,
    };
    form.value.setValues(formValues.value);
};


const updateUser = (values, {setErrors}) => {
    axios.put('http://127.0.0.1:8000/api/parents/' + formValues.value.id, values)
        .then((response) => {
            const index = users.value.findIndex(user => user.id === response.data.id);
            users.value[index] = response.data;
            $('#userFormModal').modal('hide');
            toastr.success('Parent updated successfully.');
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
    axios.delete(`http://127.0.0.1:8000/api/parents/${userIdBeingDeleted.value}`)
    .then(() => {
        $('#deleteUserModal').modal('hide');
        users.value = users.value.filter(user => user.id !== userIdBeingDeleted.value);
        toastr.success('Parent deleted successfully.');
    });
};

onMounted(() => {
    getUsers();
});
</script>


<script>
import axios from 'axios';
import { useToastr } from '../../../toastr.js';

const toastr = useToastr();
export default {
    data() {
        return {
            allStudents: [],
            selectedStudents: [],
            selectedParentId: null,
        };
    },
    methods: {
        assignStudentsToParent(parentId) {
            this.selectedParentId = parentId;
            axios.get('/api/students')
                .then(response => {
                    this.allStudents = response.data;
                })
                .catch(error => {
                    console.error(error);
                });
            $('#parentStudentModal').modal('show');
        },

        submitStudentAssignments() {
            const selectedStudentsArray = Array.from(this.selectedStudents);
            axios.post(`/api/parents/assignStudents/${this.selectedParentId}`, {
                students: selectedStudentsArray
            })
            .then(response => {
                $('#parentStudentModal').modal('hide');
                toastr.success('Successfully assigned.');
            })
            .catch(error => {
                console.error(error);
            });
        }
    },

};
</script>

<style>
    .no-students {
        list-style-type: none;
    }
</style>