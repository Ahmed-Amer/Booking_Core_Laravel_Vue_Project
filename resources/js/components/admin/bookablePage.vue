<!--  datatable with ajax with adding action btns-->
<template>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Bookables</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <router-link class="btn btn-md btn-outline-secondary" :to="{ name: 'addbookable' }">Add
                    Bookable</router-link>

            </div>
        </div>
    </div>


    <div class="table-responsive">
        <table class="table table-striped table-sm" id="myTable">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Price</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="bookable in  bookables" :key="bookable.id">
                    <td>{{ bookable.id }}</td>
                    <td>{{ bookable.title }}</td>
                    <td style="text-align: justify !important;">{{ bookable.description }}</td>
                    <td>{{ bookable.price + '$' }}</td>
                    <td>
                        <router-link :to="{ name: 'showbookable', params: { id: bookable.id } }"
                            class="btn btn-sm btn-primary">Preview</router-link>
                        <router-link :to="{ name: 'showbookable', params: { id: bookable.id } }"
                            class="btn btn-sm btn-warning">Edit</router-link>
                        <button @click="deleteBookable(bookable.id)" class="btn btn-sm btn-danger">Delete</button>

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
            bookables: [],
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
        deleteBookable(id) {
            if (confirm("Confirm delete this bookable")) {

                axios.delete("/api/bookables/" + id).then(response => {
                    this.bookables = response.data["data"];
                    this.table.fnDestroy();
                    this.loadDataTable();

                });
            }
        }
    },
    created() {
        axios.get("/api/bookables-for-datatables").then(response => {
            this.bookables = response.data["data"];
            this.loadDataTable();

        });
    }
}
</script>
<style scoped>
/* dashboard buttons */
.btn-sm {
    min-width: 70px !important;
    margin-bottom: 2px !important;
}

td {
    padding: 15px !important;
}

#myTable_paginate,
#myTable_info,
#myTable_filter,
.dt-buttons {
    display: inline-block !important;
    width: 50% !important;
}
</style>



<!-- normal datatable with ajax without adding action btns-->

<!-- 

first:    
npm install --save jszip
npm install --save pdfmake
npm install --save datatables.net-dt
npm install --save datatables.net-buttons-dt
npm install --save datatables.net-vue3

second:
<script setup>
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

const columns = [
    { data: 'id', title: '#' },
    { data: 'title', title: 'Title' },
    { data: 'description', title: 'Description' },
    { data: 'price', title: 'Price' },
    { data: 'action', title: 'Action' },
];

const options = {
    dom: 'Bftip',
    buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
    processing: true,
    serverSide: true,
    ajax: "/api/bookables-for-datatables",

};
</script>

<template>
    <h2>Bookables</h2>
    <div class="table-responsive">
        <DataTable :options="options" :columns="columns" class="table table-striped table-sm">

        </DataTable>
    </div>
</template> -->








