class Token {
    isValid(token) {
        const payload = this.payload(token);
        if (!payload?.iss) {
            return false;
        }

        const iss = String(payload.iss);
        return iss.includes('/api/auth/login') || iss.includes('/api/auth/signup') || iss.includes('/api/auth/register');
    }

    payload(token) {
        try {
            const part = token.split('.')[1];
            return this.decode(part);
        } catch {
            return null;
        }
    }

    decode(payload) {
        return JSON.parse(atob(payload));
    }
}

export default new Token();
