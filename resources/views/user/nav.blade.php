<ul class="account-nav">
    <li><a href="{{ route('user.index') }}" class="menu-link menu-link_us-s {{ request()->routeIs('user.index') ? 'menu-link_active' : '' }}">Dashboard</a></li>
    <li><a href="{{ route('user.orders') }}" class="menu-link menu-link_us-s {{ request()->routeIs('user.orders') ? 'menu-link_active' : '' }}">Orders</a></li>
    <li><a href="{{ route('user.address') }}" class="menu-link menu-link_us-s {{ request()->routeIs('user.address') ? 'menu-link_active' : '' }}">Addresses</a></li>
    <li><a href="{{ route('user.details') }}" class="menu-link menu-link_us-s {{ request()->routeIs('user.details') ? 'menu-link_active' : '' }}">Account Details</a></li>
    <li><a href="{{ route('user.wishlist') }}" class="menu-link menu-link_us-s {{ request()->routeIs('user.wishlist') ? 'menu-link_active' : '' }}">Wishlist</a></li>
    <li>
        <form method="POST" action="{{ route('logout') }}" id="logout-form">
            @csrf
            <a href="{{ route('logout') }}" class=""
                onclick="event.preventDefault();document.getElementById('logout-form').submit();"> Logout
            </a>
        </form>
    </li>
</ul>
