import { defineStore } from "pinia";
import { axios } from "~/utils/api/axios.js";
export const userInfo = defineStore({
  id: "userInfo",
  state: () => ({
    userInfo: null,
  }),
  actions: {
    async setUserInfo() {
      try {
        const response = await axios.get(`${API_BE}/api/user`);
        const { user } = response.data
        this.userInfo = user;
      } catch {

      }
    },
  },
});
