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

                 <li class="{{ Request::routeIs('distributed-hubs') || Request::routeIs('hub-view') ? 'mm-active' : '' }}">
                     <a href="{{route('distributed-hubs')}}" class="{{ Request::routeIs('distributed-hubs') || Request::routeIs('hub-view') ? 'active' : '' }}">
                         <img src="{{ asset('public/assets/images/icons/Distributed-icon.svg') }}" alt="">
                         <span>Distributed Hubs</span>
                     </a>
                 </li>
                 <li class="{{ Request::routeIs('customer-managements') ? 'mm-active' : '' }}">
                     <a href="{{route('customer-managements')}}" class="{{ Request::routeIs('customer-managements') ? 'active' : '' }}">
                         <img src="{{ asset('public/assets/images/icons/Customer-icon.svg') }}" alt="">
                         <span>Customer Management</span>
                     </a>
                 </li>

                 <li class="{{ Request::routeIs('users') || Request::routeIs('roles') ? 'mm-active' : '' }}">
                     <a href=" {{route('users')}}" class="{{ Request::routeIs('users') || Request::routeIs('roles') ? 'active' : '' }}">
                         <img src="{{ asset('public/assets/images/icons/Management-icon.svg') }}" alt="">
                         <span>User Management</span>
                     </a>
                 </li>

                 <li class="{{ Request::routeIs('products', 'individual') ? 'mm-active' : '' }}">
                     <a href="{{route('products', 'individual')}}" class="{{ Request::routeIs('products', 'individual') ? 'active' : '' }}">
                         <img src="{{ asset('public/assets/images/icons/Inventory-icon.svg') }}" alt="">
                         <span>Inventory Management</span>
                     </a>
                 </li>

                 <li class="{{ Request::routeIs('notifications') ||  Request::routeIs('create-notification') ||  Request::routeIs('edit-notification') ? 'mm-active' : '' }}">
                     <a href="{{route('notifications')}}" class="{{ Request::routeIs('notifications') ||  Request::routeIs('create-notification') ||  Request::routeIs('edit-notification') ? 'active' : '' }}"> <img src="{{ asset('public/assets/images/icons/Notification-icon.svg') }}" alt="">
                         <span>Notification Settings</span>
                     </a>
                 </li>

                 <li>
                     <a href="#">
                         <img src="{{ asset('public/assets/images/icons/Transaction-icon.svg') }}" alt="">
                         <span>Transaction Management</span>
                     </a>
                 </li>

                 <li>
                     <a href="#">
                         <img src="{{ asset('public/assets/images/icons/Wallet-icon.svg') }}" alt="">
                         <span>Wallet Management</span>
                     </a>
                 </li>

                 <li class="{{ Request::routeIs('complain-queries') ? 'mm-active' : '' }}">
                     <a href="{{route('complain-queries')}}" class="{{ Request::routeIs('complain-queries') ? 'active' : '' }}">
                         <img src="{{ asset('public/assets/images/icons/Complains-icon.svg') }}" alt="">
                         <span>Complains & Queries</span>
                     </a>
                 </li>

                 <li>
                     <a href="#">
                         <img src="{{ asset('public/assets/images/icons/Refund-icon.svg') }}" alt="">
                         <span>Refund Management</span>
                     </a>
                 </li>

             </ul>
         </div>
         <!-- Sidebar -->
     </div>
 </div>
 <!-- Left Sidebar End -->
