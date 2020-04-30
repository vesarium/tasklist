

import Repository from "./Repository";

const resource = "/tasks";

export default {
    get() {
        return Repository.get(`${resource}`);
    },

    getMyTasks() {
        return Repository.get(`my-tasks`);
    },

    getTask(taskId) {
        return Repository.get(`task/${taskId}`);
    },

    approveTask(taskId) {
        return Repository.get(`accept-task/${taskId}`);
    },
    
    create(payload) {
        return Repository.post(`${resource}`, payload);
    },

    update(id, payload) {
        return Repository.post(`user/${id}`, payload);
    },

    delete(id) {
        return Repository.delete(`task/${id}`);
    }
}