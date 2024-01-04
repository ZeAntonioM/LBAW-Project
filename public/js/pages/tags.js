import {edit_tag, delete_tag} from "../api/tag.js";
import {projectTagsPageRegex, teamPageProjectRegex} from "../const/regex.js";
import {encodeForAjax, sendAjaxRequest} from "../app.js";
import {create_edit_component, createTagCard} from "../components/tag.js";
import { attachDialogs, closeDialogs} from '../modal.js';

const currentPath = window.location.pathname;
const searchBar = document.querySelector('#search-bar');
const deleteTagBtn = document.querySelector('#deleteTagBtn');

searchBar.addEventListener('input', async (e) => {
    const project_id = currentPath.match(projectTagsPageRegex)[1];
    let query = encodeForAjax({"query": searchBar.value})+'&'+  encodeForAjax({"project": project_id});
    sendAjaxRequest("GET", "/api/tags?" + query).catch(() => {
        console.error("Network error");
    }).then(async response => {
        const data = await response.json();
        await updateTagTable(data);
    }).catch(() => {
        console.error('Error parsing JSON');
    });
});

deleteTagBtn.addEventListener('click', async () => {
    const tag_id = deleteTagBtn.dataset.tagid;
    const errors = document.querySelector('#tag' + tag_id + ' .error')
    const tagSection = document.querySelector('#tag' + tag_id);
    try {
        await delete_tag(tag_id);
    } catch (err) {
        errors.innerHTML = err;
        return;
    }
    errors.innerHTML = '';
    tagSection.remove();
    closeDialogs();
});

function setUpActions() {
    const edits_actions = document.querySelectorAll('.tagCard .edit');
    const edits_submits = document.querySelectorAll(".tagSection form");
    const deletes = document.querySelectorAll('.tagCard .delete');

    deletes.forEach(
        (delete_action) => delete_action.addEventListener('click',
            (e) => {
                const tag_id = delete_action.parentNode.parentNode.parentNode.id.match(/tag(\d+)/)[1];
                deleteTagBtn.dataset.tagid = tag_id;
            })
    )

    edits_actions.forEach(
        (edit) => edit.addEventListener('click',
            (e) => {
                const tag_id = edit.parentNode.parentNode.parentNode.id.match(/tag(\d+)/)[1];
                const form = document.querySelector('#tag' + tag_id + ' form');
                form.classList.toggle('hidden');
            }
        ));
    
    edits_submits.forEach(
        (edit) => edit.addEventListener('submit',
            async (e) => {
                e.preventDefault();
                const tag_id = edit.parentNode.id.match(/tag(\d+)/)[1];
                const title = document.querySelector('#tag' + tag_id + " form input[type='text'");
                const errors = document.querySelector('#tag' + tag_id + ' .error')
                try {
                    await edit_tag(tag_id, title);
                } catch (err) {
                    errors.innerHTML = err;
                    return;
                }
                errors.innerHTML = '';
                document.querySelector('#tag' + tag_id + ' .tagCard .tagContainer .tag').innerText = title.value;
                const form = document.querySelector('#tag' + tag_id + ' form');
                form.classList.toggle('hidden');
            }
        )
    );
}

function updateTagTable(data) {
    const table = document.querySelector('.tags');
    table.innerHTML = '';
    data.forEach((tag) => {
        const tagSection = document.createElement('section');
        tagSection.classList.add('tagSection');
        tagSection.id = 'tag'+tag.id;
        tagSection.append(createTagCard(tag));
        tagSection.append(create_edit_component(tag));
        table.append(tagSection);
    });
    attachDialogs();
    setUpActions();
}

document.addEventListener('DOMContentLoaded', function () {
    attachDialogs();
    setUpActions();
});
