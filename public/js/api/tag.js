import {sendAjaxRequest} from "../app.js";

export async function edit_tag(tag_id, title) {
    const project_response = await sendAjaxRequest("PUT", "/tag/" + tag_id + "/edit", {'title': title.value});
    const res = await project_response.json();
    if(res.errors) throw res.errors;
}
export async function delete_tag(tag_id){
    const project_response = await sendAjaxRequest("DELETE", "/tag/" + tag_id + "/delete");
    const res = await project_response.json();
    if(res.errors) throw res.errors;
}