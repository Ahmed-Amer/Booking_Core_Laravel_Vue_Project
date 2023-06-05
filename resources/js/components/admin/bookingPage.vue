<!--  datatable with ajax with adding action btns-->
<template>
    <h2 class="pt-2 pb-1 mb-4 border-bottom">Bookings</h2>

    <div class="table-responsive">
        <table class="table table-striped table-sm" id="myTable">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">From</th>
                    <th scope="col">To</th>
                    <th scope="col">Price</th>
                    <th scope="col">Created_at</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="booking in  bookings" :key="booking.id">
                    <td>{{ booking.id }}</td>
                    <td>{{ booking.from }}</td>
                    <td>{{ booking.to }}</td>
                    <td>{{ booking.total_price + '$' }}</td>
                    <td>{{ booking.created_at.slice(0, 10) }}</td>
                    <td>
                        <router-link :to="{ name: 'showbookable', params: { id: booking.bookable_id } }"
                            class="btn btn-sm btn-primary">Preview Bookable</router-link>
                        <button @click="deleteBooking(booking.id)" class="btn btn-sm btn-danger">Delete</button>
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
            bookings: [],
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
        deleteBooking(id) {
            if (confirm("Confirm delete this booking")) {

                axios.delete("/api/bookings/" + id).then(response => {
                    this.bookings = response.data["data"];
                    this.table.fnDestroy();
                    this.loadDataTable();

                });
            }
        }
    },
    created() {
        axios.get("/api/bookings-for-datatables").then(response => {
            this.bookings = response.data["data"];
            this.loadDataTable();

        });
    }
}
</script>
<style scoped>
/* dashboard buttons */
.btn {
    min-width: 110px !important;
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
}</style>
