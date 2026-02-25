import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.withCredentials = true;

// Send Laravel CSRF token for same-origin POST/PUT/PATCH/DELETE
const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    const match = document.cookie.match(/XSRF-TOKEN=([^;]+)/);
    if (match) {
        window.axios.defaults.headers.common['X-XSRF-TOKEN'] = decodeURIComponent(match[1]);
    }
}
