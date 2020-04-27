
import axios from 'axios'

const baseDomain = "http://tasklist-api.local:8081";
const baseURL = `${baseDomain}/api`;

const token = localStorage.getItem("token") || "";
console.log('token data', localStorage.getItem("token"));
// Repository.defaults.headers.common = {
//     'Authorization': 'Bearer ' + resp.token,
//     'Content-type': 'application/x-www-form-urlencoded'
// };

export default axios.create({
    baseURL,
    headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer '+token ,
    }
});