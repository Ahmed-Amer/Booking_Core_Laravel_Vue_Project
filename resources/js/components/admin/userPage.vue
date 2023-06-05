<!--  datatable with ajax with adding action btns-->
<template>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Users</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <router-link class="btn btn-md btn-outline-secondary" :to="{ name: 'adduser' }">Add User</router-link>

            </div>
        </div>
    </div>


    <div class="table-responsive">
        <table class="table table-striped table-sm" id="myTable">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <!-- <th scope="col">Password</th> -->
                    <th scope="col">Created_at</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in  users" :key="user.id">
                    <td>{{ user.id }}</td>
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <!-- <td>{{ user.password }}</td> -->
                    <td>{{ user.created_at.slice(0, 10) }}</td>
                    <td>
                        <router-link :to="{ name: 'showbookable', params: { id: user.id } }" class="btn btn-sm btn-primary">Edit</router-link>
                        <button @click="deleteUser(user.id)" class="btn btn-sm btn-danger">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
import DataTable from 'datatables.net-vue3';
import DataTablesCore from 'datatables.net';
import jszip from 'jszip';
import pdfmake from 'pdfmake';
import 'datatables.net-buttons';
import 'datatables.net-buttons/js/buttons.colVis.mjs';
import 'datatables.net-buttons/js/buttons.html5.mjs';
import 'datatables.net-buttons/js/buttons.print.mjs';


DataTable.use(DataTablesCore);
DataTablesCore.Buttons.jszip(jszip);
DataTablesCore.Buttons.pdfMake(pdfmake);



export default {
    data() {
        return {
            users: [],
            table: null,
        }
    },
    methods: {
        loadDataTable() {
            this.$nextTick(() => {
                this.table = new DataTablesCore('#myTable', {
                    dom: 'Bftip',
                    "bDestroy": true,
                    buttons: [
                        {
                            extend: 'csvHtml5',
                            className: 'btn btn-sm btn-outline-secondary',
                            exportOptions: {
                                columns: ':not(:last-child)',
                            }
                        }, 
                        {
                            extend: 'excelHtml5',
                            className: 'btn btn-sm btn-outline-secondary',
                            exportOptions: {
                                columns: ':not(:last-child)',
                            }
                        }
                        , 
                        {
                            extend: 'pdf',
                            className: 'btn btn-sm btn-outline-secondary',
                            exportOptions: {
                                columns: ':not(:last-child)',
                            }
                        }, 
                        
                        {
                            extend: 'print',
                            className: 'btn btn-sm btn-outline-secondary',
                        }
                        ],
                });
            })
        },
        deleteUser(id){
            if(confirm("Confirm delete this users")){

                axios.delete("/api/users/" + id).then(response => {
                this.users = response.data["data"];
                this.table.fnDestroy();
                this.loadDataTable();
                // this.$router.push
    
            });
            }
        }
    },
    created() {
        axios.get("/api/users-for-datatables").then(response => {
            this.users = response.data["data"];
            this.loadDataTable();

        });
    }
}
</script>
<style scoped>
/* dashboard buttons */
.btn-sm {
    max-width: 70px !important;
    font-size: 12px;
    margin: 2px ;
}

td {
    padding: 15px !important;
}

#myTable_paginate, #myTable_info,#myTable_filter ,.dt-buttons{
    display: inline-block !important;
    width: 50% !important;
}
</style>
