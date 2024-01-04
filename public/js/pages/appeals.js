const currentPath = window.location.pathname;
const searchBar = document.getElementById('search-bar');

if( adminPageRegex.test(currentPath)){
    const form = document.querySelector('#search');
    searchBar.addEventListener('keydown',(e)=>{
        if(e.key!=='Enter') return;
        form.submit();
    })
}