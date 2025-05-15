<template>
  <div class="dashboard-wrapper">
    <!-- Sidebar -->
    <div class="sidebar">
      <div class="sidebar-header">
        <h3 class="text-white"><i class="bi bi-speedometer2 me-2"></i>Admin Panel</h3>
      </div>
      <ul class="nav flex-column">
        <li class="nav-item">
          <router-link to="/dashboard" class="nav-link active">
            <i class="bi bi-house-door me-2"></i>Dashboard
          </router-link>
        </li>
        <li class="nav-item" v-for="item in dashboardItems" :key="item.title">
          <router-link :to="item.link" class="nav-link">
            <i :class="item.icon" class="me-2"></i>{{ item.title }}
          </router-link>
        </li>
      </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <!-- Header -->
      <header class="header">
        <h1 class="fw-bold mb-0">Admin Dashboard</h1>
        <div class="header-controls">
          <div class="dropdown">
            <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
              Sort By: {{ sortOption }}
            </button>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" @click="sortOption = 'Count'">Count</a></li>
              <li><a class="dropdown-item" @click="sortOption = 'Title'">Title</a></li>
            </ul>
          </div>
        </div>
      </header>

      <!-- Content -->
      <div class="content p-4">
        <!-- Loading State -->
        <div v-if="isLoading" class="text-center my-5">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>

        <!-- Error State -->
        <div v-else-if="hasError" class="alert alert-danger">
          <p><strong>Error:</strong> {{ errorMessage }}</p>
          <button class="btn btn-primary mt-2" @click="fetchStats">Try Again</button>
        </div>

        <!-- Dashboard Content -->
        <div v-else>
          <!-- Stats Cards -->
          <div class="row g-4 mb-5">
            <div v-for="item in sortedItems" :key="item.title" class="col-md-6 col-lg-3">
              <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center">
                  <i :class="item.icon" class="mb-3 icon"></i>
                  <h5 class="card-title fw-semibold">{{ item.title }}</h5>
                  <p class="display-6 fw-bold text-primary">{{ item.count }}</p>
                  <router-link :to="item.link" class="btn btn-outline-primary btn-sm">View Details</router-link>
                </div>
              </div>
            </div>
          </div>

          <!-- Chart Section -->
          <div class="row g-4">
            <div class="col-12">
              <div class="card shadow-sm border-0">
                <div class="card-body">
                  <h5 class="card-title fw-semibold">Statistics Overview</h5>
                  <canvas id="statsChart"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import api from '@/axios';
import Chart from 'chart.js/auto';

export default {
  data() {
    return {
      dashboardItems: [
        { title: 'Total Users', link: '/users', count: 0, icon: 'bi bi-people-fill' },
        { title: 'Total Jobs', link: '/jobs', count: 0, icon: 'bi bi-briefcase-fill' },
        { title: 'Pending Jobs', link: '/jobs/pending', count: 0, icon: 'bi bi-hourglass-split' },
        { title: 'Accepted Jobs', link: '/jobs/accepted', count: 0, icon: 'bi bi-check-circle-fill' },
        { title: 'Rejected Jobs', link: '/jobs/rejected', count: 0, icon: 'bi bi-x-circle-fill' }
      ],
      isLoading: false,
      hasError: false,
      errorMessage: '',
      sortOption: 'Count',
      chart: null
    };
  },
  computed: {
    sortedItems() {
      return [...this.dashboardItems].sort((a, b) => {
        return this.sortOption === 'Count' ? b.count - a.count : a.title.localeCompare(b.title);
      });
    }
  },
  mounted() {
    this.fetchStats();
  },
  updated() {
    if (!this.isLoading && !this.hasError && !this.chart) {
      this.createChart();
    }
  },
  methods: {
    async fetchStats() {
      this.isLoading = true;
      this.hasError = false;
      this.errorMessage = '';

      try {
        const token = localStorage.getItem('token');
        if (!token) {
          this.hasError = true;
          this.errorMessage = 'You need to be logged in to access this dashboard';
          this.isLoading = false;
          return;
        }

        this.dashboardItems.forEach(item => {
          item.count = 0;
        });

        try {
          const usersResponse = await api.get('/admin/users');
          if (usersResponse.data && usersResponse.data.users) {
            this.dashboardItems[0].count = usersResponse.data.users.total || usersResponse.data.users.length || 0;
          }
        } catch (error) {
          console.error('Error fetching users:', error.response?.status, error.message);
        }

        try {
          const jobsResponse = await api.get('/admin/jobs');
          if (jobsResponse.data && jobsResponse.data.jobs) {
            this.dashboardItems[1].count = jobsResponse.data.jobs.total || jobsResponse.data.jobs.length || 0;
          }
        } catch (error) {
          console.error('Error fetching jobs:', error.response?.status, error.message);
        }

        try {
          const pendingResponse = await api.get('/admin/jobs/pending');
          if (pendingResponse.data && pendingResponse.data.pendingJobs) {
            this.dashboardItems[2].count = pendingResponse.data.pendingJobs.total || pendingResponse.data.pendingJobs.length || 0;
          }
        } catch (error) {
          console.error('Error fetching pending jobs:', error.response?.status, error.message);
        }

        try {
          const acceptedResponse = await api.get('/admin/jobs/accepted');
          if (acceptedResponse.data && acceptedResponse.data.acceptedJobs) {
            this.dashboardItems[3].count = acceptedResponse.data.acceptedJobs.total || acceptedResponse.data.acceptedJobs.length || 0;
          }
        } catch (error) {
          console.error('Error fetching accepted jobs:', error.response?.status, error.message);
        }

        try {
          const rejectedResponse = await api.get('/admin/jobs/rejected');
          if (rejectedResponse.data && rejectedResponse.data.rejectedJobs) {
            this.dashboardItems[4].count = rejectedResponse.data.rejectedJobs.total || rejectedResponse.data.rejectedJobs.length || 0;
          }
        } catch (error) {
          console.error('Error fetching rejected jobs:', error.response?.status, error.message);
        }

      } catch (error) {
        console.error('Error fetching stats:', error);
        this.hasError = true;
        this.errorMessage = 'Failed to load dashboard data. ' +
          (error.response?.status === 401 ? 'You are not authorized to view this data.' :
           'There might be an issue with the server.');
      } finally {
        this.isLoading = false;
      }
    },
    createChart() {
      const ctx = document.getElementById('statsChart').getContext('2d');
      this.chart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: this.dashboardItems.map(item => item.title),
          datasets: [{
            label: 'Statistics',
            data: this.dashboardItems.map(item => item.count),
            backgroundColor: 'rgba(52, 168, 83, 0.6)',
            borderColor: 'rgba(52, 168, 83, 1)',
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          scales: {
            y: {
              beginAtZero: true,
              title: {
                display: true,
                text: 'Count'
              }
            }
          },
          plugins: {
            legend: {
              display: false
            }
          }
        }
      });
    }
  }
};
</script>

<style scoped>
.dashboard-wrapper {
  display: flex;
  min-height: 100vh;
  background-color: #f5f7fa;
}

/* Sidebar */
.sidebar {
  width: 250px;
  background-color: #34a853;
  color: white;
  position: fixed;
  height: 100%;
  overflow-y: auto;
  transition: width 0.3s ease;
}

.sidebar-header {
  padding: 1.5rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.nav-link {
  padding: 0.75rem 1.5rem;
  color: white;
  transition: background-color 0.3s ease;
}

.nav-link:hover,
.nav-link.active {
  background-color: rgba(255, 255, 255, 0.1);
}

/* Main Content */
.main-content {
  margin-left: 250px;
  flex: 1;
  display: flex;
  flex-direction: column;
}

.header {
  background: white;
  padding: 1.5rem 2rem;
  border-bottom: 1px solid #e5e7eb;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.header-controls {
  display: flex;
  gap: 1rem;
}

/* Content */
.content {
  flex: 1;
  padding: 2rem;
}

.card {
  border-radius: 12px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  background: white;
}

.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
}

.card-body {
  padding: 1.5rem;
}

.icon {
  font-size: 2.5rem;
  color: #34a853;
}

.btn-outline-primary {
  border-color: #34a853;
  color: #34a853;
}

.btn-outline-primary:hover {
  background-color: #34a853;
  color: white;
}

.btn-primary {
  background-color: #34a853;
  border-color: #34a853;
}

.btn-primary:hover {
  background-color: #2d9145;
  border-color: #2d9145;
}

.dropdown-item:active {
  background-color: #34a853;
  color: white;
}

/* Responsive Design */
@media (max-width: 992px) {
  .sidebar {
    width: 200px;
  }
  .main-content {
    margin-left: 200px;
  }
}

@media (max-width: 768px) {
  .sidebar {
    width: 100%;
    position: relative;
    height: auto;
  }
  .main-content {
    margin-left: 0;
  }
}
</style>
