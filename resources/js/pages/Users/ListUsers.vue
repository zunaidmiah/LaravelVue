<script setup>
import { ref, onMounted, reactive } from "vue";
import {Form, Field} from 'vee-validate';
import * as yup from 'yup';
import {useToastr} from '../../toastr.js';


const users = ref([]);
const editing = ref(false);
const formValues = ref();
const form = ref(null);
const toastr = useToastr();

const getUsers = () => {
    axios.get("/api/users").then((response) => {
        users.value = response.data;
    });
};

const addUserSchema = yup.object({
    name: yup.string().required(),
    email: yup.string().email().required(),
    password: yup.string().required().min(8),
});

const editUserSchema = yup.object({
    name: yup.string().required(),
    email: yup.string().email().required(),
    password: yup.string().when((password, schema) => {
        return password ? schema.required().min(10) : schema;
    }),
});

const createUser = (values, {resetForm, setErrors}) => {
    axios.post("/api/users", values)
    .then((response) => {
        users.value.unshift(response.data);
        $("#createUser").modal('hide');
        resetForm();
        toastr.success("User added successfully!");
    }).catch((error) => {
        if(error.response.data.errors){
            setErrors(error.response.data.errors);
        }
        toastr.error("Something went wrong!");
    });
}


const updateUser = (values, {setErrors}) => {
    axios.put('/api/users/'+formValues.value.id, values)
        .then((response) => {
            const index = users.value.findIndex(user => user.id === response.data.id);
            users.value[index] = response.data;
            $("#createUser").modal('hide');
            toastr.success("User updated successfully!");
        }).catch((error) => {
            if(error.response.data.errors){
                setErrors(error.response.data.errors);
            }
            toastr.error("Something went wrong!");
            console.log(error);
        });
}

const handleSubmit = (values, actions) => {
    if(editing.value){
        updateUser(values, actions);
    }else{
        createUser(values, actions);
    }

}

const addUser = () => {
    editing.value = false;
    $("#createUser").modal('show');
}

const editUser = (user) => {
    form.value.resetForm();
    editing.value = true;
    $("#createUser").modal('show');
    console.log(user.name);
    formValues.value = {
        id: user.id,
        name: user.name,
        email: user.email,
    };
}

onMounted(() => {
    getUsers();
});
</script>
<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Users List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Users List</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <!-- Button trigger modal -->
            <button
                type="button"
                class="btn btn-primary"
                @click="addUser"
            >
                Add User
            </button>
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Role</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(user, index) in users" :key="user.id">
                                <th scope="row">{{ index + 1 }}</th>
                                <td>{{ user.name }}</td>
                                <td>{{ user.email }}</td>
                                <td>User</td>
                                <td>
                                    <a href="#" @click.prevent="editUser(user)"> <i class="fa fa-edit"/></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div
        class="modal fade"
        id="createUser"
        tabindex="-1"
        role="dialog"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        <span v-if="editing">Edit User</span>
                        <span v-else>Add New User</span>
                    </h5>
                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <Form ref="form" @submit="handleSubmit" :validation-schema="editing ? editUserSchema : addUserSchema" v-slot="{ errors }" :initial-values="formValues">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <Field type="text" class="form-control" :class="{'is-invalid': errors.name}" id="name" aria-describedby="emailHelp" placeholder="Enter name" name="name" />
                            <span class="invalid-feedback">{{ errors.name }}</span>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Email address</label>
                            <Field type="email" class="form-control" :class="{'is-invalid': errors.email}" id="email" aria-describedby="emailHelp" placeholder="Enter email" name="email" />
                            <span class="invalid-feedback">{{ errors.email }}</span>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Password</label>
                            <Field type="password" class="form-control" :class="{'is-invalid': errors.password}" id="password" placeholder="Password" name="password" />
                            <span class="invalid-feedback">{{ errors.password }}</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal"
                        >
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Save
                        </button>
                    </div>
                </Form>
            </div>
        </div>
    </div>
</template>
