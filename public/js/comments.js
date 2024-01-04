


document.addEventListener('DOMContentLoaded', function () {
        let container = document.querySelector('.comments');
        let projectId = container.dataset.project;
        let taskId = container.dataset.task;
        let currentPage = 1;
        let loading = false;
  
        function loadMore() {
            if (loading) return;
            loading = true;
  
            currentPage++;
  
            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (this.readyState === 4) {
                    if (this.status === 200) {
                        try {
                            const data = JSON.parse(this.responseText);
                            data.htmlArray.forEach((html) => {
                                container.insertAdjacentHTML('beforeend', html);
                            });
  
                        } catch (error) {
                            console.error('Error parsing JSON:', error);
                        }
                    } else {
                        console.error('Error ' + this.status + ': ' + this.statusText);
                    }
                    loading = false;
                }
            };
            const currentPath = window.location.pathname;
            const allPath = `${currentPath}/next?page=${currentPage}&project=${projectId}&task=${taskId}`;
            xhr.open('GET', `${allPath}`, true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.setRequestHeader('X-CSRF-TOKEN', document.head.querySelector('meta[name="csrf-token"]').content);
            xhr.send();
        }
  
        container.addEventListener('scroll', function () {
            var scrollY = container.scrollTop + 0.5;
            var height = container.scrollHeight - container.clientHeight;
  
            if (scrollY === height) {
                loadMore();
            }
        });
    });
  
  