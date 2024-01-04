import { showSnackbar } from "../snackbar.js";

const searchbar = document.querySelector('#search-bar');
const form = document.querySelector('#search')
const snackbar = document.querySelector('#snackbar');

searchbar.addEventListener('keydown',(e)=>{
    if(e.key!== 'Enter') return;
    form.submit();
})

if (snackbar != null) showSnackbar();
