import { createRouter, createWebHistory } from "vue-router";
import JobList from "../views/JobList.vue";
import ApplyForm from "../views/ApplyForm.vue";
import CandidateDashboard from "../views/CandidateDashboard.vue";
import Login from "../views/Login.vue";

const routes = [
  { path: "/", name: "JobList", component: JobList },
  {
    path: "/apply/:jobId?",
    name: "ApplyForm",
    component: ApplyForm,
    props: true,
  },
  {
    path: "/dashboard",
    name: "CandidateDashboard",
    component: CandidateDashboard,
  },
  { path: "/login", name: "Login", component: Login },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

export default router;
