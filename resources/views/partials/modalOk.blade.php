@php
    $icons = array(
        'mymodal-error' => 'fa-circle-exclamation',
        'mymodal-warning' => 'fa-triangle-exclamation',
        'mymodal-info' => 'fa-circle-info',
        'mymodal-success' => 'fa-circle-check',
    )
@endphp

<dialog class="mymodal {{ $type }}" data-open-form-id="{{ $openDialogClass }}">
    <div class="mymodal-header">
        <div class="icon-title-wrapper">
            <i class="fa-solid {{ $icons[$type] }}"></i>
            <h3> {{ $modalTitle }}</h3>
        </div>
        <i class="fa-solid fa-x"></i>
    </div>
    <div class="mymodal-body">
        <p> {{ $modalBody }}</p>
    </div>
</dialog>