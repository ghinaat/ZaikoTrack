<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            @if (Route::currentRouteName() === 'users.profile')
            <div>
                <h5 class="mb-0">Profile</h5>
            </div>
            @elseif (Route::currentRouteName() === 'user.changePassword' )
            <div>
                <h5 class="mb-0">Change Password</h5>
            </div>
            @endif

            <ul class="nav nav-pills" style="background-color: transparent; border-bottom: none;">
                @if (Route::currentRouteName() === 'users.profile')
                    <li class="nav-item">
                        <a class="nav-link {{ Route::currentRouteName() === 'users.profile' ? 'active' : '' }}" href="{{ route('users.profile') }}" >Profile</a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('users.profile') }}">Profile</a>
                    </li>
                @endif

                @if (Route::currentRouteName() === 'user.changePassword' )
                    <li class="nav-item">
                        <a class="nav-link {{ Route::currentRouteName() === 'user.changePassword' ? 'active' : '' }}" href="{{ route('user.changePassword') }}" style="background-color: transparent; border-bottom: none;box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">Change Password</a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.changePassword') }}">Change Password</a>
                    </li>
                @endif     
            </ul>
        </div>
    </div>
</div>