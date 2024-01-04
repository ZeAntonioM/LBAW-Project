import { initializeInfiniteScroll } from './infinite-scroll.js';
const searchBar = document.getElementById('search-bar');
searchBar.addEventListener('keydown', (e) => {
    if(e.key!=='Enter') return;
    const form = document.querySelector('#search');
    form.submit();
});

let container = document.querySelector('.tasks');
let projectId = container.dataset.project;
initializeInfiniteScroll(container,projectId);
