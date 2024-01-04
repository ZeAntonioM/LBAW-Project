@php
    $icons = array(
        'error' => 'fa-circle-exclamation',
        'warning' => 'fa-triangle-exclamation',
        'info' => 'fa-circle-info',
        'success' => 'fa-circle-check',
    )
@endphp

<div id="snackbar" class="snackbar-{{ $type }}"> <i class="fa-solid {{ $icons[$type] }}"></i> <p>{{$content}}</p></div>