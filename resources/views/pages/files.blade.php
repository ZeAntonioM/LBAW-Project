@extends('layouts.project')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/files.css') }}">
@endpush
@push('scripts')
<script type="module" src={{ url('js/app.js') }} defer></script>
<script type="module" src={{ url('js/files.js') }} defer></script>
<script type="module" src={{ url('js/sort.js') }} defer></script>
@endpush
@section('content')
<div class = "container-fluid">
    <div class = "row ">
            <div class ="col-12">
            <div class = "row main-header">
                <div class = "col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 test1">
                        <header>
                            <h1> Files : {{$project->title}}</h1> 
                        </header>
                </div>
                <div class = "col-xs-12 col-sm-12 col-md-12 col-lg-8 col-xl-8  p-4 flex-wrap options">
                
                @can('downloadAll', [\App\Models\File::class, $project])
                <form action="{{ route('download_all_files') }}" method="get" enctype="multipart/form-data" id="downloadForm" class="d-flex fff align-items-center justify-content-center flex-column">
                    @csrf()
                    <input name="id" type="number" value="{{$project->id}}" hidden>
                    <button type="submit" class="btn dl"><i class="bi bi-download"></i> Download all</button>
                </form>
                @endcan
                @can('deleteAll', [\App\Models\File::class, $project])
                <form action="{{ route('delete_all_files') }}" method="post" enctype="multipart/form-data" id="deleteForm" class="d-flex fff align-items-center justify-content-center flex-column">
                    @csrf()
                    @method('delete')
                    <input name="id" type="number" value="{{$project->id}}" hidden>
                    <button type = "submit" class ="btn ra"> <i class="bi bi-trash-fill"></i> Remove all</button>
                </form>
                @endcan
                @can('upload', [\App\Models\File::class, $project])
                <form action="{{ route('upload_file') }}" method="post" enctype="multipart/form-data" id="uploadForm" class="d-flex fff align-items-center justify-content-center flex-column">
                    @csrf()
                    <input type="file" name="file" id="file" style="display: none;">
                    <input name="id" type="number" value="{{$project->id}}" hidden>
                    <button type="button" class=" btn up" id="uploadButton"> <i class="bi bi-file-earmark-arrow-up-fill"></i> Upload file</button>
                </form>

                @endcan
                @include('partials.sort')
                </div>
            </div>
            </div>

        

        <div class = "col-12">
            <div class ="row files-container">
            @forelse($files as $file)
    <div class="col-12 file p-4" data-name="{{ $file->getRealName() }}" data-date="{{ $file->getCreationData() }}" data-size="{{ $file->getFormattedSizeAttribute() }}">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3 file-title d-flex justify-content-center align-items-center">
            @switch($file->getmimetype())
                    @case('application/pdf')
                        <i class="fa-solid fa-file-pdf" style= "font-size:2em; color:var(--error-text-color);"></i>
                        @break
                    @case('image/jpeg')
                    @case('image/png')
                    @case('image/gif')
                        <i class="fa-solid fa-file-image" style= "font-size:2em; color:var(--onprimary-bg-color);"></i>
                        @break
                    @case('text/plain')
                        <i class="fa-solid fa-file-alt" style="font-size: 2em; color: #d9c621;"></i>
                        @break
                    @default
                    <i class="fa-solid fa-file" style="font-size: 2em; color: var(--primary-text-color);"></i>
            @endswitch
                <h2 class = "text-truncate p-2" >{{ $file->getRealName() }}</h2>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3 d-flex file-size justify-content-center align-items-center">
                <span>{{$file->getFormattedSizeAttribute()}}</span>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3 d-flex justify-content-center align-items-center">
                <span>{{ \Carbon\Carbon::createFromFormat('d/m/Y H:i:s', $file->getCreationData())->format('d/m/Y') }} </span>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3 d-flex justify-content-center align-items-center p-3">
            @can('download', [\App\Models\File::class, $project])
                <a href="{{ url('/file/download_file/'.$file->id) }} "  class="btn btn-link" download>
                    <i class="bi bi-download dl2"></i>
                </a>
            @endcan
            @can('delete', [\App\Models\File::class, $project])
                <a href="{{ route('delete_file', ['file' => $file]) }}" class="btn btn-link">
                    <i class="bi bi-trash-fill rm"></i>
                </a>
            @endcan
            </div>
        </div>
    </div>
    @empty
    <div class ="col-12 text-center empty">
    <p>This project doesn't have any files.</p>
    </div>
@endforelse
            </div>
            </div>

        </div>
    </div>

@endsection