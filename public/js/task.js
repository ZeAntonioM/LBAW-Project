import {config_multiselector, multiselector} from "./components/multiselector.js";
import { sendAjaxRequest } from './app.js'
import { addOpenModalBtn, attachDialogs } from './modal.js'
import { getDateString } from './utils.js';

const currentPath = window.location.pathname;

function addTaskEventHandlers() {
    const closeTaskButton = document.querySelector('#closeTaskBtn');
    const cancelTaskButton = document.querySelector('#cancelTaskBtn');
    const reopenTaskButton = document.querySelector('#reopenTaskBtn');
    const form = document.querySelector('form');

    if (closeTaskButton != null) changeTaskStatusEvent(closeTaskButton, 'closed');
    if (cancelTaskButton != null) changeTaskStatusEvent(cancelTaskButton, 'canceled');
    if (reopenTaskButton != null) changeTaskStatusEvent(reopenTaskButton, 'open');

    attachDialogs();

    config_multiselector();

    if (form != null) {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            multiselector('.users', '#assigns');
            multiselector('.tags', '#tags');
            form.submit();
        });
    }    
};

function changeTaskStatusEvent(button, status) {
    button.addEventListener('click', () =>{
        sendAjaxRequest('PUT', currentPath + '/edit/status', {'status': status}).catch(() => {
            console.error("Network error");
        }).then(async response => {
            const data = await response.json();

            if (response.ok) {
                const task = data.task;
                const deadline = document.querySelector('.deadlineContainer')
                const finishedTimeSpan = document.createElement('span');
                const actionString = status.charAt(0).toUpperCase() + status.slice(1);
                
                buildStatusDependantElems(task.status, data.closed_user_name);

                editStatusChip(status);

                deadline.innerHTML = '';
                
                finishedTimeSpan.innerHTML = '<i class="fa-solid fa-clock"></i> '
                finishedTimeSpan.innerHTML += (task.status == 'open' ? 'Deadline: ': `${actionString} at: `) + getDateString((task.status == 'open' ? task.deadline : task.endtime));
                deadline.appendChild(finishedTimeSpan);

                document.querySelectorAll('dialog').forEach(dialog => {
                    dialog.close();
                });
            }
            else {
                console.error(`Error ${response.status}: ${data.error}`);
            }
        }).catch(() => {
            console.error('Error parsing JSON');
        })
    });
};

function buildStatusDependantElems(status, closed_user_name) {
    const closeOpenContainer = document.querySelector('.primaryContainer');
    const editCancelContainer = document.querySelector('.actions');
    const actionString = status.charAt(0).toUpperCase() + status.slice(1);
    const description = document.querySelector('.primaryContainer').firstChild;

    if (status == 'open') {
        document.querySelector('.openReopenModal').remove();
        document.querySelector('#finishedTaskUser').remove();

        const nextSibling = description.nextSibling;

        const closeTaskBtn = document.createElement('a');
        const cancelTaskBtn = document.createElement('a');
        const editTaskBtn = document.createElement('a');

        closeTaskBtn.classList.add('buttonLink', 'openCloseModal');
        closeTaskBtn.innerHTML += '<i class="fa-solid fa-folder-closed"></i> Close task';

        cancelTaskBtn.classList.add('buttonLink', 'cancel', 'openCancelModal');
        cancelTaskBtn.innerHTML += '<i class="fa-solid fa-ban"></i> Cancel';

        editTaskBtn.classList.add('buttonLink', 'edit');
        editTaskBtn.setAttribute('href', currentPath + '/edit');
        editTaskBtn.innerHTML += '<i class="fa-solid fa-pen-to-square"></i> Edit';

        editCancelContainer.appendChild(editTaskBtn);
        editCancelContainer.appendChild(cancelTaskBtn);
        closeOpenContainer.insertBefore(closeTaskBtn, nextSibling);
    }
    else {
        document.querySelector('.openCloseModal').remove();
        document.querySelector('.openCancelModal').remove();
        editCancelContainer.querySelector('.edit').remove();

        const nextSibling = description.nextSibling;

        const finishedTaskUser = document.createElement('section');
        const finishedTaskUserSpan = document.createElement('span');
        const sideContainer = document.querySelector('.sideContainer');

        finishedTaskUserSpan.innerHTML = `<i class="fa-solid fa-user"></i> ${actionString} by: ${closed_user_name}`;
        finishedTaskUser.setAttribute('id', 'finishedTaskUser');
        finishedTaskUser.appendChild(finishedTaskUserSpan);
        sideContainer.insertBefore(finishedTaskUser, sideContainer.children[1]);

        const reopenTaskBtn = document.createElement('a');
        reopenTaskBtn.classList.add('buttonLink', 'openReopenModal');
        
        reopenTaskBtn.innerHTML = '<i class="fa-solid fa-folder-open"></i> Reopen task';

        closeOpenContainer.insertBefore(reopenTaskBtn, nextSibling)
    }

    document.querySelectorAll('dialog').forEach((dialog) => {
        const openBtn = document.querySelector('.' + dialog.dataset.openFormId);

        if (openBtn != null) addOpenModalBtn(dialog, openBtn);
    })
}

function editStatusChip (status) {
    const statusChip = document.querySelector('.status');

    statusChip.classList.remove('open');
    statusChip.classList.remove('closed');
    statusChip.classList.remove('canceled');
    statusChip.classList.add(status);

    if (status == 'open') {
        statusChip.innerHTML = '<i class="fa-solid fa-folder-open"></i> Open'
    } else if (status == 'closed') {
        statusChip.innerHTML = '<i class="fa-solid fa-folder-closed"></i> Closed'
    } else {
        statusChip.innerHTML = '<i class="fa-solid fa-ban"></i> Canceled'
    }
}

addTaskEventHandlers();
