<div class="panel panel-default">
    <div class="panel-heading clearfix" role="tab" id="heading-{{ $folder->id }}">
        <h4 class="panel-title pull-left">
            <a role="button"
               data-toggle="collapse"
               data-parent="#accordion-{{ $folder->parent_id ?: '0' }}"
               href="#folder-{{ $folder->id }}"
               aria-expanded="true"
               aria-controls="collapseOne"
            >
                <i class="fa fa-btn fa-folder-o" aria-hidden="true"></i>{{ $folder->name }}
            </a>
        </h4>
        <div class="actions pull-right">
            <div class="dropdown">
                <button id="dLabel" type="button" class="btn btn-xs btn-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                    <li>
                        <a href="{{ route('folder.edit', ['folder' => $folder->id]) }}">
                            <i class="fa fa-btn fa-pencil" aria-hidden="true"></i>Edit
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('share/' . $folder->id) }}">
                            <i class="fa fa-btn fa-share" aria-hidden="true"></i>Share
                        </a>
                    </li>
                    <li role="separator" class="divider"></li>
                    <li>
                        <a href="#"
                           data-action="{{ route('folder.destroy', ['folder' => $folder->id]) }}"
                           data-name="{{ $folder->name }}"
                           data-type="folder"
                           data-toggle="modal"
                           data-target="#deleteModal"
                           class="text-danger">
                            <i class="fa fa-btn fa-trash" aria-hidden="true"></i>Delete
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div id="folder-{{ $folder->id }}"
         class="panel-collapse collapse"
         role="tabpanel"
         aria-labelledby="heading-{{ $folder->id }}"
    >
        <div class="panel-body">
            @foreach($folder->links as $link)
                @include('link.partials.link-panel', ['link' => $link])
            @endforeach
            @if($folder->children)
                <div class="panel-group" id="accordion-{{ $folder->id }}" role="tablist" aria-multiselectable="true">
                    @foreach($folder->children as $folder)
                        @include('folders.partials.folder-panel', ['folder' => $folder])
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>