import {sendAjaxRequest} from "../app.js";

export async function seenProjectNotifications() {
    const notification = await sendAjaxRequest("PUT", "/api/projectNotifications/seen");
    return await notification.json();
}
export async function seenTaskNotifications() {
    const notification = await sendAjaxRequest("PUT", "/api/taskNotifications/seen");
    return await notification.json();
}
export async function seenInviteNotifications() {
    const notification = await sendAjaxRequest("PUT", "/api/inviteNotifications/seen");
    return await notification.json();
}
export async function seenForumNotifications() {
    const notification = await sendAjaxRequest("PUT", "/api/forumNotifications/seen");
    return await notification.json();
}
export async function seenCommentNotifications() {
    const notification = await sendAjaxRequest("PUT", "/api/commentNotifications/seen");
    return await notification.json();
}