<!-- need to remove -->
<li class="nav-item">
    <a href="{{ route('home') }}" class="nav-link {{ Request::is('home') ? 'active' : null }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Home</p>
    </a>
</li>

@role('user')
<li class="nav-item has-treeview">
        <a href="#" class="nav-link {{ Request::is('vault-submission') ? 'active' : null }}">
            <i class="nav-icon fa fa-shopping-bag {{ Request::is('vault-submission') ? 'menu-open' : '' }}"></i>
            <p>Vault Submission<i class="right fas fa-angle-left"></i></p>
        </a>
       <ul class="nav nav-treeview" style="display:{{ Request::is('vault-submission') ? 'block' : 'none' }};">
          <li class="nav-item">
            <a href="{{ route('vault.submission') }}" class="nav-link {{ Request::is('vault-submission') ? 'active' : null }}">
              <i class="far fa-circle nav-icon"></i>
              <p>Create New Order</p>
            </a>
          </li>
     </ul>
</li>
<li class="nav-item">
    <a class="nav-link {{ Request::is('order-list') ? 'active' : null }}" href="{{ url('/order-list') }}" >
        <i class="nav-icon fas fa-truck-moving"></i>
        <p>Order</p>
    </a>
</li>
@endrole

@role('admin')
<li class="nav-item">
    <a class="nav-link {{ Request::is('users', 'users/' . Auth::user()->id, 'users/' . Auth::user()->id . '/edit') ? 'active' : null }}" href="{{ url('/users') }}" >
        <i class="nav-icon fas fa-users"></i>
        <p>Users</p>
    </a>
</li>
<?php /*
<li class="nav-item">
    <a class="nav-link {{ Request::is('cards-inventory', 'add-card') ? 'active' : null }}" href="{{ url('/cards-inventory') }}" >
        <i class="nav-icon fas fa-truck-moving"></i>
        <p>Inventory</p>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link {{ Request::is('cardhedger-list', 'add-new-card') ? 'active' : null }}" href="{{ url('/cardhedger-list') }}" >
        <i class="nav-icon fas fa-credit-card"></i>
        <p>Card Hedger</p>
    </a>
</li>*/ ?>

<li class="nav-item">
    <a class="nav-link {{ Request::is('order-list') ? 'active' : null }}" href="{{ url('/order-list') }}" >
        <i class="nav-icon fas fa-truck-moving"></i>
        <p>Bulk Submissions</p>
    </a>
</li>
@endrole

@role('subadmin')
<?php /*<li class="nav-item">
    <a class="nav-link {{ Request::is('cards-inventory', 'add-card') ? 'active' : null }}" href="{{ url('/cards-inventory') }}" >
        <i class="nav-icon fas fa-truck-moving"></i>
        <p>Inventory</p>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link {{ Request::is('cardhedger-list', 'add-new-card') ? 'active' : null }}" href="{{ url('/cardhedger-list') }}" >
        <i class="nav-icon fas fa-credit-card"></i>
        <p>Card Hedger</p>
    </a>
</li>*/ ?>
@endrole
