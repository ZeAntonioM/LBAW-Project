import { encodeForAjax, sendAjaxRequest } from "./app.js";

const searchBar = document.getElementById('search-bar');
searchBar.addEventListener('input', (e) => {
    const input = encodeForAjax({"query": searchBar.value});

    sendAjaxRequest("GET", "/api/users?" + input).catch(() => {
        console.error("Network error");
    }).then(async response => {
        const data = await response.json();
        updateSearchedUsers(data);
    }).catch(() => {
        console.error('Error parsing JSON');
    });
});

function updateSearchedUsers(data) {
    let usersSection = document.querySelector('.userContainer');
    usersSection.innerHTML = '';

    data.forEach(user => {
        const divWrapper = document.createElement('div');
        const nameSection = document.createElement('section');
        const emailSection = document.createElement('section');
        const roleSection = document.createElement('section');
        const editSection = document.createElement('section')
        const deleteSection = document.createElement('section')
        const editAnchor = document.createElement('a');
        const deleteAnchor = document.createElement('a');
        const editButton = document.createElement('button');
        const deleteButton = document.createElement('button');

        divWrapper.classList.add('user');

        nameSection.classList.add('name');
        nameSection.textContent = user.name;
        emailSection.classList.add('email');
        nameSection.textContent = user.email;
        roleSection.classList.add('role');
        roleSection.textContent = user.isAdmin ? 'Admin' : 'User';
        editButton.textContent = 'Edit';
        deleteButton.textContent = 'Delete';
        editAnchor.setAttribute('href', `/user-profile/${user.id}/edit`);
        editAnchor.appendChild(editButton);
        deleteAnchor.setAttribute('href', `/admin/users/${user.id}/delete`);
        deleteAnchor.appendChild(deleteButton);
        editSection.classList.add('change');
        editSection.appendChild(editAnchor);
        deleteSection.classList.add('change');
        deleteSection.appendChild(deleteAnchor);

        divWrapper.appendChild(nameSection);
        divWrapper.appendChild(emailSection);
        divWrapper.appendChild(roleSection);
        divWrapper.appendChild(editSection);
        divWrapper.appendChild(deleteSection);

        usersSection.appendChild(divWrapper);
    });
}