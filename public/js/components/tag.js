import {icons} from "../const/icons.js";

export function createTagCard(tag){

    const tagCard =document.createElement('section');
    const tagContainer = document.createElement('tagContainer');
    const tag_span = document.createElement('span');
    const span = document.createElement('span');
    const actions = document.createElement('section');
    const edit = document.createElement('span');
    const delete_action = document.createElement('span');

    tagCard.classList.add('tagCard');
    tagContainer.classList.add('tagContainer');
    tag_span.classList.add('tag');
    tag_span.classList.add('tag'+tag.id%10);
    actions.classList.add('actions');
    edit.classList.add('edit');
    delete_action.classList.add('delete');
    tag_span.innerText= tag.title;
    span.innerHTML = icons['tasks']+ tag.tasks.length + ' tasks';
    edit.innerHTML = icons['edit'];
    delete_action.innerHTML = icons['trash'];
    actions.append(edit);
    actions.append(delete_action);
    tagContainer.append(tag_span);
    tagCard.append(tagContainer);
    tagCard.append(span);
    tagCard.append(actions);
    return tagCard
}

export function create_edit_component(tag){
    const form = document.createElement('form');
    const input = document.createElement('input');
    const submit = document.createElement('input');
    const errors  = document.createElement('span');

    form.classList.add('hidden');
    errors.classList.add('error');
    form.id= 'edit';
    form.autocomplete='off';
    input.required=true;
    input.maxLength = 20;
    input.minLength = 1;
    input.type='text';
    input.name= 'title';
    input.value= tag.title;
    submit.type='submit';

    form.append(input);
    form.append(submit);
    form.append(errors);

    return form;

}
