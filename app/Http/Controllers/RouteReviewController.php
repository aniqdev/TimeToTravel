<?php

namespace App\Http\Controllers;

use App\Models\RouteReview;
use Illuminate\Http\Request;

class RouteReviewController extends Controller
{
    public function activate(Request $request)
    {
        $review = RouteReview::where('id', (int)$request->review_id)->first();

        $route = $review->route;

        $review->update([
            'approved' => $request->get('active'),
        ]);

        $avg = (float)RouteReview::where('route_id', $route->id)->where('approved', 1)->get('mark')->avg('mark');

        $route->update(['rating' => $avg]);

        return [
            'status' => 'ok',
            'review_id' => $request->review_id,
        ];
    }

    public function delete(Request $request)
    {
        $review = RouteReview::where('id', (int)$request->review_id)->first();

        $route = $review->route;

        $review->delete();

        $avg = (float)RouteReview::where('route_id', $route->id)->where('approved', 1)->get('mark')->avg('mark');

        $route->update(['rating' => $avg]);

        return [
            'status' => 'ok',
            'review_id' => $request->review_id,
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('routeReviews.index', [
            'route_reviews' => RouteReview::with('route', 'author')->orderBy('id', 'DESC')->paginate(20),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RouteReview  $routeReview
     * @return \Illuminate\Http\Response
     */
    public function show(RouteReview $routeReview)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RouteReview  $routeReview
     * @return \Illuminate\Http\Response
     */
    public function edit(RouteReview $routeReview)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RouteReview  $routeReview
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RouteReview $routeReview)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RouteReview  $routeReview
     * @return \Illuminate\Http\Response
     */
    public function destroy(RouteReview $routeReview)
    {
        //
    }
}
