<?php
$currentOption = isset($_GET['per_page']) ? $_GET['per_page'] : '';
// $perPage = ['1' => 1, '2' => 2, '10' => 10];
$perPage = ['25' => 25, '50' => 50, '100' => 100];
?>
<select class="form-control perPage" name="choices-single-no-sorting" id="perPageDropdown" onchange="perPage(this);">
    @foreach ($perPage as $key => $value)
        @php $selected = false; @endphp
        @if ($currentOption == $value)
            @php $selected = true; @endphp
        @endif
        <option @php echo $selected ? 'selected' : ""; @endphp value="{{ $key }}">{{ $value }}</option>
    @endforeach
</select>
