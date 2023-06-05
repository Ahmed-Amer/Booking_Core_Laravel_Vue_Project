<!--  datatable with ajax with adding action btns-->
<template>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Reports</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <router-link class="btn btn-md btn-outline-secondary" :to="{ name: 'addreport' }">Create Report</router-link>

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
                    <!-- <th scope="col">Password</th> -->
                    <th scope="col">File Name</th>
                    <th scope="col">Created_at</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="file in  files" :key="file.id">
                    <td>{{ file.id }}</td>
                    <td>{{ file.title }}</td>
                    <td style="text-align: justify !important;">{{ file.description }}</td>
                    <td>{{ file.file_name }}</td>
                    <td>{{ file.created_at.slice(0, 10) }}</td>
                    <td>
                        <button @click="downloadFile(file)" class="btn btn-sm btn-primary">Download</button>
                        <button @click="deleteFile(file.id)" class="btn btn-sm btn-danger">Delete</button>
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
            files: [],
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
        downloadFile(file) {
            axios.get("/api/download_files/" + file.id, { responseType: 'arraybuffer' })
                .then(response => {
                    let blob = new Blob([response.data], { type: 'application/pdf' })
                    let link = document.createElement('a')
                    link.href = window.URL.createObjectURL(blob)
                    link.download = file.file_name
                    link.click()
                });
        },
        deleteFile(id) {
            if (confirm("Confirm delete this file")) {

                axios.delete("/api/reports/" + id).then(response => {
                    this.files = response.data["data"];
                    this.table.fnDestroy();
                    this.loadDataTable();
                });
            }
        }
    },
    created() {
        axios.get("/api/files-for-datatables").then(response => {
            this.files = response.data["data"];
            this.loadDataTable();

        });
    }
}
</script>
<style scoped>
/* dashboard buttons */
.btn-sm {
    min-width: 75px !important;
    font-size: 12px;
    margin: 2px;
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
