import axios from "axios";
import { useRouter } from "vue-router";
import { emitter } from "./event-bus";

const api = axios.create({
    baseURL: 'http://localhost:8000/api',
    headers: {
        'Content-Type': 'multipart/form-data',
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
    }
});

// if(localStorage.getItem('token')){
//     api.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`
//     await api.get('user').then(res => {
//         console.log(res)
//     }).catch(err => {
//         console.log(err)
//         api.defaults.headers.common['Authorization'] = ''
//         localStorage.removeItem('token')
//         localStorage.removeItem('user')
//         useRouter().push({ name: 'login' })
//     })
// }



export default api