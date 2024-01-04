<dialog class="mymodal" data-open-form-id="{{ $openDialogClass }}">
    <div class="mymodal-header">
        <h3> {{ $modalTitle }}</h3>
        <i class="fa-solid fa-x"></i>
    </div>
    <div class="mymodal-body">
        <p> {{ $modalBody }}</p>
    </div>
    <div class="mymodal-buttons">
        <a class="close-modal">Close</a>
        <button type="{{ isset($formId) ? "submit" : "button"}}" class="mymodal-confirm" 
            @isset ($actionId)
                id="{{ $actionId }}"
            @endisset 
            @isset ($formId)
                form="{{ $formId }}"
            @endisset
        >
            Confirm
        </button>
    </div>
</dialog>