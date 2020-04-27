import axios from "axios";

export default axios.create({
    baseURL: "http://tasklist-api.local:8081/api",
    headers: {
        "Content-type": "application/json"
    }
});
