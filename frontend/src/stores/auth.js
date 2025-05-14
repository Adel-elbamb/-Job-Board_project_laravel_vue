import { defineStore } from "pinia";
import axios from "axios";

export const useAuthStore = defineStore("auth", {
  state: () => {
    const storedUser = localStorage.getItem("user");
    const storedToken = localStorage.getItem("token");
    return {
      token: storedToken && storedToken !== "undefined" ? storedToken : null,
      user:
        storedUser && storedUser !== "undefined"
          ? JSON.parse(storedUser)
          : null,
    };
  },
  actions: {
    setToken(token) {
      if (token && token !== "undefined") {
        this.token = token;
        localStorage.setItem("token", token);
        axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
      } else {
        console.error("Attempted to set invalid token:", token);
      }
    },
    setUser(user) {
      if (user && user !== "undefined") {
        this.user = user;
        localStorage.setItem("user", JSON.stringify(user));
      } else {
        console.error("Attempted to set invalid user:", user);
      }
    },
    logout() {
      this.token = null;
      this.user = null;
      localStorage.removeItem("token");
      localStorage.removeItem("user");
      delete axios.defaults.headers.common["Authorization"];
    },
  },
});
