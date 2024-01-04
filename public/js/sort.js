export const sortButtons = document.querySelectorAll('.sort-btn');

export function initializeSortButtons(sortButtons, sortFiles) {
    sortButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            var sortBy = button.dataset.sort;
            var type = button.dataset.type;
            sortFiles(sortBy, type);
        });
    });
}

export function sortFiles(property, type) {
    var filesContainer = document.querySelector('.files-container');
    var files = Array.from(document.querySelectorAll('.file'));
    filesContainer.innerHTML = '';
    files.sort(function (a, b) {
        var aValue = a.dataset[property];
        var bValue = b.dataset[property];
        if (property === 'date' && type === 'up') {
            aValue = aValue.split('/').reverse().join('');
            bValue = bValue.split('/').reverse().join('');
            return aValue > bValue ? -1 : aValue < bValue ? 1 : 0;
        } else if (property === 'date' && type === 'down') {
            aValue = aValue.split('/').reverse().join('');
            bValue = bValue.split('/').reverse().join('');
            return aValue > bValue ? 1 : aValue < bValue ? -1 : 0;
        } else if (property === 'name' && type === 'up') {
            return aValue.localeCompare(bValue);
        } else if (property === "name" && type === 'down') {
            return bValue.localeCompare(aValue);
        } else if (property === 'size' && type === 'up') {
            return parseInt(aValue) - parseInt(bValue);
        } else if (property === 'size' && type === 'down') {
            return parseInt(bValue) - parseInt(aValue);
        }
        return 0;
    });
    files.forEach(function (file) {
        filesContainer.appendChild(file);
    });
}
