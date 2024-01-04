import { attachDialogs, closeDialogs } from "../modal.js";
import { sendAjaxRequest } from "../app.js";
import { showSnackbar, buildSnackbar } from "../snackbar.js";

const currentPath = window.location.pathname;

document.addEventListener('DOMContentLoaded', function () {
    attachDialogs();
    addRemoveUserEvent();
    giveUserToActionHandler();
    addUserBtnEvent();
    addSendEmailBtnEvent();
});

function giveUserToActionHandler() {
    const actionBtn = document.querySelector('#removeUserBtn');

    document.querySelectorAll('.remove-user-btn').forEach((button) => {
        button.addEventListener('click', () => {
            actionBtn.dataset.user = button.dataset.user;
        })
    })
}

function addRemoveUserEvent() {
    const actionBtn = document.querySelector('#removeUserBtn');

    actionBtn.addEventListener('click', () => {
        sendAjaxRequest('DELETE', currentPath + '/remove', {'user': actionBtn.dataset.user}).catch(() => {
            console.error("Network error");
        }).then(async response => {
            const data = await response.json();

            if (response.ok) {
                document.querySelector('#user-item-' + actionBtn.dataset.user).remove();
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
}

function addUserBtnEvent() {
    const submitForm = document.querySelector('#add-user-form');
    const formInput = submitForm.querySelector('input[name=\'email\']');
    const sendEmailDialog = document.querySelector('dialog[data-open-form-id=openSendEmailInviteModal');

    submitForm.addEventListener('submit', event => {
        event.preventDefault();

        sendAjaxRequest('GET', '/api/check-user/' + formInput.value.toLowerCase()).catch(() => {
            console.error("Network error");
        }).then(async response => {
            const data = await response.json();

            if (response.ok) {
                if (data) {
                    submitForm.submit();
                } else {
                    sendEmailDialog.showModal()
                }
            }
            else {
                console.error(`Error ${response.status}: ${data.error}`);
            }
        }).catch(() => {
            console.error('Error parsing JSON');
        })
    })
}

function addSendEmailBtnEvent() {
    const button = document.querySelector('#send-email-invite-btn');
    button.blur();
    const submitForm = document.querySelector('#add-user-form');
    const formInput = submitForm.querySelector('input[name=\'email\']')

    button.addEventListener('click', () => {
        document.body.style.cursor = 'progress';
        closeDialogs();
        sendAjaxRequest('POST', currentPath + '/invite', {'email': formInput.value.toLowerCase()}).catch(() => {
            console.error("Network error");
        }).then(async response => {
            const data = await response.json();

            document.querySelector('.team').appendChild(buildSnackbar((response.ok ? 'success' : 'error'), data.message));
            document.body.style.cursor = 'default';
            showSnackbar();
        }).catch(() => {
            console.error('Error parsing JSON');
        })
    });
}