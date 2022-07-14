@extends('layouts.main')

@section('page-title')
Отзывы
@endsection

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="/">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('routes.index') }}">Home</a></li>
    <li class="breadcrumb-item active">Отзывы</li>
</ol>
@endsection

@section('content')
<style>
.card{
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.stars{
    color: #ff8100;
}
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
{{ $route_reviews->links() }}
<div class="row">
    @foreach($route_reviews as $review)
        <div class="col-sm-4 mb-3 js-route-review">
            <div class="post card p-2" style="height: 100%;">
                <div class="user-block">
                    <img class="img-circle img-bordered-sm" src="/images/user-icon.png" alt="user image">
                    <span class="username d-flex">
                        <a href="{{ route('users.showAutor', $review->author) }}" class="col-6 eclips">{{ $review->author->name }}</a>
                        <a href="{{ route('routes.edit', $review->route) }}" class="ml-auto col-6 eclips text-right">{{ $review->route->name }}</a>
                    </span>
                    <span class="description d-flex">
                        {{ $review->created_at->format('d-m-Y H:i') }}
                        <span class="stars ml-auto">
                            @for($i = 0; $i < $review->mark; $i++)
                                <i class="fas fa-star"></i>
                            @endfor
                        </span>
                    </span>
                </div>
                <p>{{ $review->text }}</p>
                <div class="buttons d-flex">
                    <label class="btn btn-default btn-sm d-inline-flex align-baseline mb-0">
                        <span>approve</span>
                        <input class="city-check ml-2" type="checkbox" {{ $review->approved ? 'checked' : '' }} onchange="routeReviewCheck(this, {{ $review->id }})">
                    </label>
                    <button class="btn btn-danger btn-sm ml-auto" onclick="routeReviewDelete(this, {{ $review->id }})">delete</button>
                </div>
            </div>
        </div>
    @endforeach
</div>
{{ $route_reviews->links() }}

<script>
function routeReviewCheck(input, review_id) {
    $.post('/api/RouteReview/activate', { 
        review_id: review_id,
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
function routeReviewDelete(button, review_id) {
    if(!confirm('Удалять?')) return false
    $.post('/api/RouteReview/delete', { 
        review_id: review_id,
        _token: "{{ csrf_token() }}" 
    }, function(data) {
        if (data.status && data.status === 'ok') {
            alert('success', 'Saved');
            button.closest('.js-route-review').remove()
        }else{
            alert('danger', 'Error');
        }
    })
}
</script>
@endsection