@extends('layouts.main')

@section('page-title', 'Cities')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="/">Home</a></li>
    <li class="breadcrumb-item active">Cities</li>
</ol>
@endsection

@section('content')
<style>
.city-check {
    appearance: none;
    background-color: #fff;
    margin: 0;
    font: inherit;
    color: currentColor;
    width: 22px;
    height: 22px;
    border: 1px solid #999;
    transform: translateY(-0.075em);
    border-radius: 3px;
    display: grid;
    place-content: center;
    cursor: pointer;
}

.city-check::before {
    content: "";
    width: 16px;
    height: 16px;
    transform: scale(0); 
    transition: 120ms transform ease-in-out;
    box-shadow: inset 1em 1em #17a2b8;
}

.city-check:checked::before {
  transform: scale(1);
}
</style>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Cities</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
            <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
            <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped projects">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>City</th>
                    <th>Country</th>
                    <th>active</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cities as $city)
                <tr>
                    <td>
                        {{ $city->id }}
                    </td>
                    <td>
                        <a>{{ $city->city }}</a>
                    </td>
                    <td>
                        <a>{{ $city->country }}</a>
                    </td>
                    <td class="project-actions text-right">
                        <input class="city-check" type="checkbox" {{ $city->active ? 'checked' : '' }} onchange="cityCheck(this)" data-cityid="{{ $city->id }}">
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
function cityCheck(input) {
    $.post('/api/cities/activate', { 
        city_id: input.dataset.cityid,
        active: input.checked ? 1 : 0,
        _token: "{{ csrf_token() }}" 
    }, function(data) {
        if (data.status && data.status === 'ok') {
            alert('success', 'Saved');
        }else{
            alert('danger', 'Error');
        }
    })
}
</script>
@endsection