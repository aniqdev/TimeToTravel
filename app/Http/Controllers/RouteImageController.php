<?php

namespace App\Http\Controllers;

use App\Models\RouteImage;
use Illuminate\Http\Request;
use Response;
use Illuminate\Support\Facades\Storage;

class RouteImageController extends Controller
{
	public function routeImagesThumb($image)
	{
		if (!is_dir(base_path('storage/app/public/route-images-previews'))) {
			mkdir(base_path('storage/app/public/route-images-previews'));
		}
		
		$src = public_path('/storage/route-images/'.$image);

		$dist = public_path('/storage/route-images-previews/'.$image);

		makeImagePreview($src, $dist, 295, 200);

		//get content of image
		$content = Storage::get('route-images-previews/'.$image);
		//get mime type of image
		$mime = Storage::mimeType('route-images-previews/'.$image);
		//prepare response with image content and response code
		$response = Response::make($content, 200);
		//set header 
		$response->header("Content-Type", $mime);

		return $response;
	}

	public function sightImagesThumb($image)
	{
		if (!is_dir(base_path('storage/app/public/sight-images-previews'))) {
			mkdir(base_path('storage/app/public/sight-images-previews'));
		}

		$src = public_path('/storage/sight-images/'.$image);

		$dist = public_path('/storage/sight-images-previews/'.$image);

		makeImagePreview($src, $dist, 295, 200);

		//get content of image
		$content = Storage::get('sight-images-previews/'.$image);
		//get mime type of image
		$mime = Storage::mimeType('sight-images-previews/'.$image);
		//prepare response with image content and response code
		$response = Response::make($content, 200);
		//set header 
		$response->header("Content-Type", $mime);

		return $response;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		//
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
	 * @param  \App\Models\RouteImage  $routeImage
	 * @return \Illuminate\Http\Response
	 */
	public function show(RouteImage $routeImage)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Models\RouteImage  $routeImage
	 * @return \Illuminate\Http\Response
	 */
	public function edit(RouteImage $routeImage)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Models\RouteImage  $routeImage
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, RouteImage $routeImage)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\RouteImage  $routeImage
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(RouteImage $routeImage)
	{
		//
	}
}
