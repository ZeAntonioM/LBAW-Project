import { initializeInfiniteScroll } from './infinite-scroll.js';

let container = document.querySelector('.forum-container');
let projectId = container.dataset.project;
initializeInfiniteScroll(container,projectId);

