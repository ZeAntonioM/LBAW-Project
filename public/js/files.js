
import { sortButtons,initializeSortButtons, sortFiles } from './sort.js';

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('file').addEventListener('change', function () {
        document.getElementById('uploadForm').submit();
    });

    document.getElementById('uploadButton').addEventListener('click', function () {
        document.getElementById('file').click();
    });
    

    initializeSortButtons(sortButtons, sortFiles);

});
