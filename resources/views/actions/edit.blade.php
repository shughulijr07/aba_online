@can('view-action',[$controller_name,'edit'])
<li class="nav-item">
	<a class="nav-link" href="{{url('/'.$controller_name.'/'.$$model_name->id.'/edit')}}">
		<i class="nav-link-icon lnr-pencil"></i>
		@if ($controller_name == 'projects')
		<span>Edit Client</span>
		@else
		<span>Edit {{ucwords( str_ireplace('_', ' ', $model_name) )}}</span>
		@endif
	</a>
</li>
@endcan