

import Repository from "./Repository";

const resource = "/capabilities";

export default {
    get() {
        return Repository.get(`${resource}`);
    },

    // getUser(userId) {
    //     return Repository.get(`user/${userId}`);
    // },

    create(payload) {
        return Repository.post(`${resource}`, payload);
    },

    // update(id, payload) {
    //     return Repository.post(`user/${id}`, payload);
    // },

    // delete(id) {
    //     return Repository.delete(`user/${id}`);
    // }
}