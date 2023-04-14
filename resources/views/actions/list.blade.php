@can('view-action',[$controller_name,'view'])
<li class="nav-item">
	<a class="nav-link" href="{{url('/'.$controller_name)}}">
		<i class="nav-link-icon fas fa-list-ul"></i>
		<span>List Of {{ucwords( str_ireplace('_', ' ', $controller_name) )}}</span>
	</a>
</li>
@endcan