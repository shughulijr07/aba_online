<div class="page-title-actions">                
    <button type="button" data-toggle="tooltip" title="Print" data-placement="bottom" class="btn-shadow mr-3 btn btn-info">
        <i class="lnr-printer"></i>
    </button>
    <div class="d-inline-block dropdown">
        <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-shadow dropdown-toggle btn btn-primary">
            <span class="btn-icon-wrapper pr-2 opacity-7">
                <i class="fa fa-tasks" aria-hidden="true"></i>
            </span>
            Actions
        </button>
        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
            <ul class="nav flex-column">

                @if($view_type == 'index')
                    @include('actions.add')
                @endif

                @if($view_type == 'create')
                    @include('actions.list')
                @endif

                @if($view_type == 'edit')
                    @include('actions.add')
                    @include('actions.list')
                    @include('actions.delete')
                @endif

                @if($view_type == 'show')
                    @include('actions.edit')
                    @include('actions.add')
                    @include('actions.list')
                    @include('actions.delete')
                @endif

            </ul>
        </div>
    </div>
</div>

