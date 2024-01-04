// favorite.js

import { encodeForAjax, sendAjaxRequest } from "./app.js";

document.addEventListener('DOMContentLoaded', function() {
    var favoriteStars = document.querySelectorAll('.favorite-star');

    favoriteStars.forEach(function(star) {
        star.addEventListener('click', function() {
            var projectId = star.dataset.project;
            sendAjaxRequest("PUT", `/project/${projectId}/favorite`, {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.head.querySelector('meta[name="csrf-token"]').content
            },null)
            .catch(() => {
                console.error('error');
            })
            .then(async response => {
                const data = await response.json();
                if (data.status === 'favorited') {
                    star.style.color = 'orange';
                    star.dataset.favorite = 'true';
                } else if (data.status === 'unfavorited') {
                    star.style.color = 'grey';
                    star.dataset.favorite = 'false';
                }
                
            })
            .catch(() => {
                console.error('Error parsing JSON');
            });
        });
    });
});
