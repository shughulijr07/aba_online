@can('view-action',[$controller_name,'view'])
<li class="nav-item">
	<a class="nav-link" href="{{url('/'.$controller_name)}}">
		<i class="nav-link-icon fas fa-list-ul"></i>
		@if ($controller_name == 'projects')
		<span>List Of Clients</span>
		@else
		<span>List Of {{ucwords( str_ireplace('_', ' ', $controller_name) )}}</span>
		@endif
	</a>
</li>
@endcan