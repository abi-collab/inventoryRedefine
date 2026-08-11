class AppStorage {
    storeToken(token) {
        localStorage.setItem('token', token);
    }

    storeUser(user) {
        localStorage.setItem('user', user);
    }

    store(token, user) {
        this.storeToken(token);
        this.storeUser(user);
    }

    clear() {
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        localStorage.removeItem('user_role');
        localStorage.removeItem('user_id');
    }

    getToken() {
        return localStorage.getItem('token');
    }

    getUser() {
        return localStorage.getItem('user');
    }
}

export default new AppStorage();
