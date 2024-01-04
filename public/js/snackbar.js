import {icons} from './const/icons.js'

export function showSnackbar() { 
    const snackbar = document.querySelector('#snackbar');
    snackbar.classList.toggle('showSnackbar');
  
    setTimeout(function () { 
        snackbar.classList.toggle('showSnackbar');
        snackbar.remove();
    }, 5000); 
}

export function buildSnackbar(type, content) {
    const contentParagraph = document.createElement('p');
    contentParagraph.innerHTML = content;
    const snackbar = document.createElement('div');
    snackbar.setAttribute('id', 'snackbar');
    snackbar.classList.add('snackbar-' + type);

    snackbar.innerHTML += icons[type];
    snackbar.appendChild(contentParagraph);

    return snackbar;
}