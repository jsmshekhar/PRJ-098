<?php
$hub_part_request = DB::table('hub_part_accessories')
    ->leftJoin('hubs', 'hubs.hub_id', '=', 'hub_part_accessories.hub_id')
    ->select(
        'hub_part_accessories.slug',
        'hub_part_accessories.accessories_title',
        'hub_part_accessories.requested_qty',
        'hub_part_accessories.status_id',
        'hub_part_accessories.assigned_qty',
        'hubs.hubid',
        'hubs.city',
        'hubs.slug as hubSlug',
        DB::raw('CASE 
            WHEN hub_part_accessories.accessories_category_id = 1 THEN "Helmet" 
            WHEN hub_part_accessories.accessories_category_id = 2 THEN "T-Shirt" 
            WHEN hub_part_accessories.accessories_category_id = 3 THEN "Mobile Holder"  
        END as accessories')
    );
if (Auth::user()->role_id == 0) {
    $hub_part_request = $hub_part_request->where('hub_part_accessories.status_id', 1);
} else {
    $hub_part_request = $hub_part_request->whereIn('hub_part_accessories.status_id', [3, 4]);
}
$hub_part_request = $hub_part_request->orderBy('hub_part_accessories.created_at', 'DESC')
    ->take(10)
    ->get();

$count = DB::table('hub_part_accessories');
if (Auth::user()->role_id == 0) {
    $count = $count->where('hub_part_accessories.status_id', 1);
} else {
    $count = $count->whereIn('hub_part_accessories.status_id', [3, 4]);
}
$count = $count->get();
$count = count($count);
?>
@can('raise_request', $permission)
<button type="button" class="btn header-item noti-icon position-relative" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <img src="{{ asset('public/assets/images/icons/bell-icon.svg') }}" alt="">
    <span class="badge bg-danger rounded-pill">{{$count}}</span>
</button>
@endcan
<div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0 admin_profilt_pop" aria-labelledby="page-header-notifications-dropdown">
    <div data-simplebar style="max-height: 230px;">
        <!-- <a href="#!" class="text-reset notification-item"> -->
        @if(isset($hub_part_request) && count($hub_part_request)>0)
        @foreach($hub_part_request as $data)
        <div class="d-flex">
            <div class="flex-grow-1 p-3 border-bottom">
                @if(Auth::user()->role_id == 0)
                <p class="notify_text">Hello , you get a Request for Accessories from Hub Id: <b>{{$data->hubid}} ({{$data->city}}).</b></p>
                <div class="notify_btn_box">
                    <form id="delete-form-{{$data->slug}}" method="post" action="{{ route('reject-hub-part-accessories', $data->slug) }}" style="display: none;">
                        @csrf
                        {{ method_field('POST') }} <!-- delete query -->
                    </form>
                    <a href="" class="btn btn-outline-danger waves-effect waves-light" onclick="
                        if (confirm('Are you sure, You want to reject?'))
                        {
                            event.preventDefault();
                            document.getElementById('delete-form-{{$data->slug}}').submit();
                        }else {
                            event.preventDefault();
                        }
                        " title="reject"> Reject
                    </a>
                    <a href="{{route('hub-part-accessories')}}" class="btn btn-success waves-effect waves-light">Open</a>

                </div>
                @else
                <p class="notify_text">@if($data->status_id == 3)Hello , your Request for Accessories: <b>{{$data->accessories_title}}</b> with a QTY of <b> ({{$data->assigned_qty}}) </b> has been Assigned. @elseif($data->status_id == 4)Hello , your Request for Accessories: <b>{{$data->accessories_title}}</b> with a QTY of <b> ({{$data->requested_qty}}) </b>has been Rejected. @endif</p>
                <div class="notify_btn_box">
                    <a href="{{route('hub-view',['slug' => $data->hubSlug, 'param' => 'accessories'])}}" class="btn btn-success waves-effect waves-light">View</a>
                </div>
                @endif
            </div>
        </div>
        @endforeach
        @endif
        <!-- </a> -->
    </div>
</div>