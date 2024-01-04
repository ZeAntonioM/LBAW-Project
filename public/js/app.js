import {getAuth, getNotifications, getProjects, getTasks} from "./api/user.js";
import {
    calculateNumbers,
    notificationSection,
    projectNotificationCard,
    updateNumbers
} from "./components/notifications.js";
import {createNotifications, subscribeToChannels} from "./notifications.js";

import {
    projectTeamPageRegex,
    projectTaskPageRegex,
    adminUserPageRegex,
    adminProjectPageRegex,
    projectHomePageRegex,
    projectTagsPageRegex,
} from "./const/regex.js";


const currentPath = window.location.pathname;
const projectHomePage = projectHomePageRegex.test(currentPath);
const projectTeamPage = projectTeamPageRegex.test(currentPath);
const projectTaskPage = projectTaskPageRegex.test(currentPath);
const adminUsersPage = adminUserPageRegex.test(currentPath);
const projectAdminPage = adminProjectPageRegex.test(currentPath);
const projectTagsPage = projectTagsPageRegex.test(currentPath);
const projectFilesPage = (/^\/project\/[0-9]+\/files$/).test(currentPath);
if(projectTaskPage)document.querySelector('#projectTasks').classList.add('selected')
else if (projectTeamPage)document.querySelector('#projectTeam').classList.add('selected')
else if(projectHomePage) document.querySelector('#projectHome').classList.add('selected')
else if(projectFilesPage) document.querySelector('#projectFiles').classList.add('selected')

else if(adminUsersPage) document.querySelector('#adminUsersPage').classList.add('selected')
else if( projectAdminPage) document.querySelector('#adminProjectsPage').classList.add('selected')

else if(projectTagsPage) document.querySelector('#projectTags').classList.add('selected')
function buildFetchOptions(method, data) {
    const options = {
        method: method,
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            "Content-Type": "application/x-www-form-urlencoded",
            "X-Requested-With": "XMLHttpRequest",
        }
    };

    if (method != "GET")
        options.body = encodeForAjax(data);

    return options;
}

export function encodeForAjax(data) {
    if (data == null) return null;
    return Object.keys(data).map(function (k) {
        return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&');
}

export async function sendAjaxRequest(method, url, data) {
    return await fetch(url, buildFetchOptions(method, data));
}

// realtime-notifications.js

createNotifications().then(()=>{calculateNumbers()});
subscribeToChannels().then()

