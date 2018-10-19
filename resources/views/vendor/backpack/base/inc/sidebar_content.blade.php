<li class="header">{{ trans('backpack::base.administration') }}</li>
<!-- ================================================ -->
<!-- ==== Recommended place for admin menu items ==== -->
<!-- ================================================ -->
<li><a href="{{ url(config('backpack.base.route_prefix', 'admin').'/dashboard') }}"><i class="fa fa-dashboard"></i> <span>{{ trans('sidebar.dashboard') }}</span></a></li>
<li><a href="{{ url(config('backpack.base.route_prefix', 'admin') . '/players') }}"><i class="fa fa-users"></i> <span>{{ trans('sidebar.players') }}</span></a></li>
<li><a href="{{ url(config('backpack.base.route_prefix', 'admin') . '/transactions') }}"><i class="fa fa-history"></i> <span>{{ trans('sidebar.transactions') }}</span></a></li>
<li><a href="{{ url(config('backpack.base.route_prefix', 'admin') . '/accounts') }}"><i class="fa fa-address-book-o"></i> <span>{{ trans('sidebar.accounts') }}</span></a></li>
<li><a href="{{ url(config('backpack.base.route_prefix', 'admin') . '/setting') }}"><i class="fa fa-cog"></i> <span>{{ trans('sidebar.settings') }}</span></a></li>
<li><a href='{{ url(config('backpack.base.route_prefix', 'admin').'/log') }}'><i class='fa fa-terminal'></i> <span>Logs</span></a></li>


<!-- ======================================= -->
<li class="header">{{ trans('backpack::base.user') }}</li>
<li><a href="{{ url(config('backpack.base.route_prefix', 'admin').'/logout') }}"><i class="fa fa-sign-out"></i> <span>{{ trans('backpack::base.logout') }}</span></a></li>