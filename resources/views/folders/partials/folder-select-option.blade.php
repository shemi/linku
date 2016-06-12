<option value="{{ $folder->id }}">
    {{ $folder->name }}
</option>

@foreach($folder->children as $cFolder)
    @include('folders.partials.folder-select-option', ['folder' => $cFolder])
@endforeach
