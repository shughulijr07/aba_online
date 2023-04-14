@can('view-action',[$controller_name,'add'])
<li class="nav-item">
	<a class="nav-link" href="{{url('/'.$controller_name.'/create')}}">
		<i class="nav-link-icon fas fa-plus"></i>
		<span>New {{ucwords( str_ireplace('_', ' ', $model_name) )}}</span>
	</a>
</li>
@endcan