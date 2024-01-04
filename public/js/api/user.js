import {sendAjaxRequest} from "../app.js";

export async function getAuth() {
    const auth_response = await sendAjaxRequest("GET", "/api/auth");
    return await auth_response.json();
}

export async function getProjects(){
    const projects_response = await sendAjaxRequest("GET", "/api/projects");
    return await projects_response.json();
}
export async function getTasks(){
    const tasks_response = await sendAjaxRequest("GET", "/api/tasks");
    return await tasks_response.json();
}
export async function getNotifications(){
    const notification = await sendAjaxRequest("GET", "/api/notifications");
    return await notification.json();
}