@can('view-action',[$controller_name,'delete'])
<li class="nav-item">
    <div class="nav-link">
        <i class="nav-link-icon lnr-trash"></i>
        <form action="{{'/'.$controller_name.'/'.$$model_name->id}}" method="POST">
            @method('DELETE')
            @csrf
            <button type="submit" class="text-btn">
            	Delete {{ucwords( str_ireplace('_', ' ', $model_name) )}}
        	</button>
        </form>
    </div>
</li>
@endcan