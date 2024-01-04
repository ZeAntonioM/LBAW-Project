/* Closes all multi-selectors*/
function resetAll() {
    const multiselectors = document.querySelectorAll('.multiselector');
    multiselectors.forEach((multiselector) => {
        const dropdown = multiselector.querySelector('.dropdown');
        if (!dropdown.classList.contains('hidden')) {
            dropdown.classList.add('hidden');
            const icon = multiselector.querySelector('span i.fas');
            icon.classList.toggle('fa-chevron-down')
        }
    });
}

/*Configs all multi-selectors in the page*/
export function config_multiselector() {
    const multiselectors = document.querySelectorAll('.multiselector');
    
    if (multiselectors != null) {
        multiselectors.forEach((multiselector) =>
            multiselector.querySelector('span').addEventListener('click', (e) => {
                    const dropdown = multiselector.querySelector('.dropdown');
                    if (dropdown.classList.contains('hidden')) resetAll();
                    const icon = multiselector.querySelector('span i.fas');
                    icon.classList.toggle('fa-chevron-down')
                    dropdown.classList.toggle('hidden');
                }
            )
        )
    }
}

/*Extracts values from the {origin} multi-selectors and stores them in the {destiny} input*/
export function multiselector(origin, destiny) {
    const items = document.querySelectorAll(origin + '.multiselector .item input:checked');
    const values = [];
    for (let idx = 0; idx < items.length; idx++) {
        values[idx] = parseInt(items[idx].value);
    }
    const destinyInput = document.querySelector(destiny);
    if (destinyInput != null) destinyInput.value = values;
}