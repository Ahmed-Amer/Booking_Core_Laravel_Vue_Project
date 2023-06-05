<template>
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="card card-stats">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <div class="info-icon text-center icon-warning">
                                <i class="fa fa-hotel"></i>
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="numbers">
                                <p class="card-category">Bookables</p>
                                <h3 class="card-title">120</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <hr>
                    <div class="stats">
                        <i class="fa-regular fa-plus"></i>  <router-link :to="{ name: 'addbookable' }"> Create Bookable </router-link>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card card-stats">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <div class="info-icon text-center icon-warning">
                                <i class="fa fa-cart-shopping"></i>
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="numbers">
                                <p class="card-category">Bookings</p>
                                <h3 class="card-title">100</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <hr>
                    <div class="stats">
                        <i class="fa-regular fa-plus"></i> <router-link :to="{ name: 'dashboard' }"> Show Bookings  </router-link>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card card-stats">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <div class="info-icon text-center icon-warning">
                                <i class="fa fa-users"></i>
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="numbers">
                                <p class="card-category">Users</p>
                                <h3 class="card-title">50</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <hr>
                    <div class="stats">
                        <i class="fa-regular fa-plus"></i> <router-link :to="{ name: 'adduser' }"> Create User </router-link>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card card-stats">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <div class="info-icon text-center icon-warning">
                                <i class="fa fa-file"></i>
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="numbers">
                                <p class="card-category">Reports</p>
                                <h3 class="card-title">20</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <hr>
                    <div class="stats">
                        <i class="fa-regular fa-plus"></i> <router-link :to="{ name: 'addreport' }"> Create Report </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Area Chart -->
        <div class="col-md-12">
            <div class="card shadow mb-4">

                <!-- Card Body -->
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="myAreaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>


<script>
import Chart from 'chart.js/auto';

export default {

    mounted() {


        const MONTHS = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ];

        function months(config) {
            var cfg = config || {};
            var count = cfg.count || 12;
            var section = cfg.section;
            var values = [];
            var i, value;

            for (i = 0; i < count; ++i) {
                value = MONTHS[Math.ceil(i) % 12];
                values.push(value.substring(0, section));
            }

            return values;
        }
        const labels = months({ count: 10 });
        const data = {
            labels: labels,
            datasets: [{
                label: 'Reservations for the last 10 months',
                data: [65, 55, 60, 65, 70, 75, 70, 80, 85, 75],
                fill: false,
                borderColor: 'rgb(13, 110, 253)',
                tension: 0.1
            }]
        };
        const options = {
            scales: {
                x: {
                    grid: {
                        display: true,
                    },
                },

                y: {
                    grid: {
                        display: true,
                    },
                },
            },
            plugins: { legend: { display: true }, }
        }


        new Chart(
            document.getElementById('myAreaChart'),
            {
                options: options,
                type: 'line',
                data: data,

            }
        );
    }
};
</script>




<style scoped>
.card-stats {
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 0.0625rem solid rgba(34, 42, 66, .05);
    border-radius: 0.2857rem;
}

.card-stats {
    background: #605e5e;
    border: 0;
    position: relative;
    width: 100%;
    margin-bottom: 30px;
    box-shadow: 0 1px 20px 0 rgba(0, 0, 0, .1);
}

.card-footer {
    border: none !important;
}

.card .card-body {
    padding: 15px;
    flex: 1 1 auto;
}

hr {
    margin: 0 !important;
}

.card-stats .info-icon.icon-warning {
    background: #b3b291;
    background-size: 210% 210%;
    background-position: 100% 0;
}

.card-stats .info-icon {
    height: 50px;
    width: 50px;
    border-radius: 50%;
    background: #e14eca;
    background-image: linear-gradient(to bottom left, #e14eca, #ba54f5, #e14eca);
    background-size: 210% 210%;
    background-position: 100% 0;
}

.card-stats .info-icon i {
    color: #fff;
    font-size: 1.5em;
    padding: 11px 7px;
}

.card-stats .stats {
    color: hsla(0, 0%, 100%, .6);
    margin: 10px 0px;
    font-size: 1em;
}

.tim-icons {
    display: inline-block;
    font: normal normal normal 1em/1 'Nucleo';
    vertical-align: middle;
    speak: none;
    text-transform: none;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

.card-stats .numbers {
    text-align: right;
}

.card-stats .numbers .card-category {
    margin-bottom: 0;
    color: #9a9a9a;
}

.card-stats .numbers .card-title {
    margin-top: 0;
}

.card .card-body .card-title {
    color: #fff;
    text-transform: inherit;
    font-weight: 300;
    margin-bottom: 0.75rem;
}
</style>