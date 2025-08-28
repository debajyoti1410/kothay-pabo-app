/**** AUTHENTICATE USER INFO HERE ****/
import { defineStore } from 'pinia';

const useAuth = defineStore('auth', () => {
    const getToken = ref(localStorage.getItem('token') || 0);
    
    const setToken = (token) => {
        getToken.value = token;
        localStorage.setItem('token',token);
    };
    
    const removeToken = () => {
        getToken.value = 0;
        localStorage.removeItem('token');
    };

    return {
        getToken,
        setToken,
        removeToken
    };
});

export default useAuth;
