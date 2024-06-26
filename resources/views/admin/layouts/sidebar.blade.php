 <!-- ========== Left Sidebar Start ========== -->
 <div class="vertical-menu">

     <div data-simplebar>

         <!--- Sidemenu -->
         <div id="sidebar-menu">
             <!-- Left Menu Start -->
             <ul class="metismenu list-unstyled" id="side-menu">
                 <li class="{{ Request::routeIs('home') ? 'mm-active' : '' }}">
                     <a href="{{ route('home') }}" class="{{ Request::routeIs('home') ? 'active' : '' }}">
                         <img src="{{ asset('public/assets/images/icons/dashboard-icon.svg') }}" alt="">
                         <span>Dashboard</span>
                     </a>
                 </li>

                 <li class="{{ Request::routeIs('live-tracking') ? 'mm-active' : '' }}">
                     <a href="{{ route('live-tracking') }}" class="{{ Request::routeIs('live-tracking') ? 'active' : '' }}">
                         <img src="{{ asset('public/assets/images/icons/tracking-icon.svg') }}" alt="">
                         <span>Live tracking</span>
                     </a>
                 </li>
                 @can('hub_list', $permission)
                 <li class="{{ Request::routeIs('distributed-hubs') || Request::routeIs('hub-view') ? 'mm-active' : '' }}">
                     <a href="{{ route('distributed-hubs') }}" class="{{ Request::routeIs('distributed-hubs') || Request::routeIs('hub-view') ? 'active' : '' }}">
                         <img src="{{ asset('public/assets/images/icons/Distributed-icon.svg') }}" alt="">
                         <span>Distributed Hubs</span>
                     </a>
                 </li>
                 @endcan
                 <li class="{{ Request::routeIs('customer-managements') || Request::routeIs('customer-view') ? 'mm-active' : '' }}">
                     <a href="{{ route('customer-managements') }}" class="{{ Request::routeIs('customer-managements') || Request::routeIs('customer-view') ? 'active' : '' }}">
                         <img src="{{ asset('public/assets/images/icons/Customer-icon.svg') }}" alt="">
                         <span>Customer Management</span>
                     </a>
                 </li>
                 @can('view_user', $permission)
                 <li class="{{ Request::routeIs('users') || Request::routeIs('roles') ? 'mm-active' : '' }}">
                     <a href=" {{ route('users') }}" class="{{ Request::routeIs('users') || Request::routeIs('roles') ? 'active' : '' }}">
                         <img src="{{ asset('public/assets/images/icons/Management-icon.svg') }}" alt="">
                         <span>User Management</span>
                     </a>
                 </li>
                 @endcan
                 @can('view_assigned_ev', $permission)
                 <li class="{{ Request::routeIs('vehicles') ? 'mm-active' : '' }}">
                     <a href=" {{ route('vehicles') }}" class="{{ Request::routeIs('vehicles') ? 'active' : '' }}">
                         <img src="{{ asset('public/assets/images/icons/Vehicles.svg') }}" alt="">
                         <span>Vehicles</span>
                     </a>
                 </li>
                 @endcan
                 @can('view_inventry', $permission)
                 <li class="{{ Request::routeIs('products', 'corporate') || Request::routeIs('product-create') || Request::routeIs('product-edit') ? 'mm-active' : '' }}">
                     <a href="{{ route('products', 'corporate') }}" class="{{ Request::routeIs('products', 'corporate') || Request::routeIs('product-create') || Request::routeIs('product-edit') ? 'active' : '' }}">
                         <img src="{{ asset('public/assets/images/icons/Inventory-icon.svg') }}" alt="">
                         <span>Inventory Management</span>
                     </a>
                 </li>
                 @endcan
                 @can('view_notification', $permission)
                 <li class="{{ Request::routeIs('notifications') || Request::routeIs('create-notification') || Request::routeIs('edit-notification') ? 'mm-active' : '' }}">
                     <a href="{{ route('notifications') }}" class="{{ Request::routeIs('notifications') || Request::routeIs('create-notification') || Request::routeIs('edit-notification') ? 'active' : '' }}">
                         <img src="{{ asset('public/assets/images/icons/Notification-icon.svg') }}" alt="">
                         <span>Notification Settings</span>
                     </a>
                 </li>
                 @endcan
                 @can('view', $permission)
                 <li class="{{ Request::routeIs('transaction-management') ? 'mm-active' : '' }}">
                     <a href="{{ route('transaction-management') }}" class="{{ Request::routeIs('transaction-management') ? 'active' : '' }}">
                         <img src="{{ asset('public/assets/images/icons/Transaction-icon.svg') }}" alt="">
                         <span>Transaction Management</span>
                     </a>
                 </li>
                 @endcan

                 {{-- <li class="{{ Request::routeIs('wallet-management') ? 'mm-active' : '' }}">
                 <a href="{{ route('wallet-management') }}" class="{{ Request::routeIs('wallet-management') ? 'active' : '' }}">
                     <img src="{{ asset('public/assets/images/icons/Wallet-icon.svg') }}" alt="">
                     <span>Wallet Management</span>
                 </a>
                 </li> --}}
                 @can('view_complaint', $permission)
                 <li class="{{ Request::routeIs('complain-queries') ? 'mm-active' : '' }}">
                     <a href="{{ route('complain-queries') }}" class="{{ Request::routeIs('complain-queries') ? 'active' : '' }}">
                         <img src="{{ asset('public/assets/images/icons/Complains-icon.svg') }}" alt="">
                         <span>Complains & Queries</span>
                     </a>
                 </li>
                 @endcan
                 @can('view', $permission)
                 <li class="{{ Request::routeIs('refund-management') ? 'mm-active' : '' }}">
                     <a href="{{ route('refund-management') }}" class="{{ Request::routeIs('refund-management') ? 'active' : '' }}">
                         <img src="{{ asset('public/assets/images/icons/Refund-icon.svg') }}" alt="">
                         <span>Refund Management</span>
                     </a>
                 </li>
                 @endcan
                 @can('view', $permission)
                 <li class="{{ Request::routeIs('hub-part-accessories') ? 'mm-active' : '' }}">
                     <a href="{{ route('hub-part-accessories') }}" class="{{ Request::routeIs('hub-part-accessories') ? 'active' : '' }}">
                         <img src="{{ asset('public/assets/images/icons/hb.svg') }}" alt="">
                         <span>Hub Part Accessories</span>
                     </a>
                 </li>
                 @endcan
                 @can('view', $permission)
                 <li class="{{ Request::routeIs('return-exchange') ? 'mm-active' : '' }}">
                     <a href="{{ route('return-exchange') }}" class="{{ Request::routeIs('return-exchange') ? 'active' : '' }}">
                         <img src="{{ asset('public/assets/images/icons/ReturnExchange.svg') }}" alt="">
                         <span>Return Exchange Module</span>
                     </a>
                 </li>
                 @endcan
                 @can('view_order', $permission)
                 <li class="{{ Request::routeIs('order-list') ? 'mm-active' : '' }}">
                     <a href="{{ route('order-list') }}" class="{{ Request::routeIs('order-list') ? 'active' : '' }}">
                         <img src="{{ asset('public/assets/images/icons/Order.svg') }}" alt="">
                         <span>Order</span>
                     </a>
                 </li>
                 @endcan

             </ul>
         </div>
         <!-- Sidebar -->
     </div>
 </div>
 <!-- Left Sidebar End -->