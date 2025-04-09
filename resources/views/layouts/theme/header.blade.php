<div class="header">
	<div class="header-content" >
		<nav class="navbar navbar-expand">
			<div class="collapse navbar-collapse justify-content-end">
				<div class="header-left">
				</div>

				<ul class="navbar-nav header-right">
				
					<li class="nav-item dropdown header-profile"  id="user-profile-dropdown">
						<a class="nav-link" href="#" role="button" data-toggle="dropdown">
							<div class="header-info" id="user-profile">
								<span class="fs-20 font-w500">
									@auth
									{{ Auth::user()->name }}
									@else
									Glow Manager
									@endauth
								</span>
								<small>
									@auth
									{{ Auth::user()->role == 'estilista' || Auth::user()->role == 'recepcionista' ? 'Empleado' : '' }}
									@endauth
								</small>
							</div>
						</a>
						<div class="dropdown-menu dropdown-menu-right" style="top:85px" id="user-profile-menu">
							<a href="{{ route('profile.edit') }}" class="dropdown-item ai-icon" style="color:#3375B6 !important">
								<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
								<span class="ml-2">Mi Perfil</span>
							</a>
							<a href="{{ route('logout') }}" 
							onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
							class="dropdown-item ai-icon">
								<svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
								<span class="ml-2">Cerrar Sesión </span>
							<form id="logout-form" action="{{ route('logout') }}" method="POST">
								@csrf
							</form>
							<a>
						</div>
					</li>
				</ul>
			</div>
		</nav>
	</div>
</div>