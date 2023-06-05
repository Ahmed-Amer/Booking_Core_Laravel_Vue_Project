<template>
    <div>

        <!-- <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
  <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6" href="#">Company name</a>
  <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <input class="form-control form-control-dark w-100 rounded-0 border-0" type="text" placeholder="Search" aria-label="Search">
  <div class="navbar-nav">
    <div class="nav-item text-nowrap">
      <a class="nav-link px-3" href="#">Sign out</a>
    </div>
  </div>
</header> -->

        <div class="container-fluid">
            <div class="row">
                <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-body-tertiary sidebar collapse">
                    <div class="position-sticky pt-3 sidebar-sticky">
                        <ul class="nav flex-column">
                            <li class="nav-item" @click="selectedComponent = 'appMainPage'">
                                <a class="nav-link" :class="{ 'active': selectedComponent == 'appMainPage' }">
                                    <i class="fa fa-house"></i>
                                    Dashboard
                                </a>
                            </li>
                            <li class="nav-item" @click="selectedComponent = 'appBookablePage'">
                                <a class="nav-link" :class="{ 'active': selectedComponent == 'appBookablePage' }">
                                    <i class="fa fa-hotel"></i>
                                    Bookables
                                </a>
                            </li>

                            <li class="nav-item" @click="selectedComponent = 'appBookingPage'">
                                <a class="nav-link" :class="{ 'active': selectedComponent == 'appBookingPage' }">
                                    <i class="fa fa-cart-shopping"></i>
                                    Bookings
                                </a>
                            </li>

                            <li class="nav-item" @click="selectedComponent = 'appUserPage'">
                                <a class="nav-link" :class="{ 'active': selectedComponent == 'appUserPage' }">
                                    <i class="fa fa-user"></i>
                                    Users
                                </a>
                            </li>

                            <li class="nav-item" @click="selectedComponent = 'appReportPage'">
                                <a class="nav-link" :class="{ 'active': selectedComponent == 'appReportPage' }">
                                    <i class="fa fa-file"></i>
                                    Reports
                                </a>
                            </li>
                        </ul>

                        <h6
                            class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-body-secondary text-uppercase">
                            <span>Saved reports</span>
                            <a class="link-secondary" href="#" aria-label="Add a new report">
                                <span data-feather="plus-circle" class="align-text-bottom"></span>
                            </a>
                        </h6>
                        <ul class="nav flex-column mb-2">
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <span data-feather="file-text" class="align-text-bottom"></span>
                                    Current month
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <span data-feather="file-text" class="align-text-bottom"></span>
                                    Last quarter
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <span data-feather="file-text" class="align-text-bottom"></span>
                                    Social engagement
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <span data-feather="file-text" class="align-text-bottom"></span>
                                    Year-end sale
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>

                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <!-- <div
                        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">Dashboard</h1>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <div class="btn-group me-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
                            </div>
                        </div>
                    </div> -->

                    <!-- load main page content dynamically -->
                    <component :is="selectedComponent"></component>
                </main>
            </div>
        </div>
    </div>
</template>

<script>
import { isLoggedIn  , checkAdmin } from '../../shared/auth';

import MainPage from './mainPage.vue';
import BookablePage from './bookablePage.vue';
import BookingPage from './bookingPage.vue';
import UserPage from './userPage.vue';
import ReportPage from './reportPage.vue';

export default {
    data() {
        return {
            selectedComponent: 'appMainPage',
        }
    },
    components: {
        appMainPage: MainPage,
        appBookablePage: BookablePage,
        appBookingPage: BookingPage,
        appUserPage: UserPage,
        appReportPage: ReportPage
    },
    beforeRouteEnter(to, from, next) {
        if (isLoggedIn() && checkAdmin()) {
                next();
            } else {
                isLoggedIn() ? next({ name: 'home' }) : next({ name: 'login' });

            }

    }

}
</script>

<style scoped>
body {
    font-size: .875rem;
}

.fa {
    width: 16px;
    height: 16px;
    text-align: right;
    margin-right: 5px;
}

/*
 * Sidebar
 */

.sidebar {
    position: fixed;
    top: 0;
    /* rtl:raw:
  right: 0;
  */
    bottom: 0;
    /* rtl:remove */
    left: 0;
    z-index: 100;
    /* Behind the navbar */
    padding: 48px 0 0;
    /* Height of navbar */
    box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
}

@media (max-width: 767.98px) {
    .sidebar {
        top: 5rem;
    }
}

.sidebar-sticky {
    height: calc(100vh - 48px);
    overflow-x: hidden;
    overflow-y: auto;
    /* Scrollable contents if viewport is shorter than content. */
}

.sidebar .nav-link {
    color: #333;
    cursor: pointer;
}

.sidebar .nav-link .feather {
    margin-right: 4px;
    color: #727272;
}

.sidebar .nav-link.active {
    color: #2470dc;
}

.sidebar .nav-link:hover .feather,
.sidebar .nav-link.active .feather {
    color: inherit;
}

.sidebar-heading {
    font-size: .75rem;
}
</style>