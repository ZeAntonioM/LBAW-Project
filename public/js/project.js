import {encodeForAjax, sendAjaxRequest} from "./app.js";

import { attachDialogs } from './modal.js'


document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.project-action-button').forEach(function (action) {
      action.addEventListener('click', function () {
            let actionId = action.getAttribute("id");
            let formId = actionId.substring(0, actionId.lastIndexOf("-") + 1) + "form";
        
            let form = document.querySelector("#" + formId);
            form.submit();
        });
    })
});

export async function getProject(project_id) {
    const project_query = encodeForAjax({'project': project_id});
    const project_response = await sendAjaxRequest("GET", "/api/projects?" + project_query);
    const project = await project_response.json();
    return project;
}

attachDialogs();
