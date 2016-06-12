<div class="panel panel-default">
    <div class="panel-heading clearfix" role="tab" id="link-{{ $link->id }}">
        <h4 class="panel-title pull-left">
            <a role="link" target="_blank" href="{{ $link->src }}">
                <i class="fa fa-btn fa-external-link" aria-hidden="true"></i>{{ $link->name }}
            </a>
            &nbsp;&nbsp;
        </h4>
        <div class="actions pull-right">
            <div class="dropdown">
                <button id="dLabel" type="button" class="btn btn-xs btn-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                    <li>
                        <a href="{{ route('link.edit', ['link' => $link->id]) }}">
                            <i class="fa fa-btn fa-pencil" aria-hidden="true"></i>Edit
                        </a>
                    </li>
                    <li role="separator" class="divider"></li>
                    <li>
                        <a href="#"
                           data-action="{{ route('link.destroy', ['link' => $link->id]) }}"
                           data-name="{{ $link->name }}"
                           data-type="link"
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
</div>