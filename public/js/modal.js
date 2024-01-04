export function attachDialogs() {
    document.querySelectorAll('dialog').forEach((dialog) => {
        attachModal(dialog);
        const openBtns = document.querySelectorAll('.' + dialog.dataset.openFormId);

        openBtns.forEach((button) => {
            addOpenModalBtn(dialog, button);
        })
    })
}

export function attachModal(dialog) {
    const closeDialog = dialog.querySelector('.close-modal');
    const closeIcon = dialog.querySelector('.fa-x');

    dialog.addEventListener('click', (event) => {        
        const modalRect = dialog.getBoundingClientRect();
        const inDialog = (modalRect.top <= event.clientY && event.clientY <= modalRect.top + modalRect.height &&
            modalRect.left <= event.clientX && event.clientX <= modalRect.left + modalRect.width);
        if (!inDialog) {
            dialog.close();
        }
    });
    
    if (closeDialog != null)
        closeDialog.addEventListener('click', () => {
            dialog.close();
        });
    
    if (closeIcon != null)
        closeIcon.addEventListener('click', () => {
            dialog.close();
        });
}

export function addOpenModalBtn(dialog, openBtn) {
    const confirmBtn = dialog.querySelector('.mymodal-confirm');

    openBtn.addEventListener('click', () => {
        dialog.showModal();
        if (confirmBtn != null)
            confirmBtn.blur();
    });
}

export function closeDialogs() {
    document.querySelectorAll('dialog').forEach(dialog => {
        dialog.close();
    });
}