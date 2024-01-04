import { sendAjaxRequest } from "./app.js";

const currentPath = window.location.pathname;

document.querySelectorAll('.own-comment').forEach((comment) => {

    comment.querySelector('.edit-comment').addEventListener('click', async (e) => {
        e.preventDefault();

        const id = comment.id;

        const comment_body = comment.querySelector('.comment-body');
        const text = comment.querySelector('.content').innerHTML;

        const textarea = document.createElement('textarea');
        const submit = document.createElement('button');
        const cancel = document.createElement('button');

        textarea.classList.add('editCommentTextarea');
        submit.classList.add('edit-comment');
        cancel.classList.add('edit-comment');

        textarea.value = text;
        
        submit.innerHTML = 'Save';
        cancel.innerHTML = 'Cancel';

        comment_body.innerHTML = '';
        comment_body.appendChild(textarea);
        comment_body.appendChild(submit);
        comment_body.appendChild(cancel);

        const p = document.createElement('p');
        p.classList.add('content');

        submit.addEventListener('click', async (e) => {
            e.preventDefault();

            const content = textarea.value;

            const response = await sendAjaxRequest('PUT', currentPath+'/edit'+'/'+id, {'content': content});

            const data = await response.json();

            if (!data.errors) {
                comment_body.innerHTML = '';
                p.innerHTML = content;
                comment_body.appendChild(p);
            }
        });

        cancel.addEventListener('click', async (e) => {
            e.preventDefault();
            comment_body.innerHTML = '';
            p.innerHTML = text;
            comment_body.appendChild(p);
        });
    });
});
