import {
    notificationSection,
    projectNotificationCard,
    taskNotificationCard,
    updateNumbers
} from "./components/notifications.js";
import {getAuth, getNotifications, getProjects, getTasks} from "./api/user.js";
const pusher = new Pusher("8afd0da3d4993e84efef", {
    cluster: 'eu',
    encrypted: true,
});
const subscribeToProjectChannels = (projects) => {

    projects.forEach(project => {
        console.log(project.id)
        const channel = pusher.subscribe('project.' + project.id);
        channel.bind('notification-project', function (data) {
            const notification_section =document.querySelector('.notificationSection.title-'+data.type);
            if(data.type === 'Project')notification_section.insertBefore(projectNotificationCard(data,data.project),notification_section.firstChild);
            else if(data.type === 'Forum') notification_section.insertBefore(projectNotificationCard(data,data.post.project),notification_section.firstChild);
            updateNumbers(data.type,1);
        });
    });
};

const subscribeToTasksChannels = (tasks) => {
    tasks.forEach(task => {
        const channel = pusher.subscribe('task.' + task.id);
        channel.bind('notification-task', function (data) {
            const notification_section =document.querySelector('.notificationSection.title-'+data.type);
            if(data.type==='Task') notification_section.insertBefore(taskNotificationCard(data,data.task),notification_section.firstChild);
            else if (data.type==='Comment')  notification_section.insertBefore(taskNotificationCard(data,data.comment.task),notification_section.firstChild);
            updateNumbers(data.type,1);
        });
    });
};
const subscribeToUserChannels = (user) => {
    const channel = pusher.subscribe('user.' + user.id);
    channel.bind('notification-user', function (data) {
        const notification_section =document.querySelector('.notificationSection.title-'+data.type);
        notification_section.insertBefore(projectNotificationCard(data,data.project),notification_section.firstChild);
        updateNumbers(data.type,1);
    });
};


export async function subscribeToChannels() {
    const user = await getAuth();
    if(user.is_admin) return;
    const projects = await getProjects();
    const tasks = await getTasks();
    subscribeToProjectChannels(projects);
    subscribeToTasksChannels(tasks);
    subscribeToUserChannels(user);
}

export async function createNotifications(){
    const notification = await getNotifications();
    const notificationsContainer = document.querySelector('.notificationsContainer');
    notificationsContainer.append(notificationSection('Project',notification.projectNotifications));
    notificationsContainer.append(notificationSection('Invite',notification.inviteNotifications));
    notificationsContainer.append(notificationSection('Task',notification.taskNotifications));
    notificationsContainer.append(notificationSection('Forum',notification.postNotifications));
    notificationsContainer.append(notificationSection('Comment',notification.commentNotifications));
}



