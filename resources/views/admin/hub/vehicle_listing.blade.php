<div class="table-rep-plugin">
    <div class="table-responsive mb-0" data-pattern="priority-columns">
        <table id="tech-companies-1" class="table">
            <thead>
                <tr>
                    <th>EV NUMBER</th>
                    <th>EV TYPE</th>
                    <th>EV CATEGORY</th>
                    <th>PROFILE CATEGORY</th>
                    <th>DEVICE</th>
                    <th>PAYMENT STATUS</th>
                    <th>STATUS</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vehicles as $key => $vehicle)
                <tr>
                    <td>{{$vehicle->ev_number}}</td>
                    <td>{{$vehicle->ev_type_name}}</td>
                    <td>{{$vehicle->ev_category_name}}</td>
                    <td>{{$vehicle->profile_category_name}}</td>
                    <td>454646464</td>
                    <td>Paid</td>
                    {{--<td>
                        @if($vehicle->status_id == 1)
                        <label class="text-success">Active</label>
                        @elseif($vehicle->status_id == 2)
                        <label class="text-danger">Inactive</label>
                        @endif
                    </td>--}}
                    <td>
                        <div class="d-flex flex-wrap gap-2">
                            <input type="checkbox" id="switch3{{$key}}" onclick="toggleStatus('switch3{{$key}}', 'vehicle')" switch="bool" {{ $vehicle->status_id == 1 ? 'checked' : '' }} value=" {{$vehicle->slug}}">
                            <label for="switch3{{$key}}" data-on-label="Active" data-off-label="Inactive"></label>
                        </div>
                    </td>
                    <td>
                        <div class="dropdown">
                            <a href="#" class="btn btn-link p-0 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical"></i>
                            </a>

                            <div class="dropdown-menu">
                                <a class="dropdown-item EvVehicleModelForm" data-toggle="modal" data-product_Id="{{ $vehicle->product_Id }}" data-slug="{{ $vehicle->slug }}" data-hub_id="{{ $vehicle->hub_id }}" data-product_category_id="{{ $vehicle->product_category_id }}" data-ev_number="{{ $vehicle->ev_number }}" data-ev_type_id="{{ $vehicle->ev_type_id }}" data-ev_category="{{ $vehicle->ev_category }}" data-profile_category="{{ $vehicle->profile_category }}" title="Edit Vehicle" style="cursor: pointer;margin-right: 5px;"></i><i class="fa fa-edit"></i> Edit
                                </a>
                                <form id="delete-form-{{$vehicle->slug}}" method="post" action="{{ route('product-delete', $vehicle->slug) }}" style="display: none;">
                                    @csrf
                                    {{ method_field('POST') }} <!-- delete query -->
                                </form>
                                <a href="" class="dropdown-item" onclick="
                                if (confirm('Are you sure, You want to delete?'))
                                {
                                    event.preventDefault();
                                    document.getElementById('delete-form-{{$vehicle->slug}}').submit();
                                }else {
                                    event.preventDefault();
                                }
                                " title="delete">
                                    <i class="fa fa-trash" style="color:#d74b4b;"></i> Delete
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $vehicles->withQueryString()->links('pagination::bootstrap-4') }}
</div>