@can('view-action',[$controller_name,'add'])
<li class="nav-item">
	<a class="nav-link" href="{{url('/'.$controller_name.'/create')}}">
		<i class="nav-link-icon fas fa-plus"></i>
		@if ($controller_name == 'projects')
		<span>New Client</span>
		@else
		<span>New {{ucwords( str_ireplace('_', ' ', $model_name) )}}</span>
		@endif
	</a>
</li> 

@endcan