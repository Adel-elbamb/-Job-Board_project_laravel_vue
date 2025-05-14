
import { defineStore } from 'pinia';

export const useUserStore = defineStore('user', {
  state: () => ({
    token: null,
    role: null,
  }),
  actions: {
    login(token, role) {
      this.token = token;
      this.role = role;
      localStorage.setItem('token', token); 
      localStorage.setItem('role', role);
    },
    logout() {
      this.token = null;
      this.role = null;
      localStorage.removeItem('token');
      localStorage.removeItem('role');
    },
  },
});