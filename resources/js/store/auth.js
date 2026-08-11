import { defineStore } from 'pinia';
import Cookies from 'js-cookie';
import AppStorage from '../helpers/AppStorage';
import Token from '../helpers/Token';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        token: localStorage.getItem('token') || null,
        name: localStorage.getItem('user') || null,
        role: Number(localStorage.getItem('user_role') || 0),
        userId: Number(localStorage.getItem('user_id') || 0),
    }),
    getters: {
        isLoggedIn: (state) => !!(state.token && Token.isValid(state.token)),
        isAdmin: (state) => Number(state.role) === 1,
    },
    actions: {
        applyLoginResponse(response) {
            const data = response.data;
            AppStorage.store(data.access_token, data.name);
            localStorage.setItem('user_role', String(data.user_role));
            localStorage.setItem('user_id', String(data.user_id));
            Cookies.set('userNow', data.user_role, { expires: 7 });
            Cookies.set('userId', data.user_id);
            Cookies.set('usersname', data.name);
            this.token = data.access_token;
            this.name = data.name;
            this.role = Number(data.user_role);
            this.userId = Number(data.user_id);
        },
        logout() {
            AppStorage.clear();
            Cookies.remove('userNow');
            Cookies.remove('userId');
            Cookies.remove('usersname');
            this.token = null;
            this.name = null;
            this.role = 0;
            this.userId = 0;
        },
    },
});
