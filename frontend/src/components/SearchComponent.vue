<script setup>
// (Keep your existing script section exactly as is)
</script>

<template>
  <div class="container-fluid green-gradient-bg">
    <div class="container w-75 mt-5 mb-0">
      <!-- Search Part -->
      <form @submit.prevent="search(1)">
        <div class="mb-3 position-relative search-container">
          <input
            v-model="filters.titledesc"
            type="text"
            class="form-control main-input text-white shadow-lg"
            placeholder="Search for Jobs ..."
            aria-label="Search for Jobs ..."
          />
          <div class="position-absolute icon-container">
            <span class=""><i class="fa-brands fa-searchengin fs-3"></i></span>
          </div>
        </div>
        <div class="container shadow-sm p-5 rounded-4 mt-0 green-light-bg">
          <h5 class="text-green-dark pb-3 text-uppercase fw-semibold">
            Advanced Search
          </h5>
          <div class="d-flex justify-content-center align-items-center flex-wrap">
            <input
              v-model="filters.location"
              type="text"
              placeholder="Location"
              class="form-control w-25 rounded-5 py-2 px-3 mx-2 my-2 border-green"
            />
            <input
              v-model="filters.category"
              type="text"
              placeholder="Category"
              class="form-control w-25 rounded-5 py-2 px-3 mx-2 my-2 border-green"
            />
            <input
              v-model="filters.salary_min"
              type="number"
              placeholder="Minimum Salary"
              class="form-control w-25 rounded-5 py-2 px-3 mx-2 my-2 border-green"
            />
            <input
              v-model="filters.salary_max"
              type="number"
              placeholder="Maximum Salary"
              class="form-control w-25 rounded-5 py-2 px-3 mx-2 my-2 border-green"
            />
            <input
              v-model="filters.from_date"
              type="date"
              placeholder="From Date"
              class="form-control w-25 rounded-5 py-2 px-3 mx-2 my-2 border-green"
            />
          </div>
          <div class="d-flex justify-content-between align-content-center mt-4">
            <p class="text-green-dark">
              <span class="fw-bold">{{ pagination.total }}</span> results found
            </p>
            <div>
              <button
                type="reset"
                @click="reset"
                class="btn btn-outline-green mx-2 rounded-5 text-uppercase shadow-sm"
              >
                Reset
              </button>
              <button
                type="submit"
                class="green-gradient-btn btn text-white px-4 py-2 shadow-sm text-uppercase mx-2"
              >
                Search Jobs
              </button>
            </div>
          </div>
        </div>
      </form>

      <!-- Jobs Section -->
      <section class="job-section py-5">
        <div class="container">
          <!-- Empty State -->
          <div v-if="jobs.length === 0 && !loading" class="text-center py-5">
            <div class="icon-circle-lg bg-green-lightest mx-auto mb-4">
              <i class="fas fa-briefcase fa-3x text-green"></i>
            </div>
            <h4 class="fw-bold text-green-dark mb-3">No jobs found</h4>
            <p class="text-green-light mb-4">Try adjusting your search filters</p>
            <button
              @click="reset"
              class="btn green-gradient-btn text-white px-4"
            >
              Reset Filters
            </button>
          </div>

          <!-- Job Cards Grid -->
          <div v-else class="row g-4">
            <div
              v-for="(job, index) in jobs"
              :key="job.id"
              class="col-xl-4 col-lg-4 col-md-6 col-sm-12"
            >
              <div
                class="card job-card h-100 shadow-sm animate-fade-up border-green-light"
                :style="{ animationDelay: `${index * 0.1}s` }"
              >
                <!-- Ribbon -->
                <div class="ribbon" v-if="job.featured">Featured</div>

                <div class="card-body d-flex flex-column p-4">
                  <!-- Header -->
                  <div class="d-flex align-items-center mb-3">
                    <div class="icon-circle me-3">
                      <i class="bi bi-briefcase-fill text-white"></i>
                    </div>
                    <h5 class="mb-0 fw-bold text-green-darker">{{ job.title }}</h5>
                  </div>

                  <!-- Description -->
                  <p class="text-muted small mb-3">{{ job.description.substring(0,80) }}...</p>

                  <!-- Info Badges -->
                  <div class="mb-3 d-flex flex-wrap gap-2">
                    <span class="badge bg-green-lightest text-green-dark border-green">
                      <i class="fas fa-map-marker-alt text-green me-1"></i>
                      {{ job.location || 'Remote' }}
                    </span>
                    <span class="badge bg-green-lightest text-green-dark border-green">
                      <i class="fas fa-dollar-sign text-green me-1"></i>
                      {{ job.salary_min ? formatCurrency(job.salary_min) : 'N/A' }} -
                      {{ job.salary_max ? formatCurrency(job.salary_max) : 'N/A' }}
                    </span>
                  </div>

                  <!-- Skills -->
                  <div class="mb-4">
                    <strong class="text-green-dark">Skills:</strong>
                    <div v-if="getSkillsArray(job.skills).length > 0" class="d-flex flex-wrap mt-2 gap-2">
                      <span
                        v-for="skill in getSkillsArray(job.skills)"
                        :key="skill"
                        class="badge green-skill-badge"
                      >
                        <i class="bi bi-check-circle-fill me-1"></i> {{ skill }}
                      </span>
                    </div>
                    <p v-else class="text-green-light small mt-1">No specific skills mentioned</p>
                  </div>

                  <!-- Apply Button -->
                  <button
                    @click="goToApplyPage(job.id)"
                    class="btn green-gradient-btn text-white fw-bold rounded-pill mt-auto w-100 hover-grow"
                  >
                    <i class="bi bi-send-fill me-2"></i> Apply Now
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Pagination -->
      <div v-if="jobs.length > 0" class="d-flex justify-content-center align-items-center mb-5">
        <button
          :disabled="pagination.current_page === 1"
          class="btn btn-outline-green mx-2 rounded-circle"
          @click="search(pagination.current_page - 1)"
        >
          <i class="fa-solid fa-arrow-left"></i>
        </button>
        <button
          v-for="page in pagination.pages_no"
          :key="page"
          :class="[
            `btn mx-2 rounded-circle`,
            pagination.current_page == page
              ? `green-gradient-btn text-white`
              : `btn-outline-green`,
          ]"
          @click="search(page)"
        >
          {{ page }}
        </button>
        <button
          :disabled="pagination.current_page === pagination.last_page"
          class="btn btn-outline-green mx-2 rounded-circle"
          @click="search(pagination.current_page + 1)"
        >
          <i class="fa-solid fa-arrow-right"></i>
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
:root {
  --green: #34A853;
  --green-dark: #2d9145;
  --green-darker: #1e6e34;
  --green-light: #66BB6A;
  --green-lighter: #81C784;
  --green-lightest: #E8F5E9;
  --green-bg-gradient: linear-gradient(135deg, var(--green), var(--green-dark));
}

/* Text Colors */
.text-green { color: var(--green) !important; }
.text-green-dark { color: var(--green-dark) !important; }
.text-green-darker { color: var(--green-darker) !important; }
.text-green-light { color: var(--green-light) !important; }
.text-green-lighter { color: var(--green-lighter) !important; }

/* Backgrounds */
.green-gradient-bg { background: linear-gradient(to bottom, #f8fff9, #ffffff); }
.green-light-bg { background-color: var(--green-lightest); }
.bg-green-lightest { background-color: var(--green-lightest) !important; }

/* Buttons */
.green-gradient-btn {
  background: var(--green-bg-gradient);
  border: none;
  transition: all 0.3s ease;
}
.green-gradient-btn:hover {
  background: linear-gradient(135deg, var(--green-dark), var(--green-darker));
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(52, 168, 83, 0.3);
}

.btn-outline-green {
  color: var(--green);
  border-color: var(--green);
  transition: all 0.3s ease;
}
.btn-outline-green:hover {
  background-color: var(--green-lightest);
}

/* Borders */
.border-green { border: 1px solid rgba(52, 168, 83, 0.3) !important; }
.border-green-light { border: 1px solid rgba(52, 168, 83, 0.15) !important; }

/* Search Input */
.main-input {
  background: var(--green-bg-gradient);
  border-radius: 50px;
  padding: 22px;
  border: none;
  box-shadow: 0 4px 15px rgba(52, 168, 83, 0.2);
}
.main-input:focus {
  background: linear-gradient(135deg, var(--green), var(--green-dark));
  box-shadow: 0 4px 20px rgba(52, 168, 83, 0.3);
}
.main-input::placeholder {
  color: rgba(255, 255, 255, 0.85);
}

.search-container i {
  transition: 0.3s;
  color: white;
}
.search-container:hover i {
  transform: rotateY(180deg);
}

/* Job Cards */
.job-card {
  border-radius: 12px;
  position: relative;
  overflow: hidden;
  transition: all 0.3s ease;
}
.job-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 15px 30px rgba(52, 168, 83, 0.15);
}

.ribbon {
  position: absolute;
  top: 0;
  right: 0;
  background: var(--green-bg-gradient);
  color: white;
  font-size: 0.75rem;
  font-weight: bold;
  padding: 4px 12px;
  border-bottom-left-radius: 8px;
  z-index: 2;
}

.icon-circle {
  width: 50px;
  height: 50px;
  background: var(--green-bg-gradient);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 4px 8px rgba(52, 168, 83, 0.2);
}

.icon-circle-lg {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.green-skill-badge {
  background: var(--green-bg-gradient);
  color: white;
  padding: 6px 12px;
  border-radius: 50px;
  font-size: 0.8rem;
  display: inline-flex;
  align-items: center;
  box-shadow: 0 2px 4px rgba(30, 110, 52, 0.2);
  transition: transform 0.2s ease;
}
.green-skill-badge:hover {
  transform: translateY(-2px);
}

/* Animations */
.animate-fade-up {
  animation: fadeUp 0.6s ease forwards;
  opacity: 0;
}
@keyframes fadeUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.hover-grow {
  transition: transform 0.3s ease;
}
.hover-grow:hover {
  transform: scale(1.03);
}

/* Responsive Adjustments */
@media (max-width: 992px) {
  .container.w-75 {
    width: 90% !important;
  }
}

@media (max-width: 768px) {
  .job-card {
    border-radius: 10px;
  }
  .icon-circle {
    width: 40px;
    height: 40px;
  }
  .main-input {
    padding: 16px;
  }
}
</style>
