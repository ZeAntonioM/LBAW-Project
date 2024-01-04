import {getDateString} from "../utils.js";
import {
    seenCommentNotifications,
    seenForumNotifications,
    seenInviteNotifications,
    seenProjectNotifications,
    seenTaskNotifications
} from "../api/notifications.js";

export function notificationSection(title, notifications) {
    const section = document.createElement('section');
    section.classList.add('notificationSection');
    section.classList.add('title-'+title);

    const header = document.createElement('header');

    const titleElement = document.createElement('h3');
    titleElement.textContent = title;

    const notificationCount = document.createElement('span');
    notificationCount.classList.add('number')

    const chevronUp = document.createElement('span');
    chevronUp.innerHTML = '<i class="fas fa-chevron-up"></i>';


    const notificationsList = document.createElement('section');
    notificationsList.className = ' notifications-list hidden';
    let unseen =0;
    notifications.forEach(notification => {
        if(!notification.seen) unseen++;
        if(title ==='Project') notificationsList.insertBefore(projectNotificationCard(notification,notification.project),notificationsList.firstChild);
        else if(title ==='Invite') notificationsList.insertBefore(projectNotificationCard(notification,notification.project),notificationsList.firstChild);
        else if(title === 'Task') notificationsList.insertBefore(taskNotificationCard(notification,notification.task),notificationsList.firstChild);
        else if( title === 'Forum') notificationsList.insertBefore(projectNotificationCard(notification,notification.post.project),notificationsList.firstChild);
        else if(title=== 'Comment') notificationsList.insertBefore(taskNotificationCard(notification,notification.comment.task),notificationsList.firstChild);
    });
    notificationCount.textContent = unseen.toString();
    header.appendChild(chevronUp);
    header.appendChild(titleElement);
    header.appendChild(notificationCount);


    header.addEventListener('click',(e)=>{
        const i= header.querySelector('i');
        i.classList.toggle('fa-chevron-down');
        notificationsList.classList.toggle('hidden');
        seenProjectNotifications().then();
        resetNumber(title);
        if(title ==='Project') seenProjectNotifications().then();
        else if(title ==='Invite') seenInviteNotifications().then();
        else if(title === 'Task') seenTaskNotifications().then();
        else if( title === 'Forum') seenForumNotifications().then();
        else if(title=== 'Comment') seenCommentNotifications().then();

    })
    section.appendChild(header);
    section.appendChild(notificationsList);

    return section
}

export function projectNotificationCard(notification,project) {
    const notificationCard = document.createElement('section');
    notificationCard.className = 'notificationCard projectNotification';

    const anchor = document.createElement('a');

    const header = document.createElement('header');

    const projectName = document.createElement('h4');
    projectName.textContent = project.title;

    const checkIcon = document.createElement('i');
    if(!notification.seen)checkIcon.className = 'fa-solid fa-check';
    else checkIcon.className = 'fa-solid fa-check-double';

    if(notification.seen) header.classList.add('seen');
    header.appendChild(projectName);
    header.appendChild(checkIcon);

    const messageSection = document.createElement('section');
    messageSection.textContent = notification.description; // Replace with the actual property from your notification object

    const footer = document.createElement('footer');
    footer.textContent = getDateString(notification.notifications_date) ; // Replace with the actual property from your notification object

    anchor.appendChild(header);
    anchor.appendChild(messageSection);
    anchor.appendChild(footer);

    notificationCard.appendChild(anchor);

    return notificationCard;
}
export function taskNotificationCard(notification,task) {

    const notificationCard = document.createElement('section');
    notificationCard.className = 'notificationCard projectNotification';

    const anchor = document.createElement('a');

    const header = document.createElement('header');
    const info = document.createElement('section');
    info.classList.add('info');

    const projectName = document.createElement('h4');
    projectName.textContent = task.project.title; // Replace with the actual property from your notification object
    const  taskName = document.createElement('h6');
    taskName.textContent = task.title;
    info.append(projectName);
    info.append(taskName);

    const checkIcon = document.createElement('i');
    if(!notification.seen)checkIcon.className = 'fa-solid fa-check';
    else checkIcon.className = 'fa-solid fa-check-double';

    if(notification.seen) header.classList.add('seen');
    header.appendChild(info);
    header.appendChild(checkIcon);

    const messageSection = document.createElement('section');
    messageSection.textContent = notification.description; // Replace with the actual property from your notification object

    const footer = document.createElement('footer');
    footer.textContent = getDateString(notification.notifications_date) ; // Replace with the actual property from your notification object

    anchor.appendChild(header);
    anchor.appendChild(messageSection);
    anchor.appendChild(footer);

    notificationCard.appendChild(anchor);

    return notificationCard;
}

function resetNumber(type){
    let number =document.querySelector('.title-'+type+'.notificationSection header .number');
    const notification = document.querySelector('body > header .notifications .number');
    notification.innerHTML = parseInt(notification.innerHTML)- parseInt(number.innerHTML);
    number.innerHTML = 0;
}
export function updateNumbers(type,n){
    let number =document.querySelector('.title-'+type+'.notificationSection header .number');
    number.innerHTML = parseInt(number.innerHTML)+n;
    const notification = document.querySelector('body > header .notifications .number');
    notification.innerHTML = parseInt(notification.innerHTML)+n;

}
export function calculateNumbers(){
    const notification = document.querySelector('body > header .notifications .number');
    let n=0;
    let number = document.querySelector('.title-Project.notificationSection header .number');
    n+= parseInt(number.innerHTML);
    number = document.querySelector('.title-Task.notificationSection header .number');
    n+= parseInt(number.innerHTML);
    number = document.querySelector('.title-Invite.notificationSection header .number');
    n+= parseInt(number.innerHTML);
    number = document.querySelector('.title-Forum.notificationSection header .number');
    n+= parseInt(number.innerHTML);
    number = document.querySelector('.title-Comment.notificationSection header .number');
    n+= parseInt(number.innerHTML);
    notification.innerHTML=n;

}



