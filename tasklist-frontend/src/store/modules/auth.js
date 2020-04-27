/* eslint-disable promise/param-names */
import {
    AUTH_REQUEST,
    AUTH_ERROR,
    AUTH_SUCCESS,
    AUTH_LOGOUT
} from "../actions/auth";

import Repository from "./../../repositories/Repository";

function getRole() {
    let userdata = localStorage.getItem("user") || "";
    if (userdata != '')
    {
        userdata = JSON.parse(userdata);
        return (userdata.roles && userdata.roles[0]) ? userdata.roles[0]:"";
    } return "";
};

function getUserData() {
    let userdata = localStorage.getItem("user") || "";
    if (userdata != '') {
        return JSON.parse(userdata);
    } return {};
};

function getRespectPoint() {
    let userdata = localStorage.getItem("user") || "";
    if (userdata != '') {
        userdata = JSON.parse(userdata);
        return (userdata.respectPoint) ? userdata.respectPoint : 0 ;
    } return "";
};

const state = {
    token: localStorage.getItem("token") || "",
    status: "",
    user: {},
    role: getRole(),
    hasLoadedOnce: false,
    respectPoint: getRespectPoint(),
    userData: getUserData()
};

const getters = {
    isAuthenticated: state => !!state.token,
    authStatus: state => state.status,
    role: state => state.role,
    respectPoint: state => state.respectPoint,
    userData: state => state.userData
};

const actions = {
    [AUTH_REQUEST]: ({ commit, dispatch }, data) => {
        return new Promise((resolve, reject) => {
            commit(AUTH_REQUEST);
            Repository.post("/login_check", data)
            .then(function (response) {
                
                commit(AUTH_SUCCESS, response.data);
                resolve({ status: true, message: 'Login Successfully.' });
            })
                .catch(function (error,response) {
                    self.error = true;
                    localStorage.removeItem("token");
                    localStorage.removeItem("user");
                    resolve({ status: false, message:'Invalid credentials.'});
            });
        });
    },
    [AUTH_LOGOUT]: ({ commit }) => {
        return new Promise(resolve => {
            commit(AUTH_LOGOUT);
            localStorage.removeItem("token");
            localStorage.removeItem("user");
            Repository.defaults.headers.common = {};
            resolve(true);
        });
    }
};

const mutations = {
    [AUTH_REQUEST]: state => {
        state.status = "loading";
    },
    [AUTH_SUCCESS]: (state, resp) => {

        state.status = "success";
        state.token = resp.token;
        state.user = resp.data;
        state.hasLoadedOnce = true;
        state.role = resp.data.roles[0];
        state.respectPoint = resp.data.respectPoint;
        Repository.defaults.headers.common = {
            'Authorization': 'Bearer ' + resp.token,
            'Content-type': 'application/x-www-form-urlencoded'
        };
        
        localStorage.token = resp.token;
        localStorage.user = JSON.stringify(resp.data);

    },
    [AUTH_ERROR]: state => {
        state.status = "error";
        state.hasLoadedOnce = true;
    },
    [AUTH_LOGOUT]: state => {
        state.token = "";
        state.user = {};

    }
};

export default {
    state,
    getters,
    actions,
    mutations
};
