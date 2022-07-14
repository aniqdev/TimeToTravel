<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use App\Models\{City, Route, RouteReview};

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
	$this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Artisan::command('load:cities', function () {

	$arr = csvToArr(storage_path('cities_ru.csv'), [ 'delimetr' => ',', 'del_first' => false ]);
	// print_r($arr);
	// return;
	$users = $this->withProgressBar($arr, function ($item) {
		// echo $item . '|'.$item[6] . PHP_EOL;
		// return;
		if (trim($item[6])) {
			$city = $item[6];
		}elseif (trim($item[2])) {
			$city = $item[2];
		}
		City::create([
			'city' => $city,
			'country' => 'Россия',
			'latitude' => $item[17],
			'longitude' => $item[18],
		]);
	});
	echo PHP_EOL;
});


Artisan::command('test', function () {

	$width = 640;

	$height = 480;

	if ($width / $height < 295 / 200) { // будет выше 200
		echo "выше";
		$new_width = 295;
		$new_height = $height / ($width / 295);
	}else{ // будет шире 295
		echo 'шире';
		$new_width = $width / ($height / 200);
		$new_height = 200;
	}

	return;
	$src = public_path('/storage/sight-images/Vgg00eV1gBnR9E0F8OveGKsD4mMRZ06wGnsZw3W0.jpg');

	$dist = public_path('/storage/sight-images-thumbs-200/Vgg00eV1gBnR9E0F8OveGKsD4mMRZ06wGnsZw3W0.jpg');

	// $imagine = new Imagine\Gmagick\Imagine();
	$imagine = new Imagine\Gd\Imagine();

	// $imagine->open($src)
	// 	    ->thumbnail($size, $mode)
	// 	    ->save($dist);

	return;
	resizeImage($src, $dist, 250);


	return;
	$route = Route::find(27);

	print_r($route->markers->toArray());
});


Artisan::command('test-gdq', function () {
	$imagine = new Imagine\Gmagick\Imagine();
});

Artisan::command('test-gdw', function () {
	$imagine = new Imagine\Gd\Imagine();
});

Artisan::command('test-gde', function () {
	$imagine = new Imagine\Imagick\Imagine();
});

Artisan::command('delrev', function () {
	$res = RouteReview::where('route_id', '<', 61)->delete();
	print_r($res);
});

Artisan::command('line-points', function () {
	$routes = Route::all();
	foreach ($routes as $route) {
		echo PHP_EOL;
		echo $route->name;
		$line_points = [];
        foreach ($route->points as $sight) {
            $line_points[] = [ $sight->longitude, $sight->latitude ];
        }
        $route->line_points = json_encode($line_points);
        $route->save();
        print_r($line_points);
	}
});






//===================================================================================
// возвращает двумерный массив с первыми CSV файла
function csvToArr($file_path='', $options = []){

		$config = [
			'delimetr' => ';',
			'encoding' => 'utf-8',
			'max_str' => false,
			'del_first' => false,
			'output' => []
		];
		$c = array_merge ( $config, $options );
		$fh = fopen($file_path,'r') or die($php_errormsg);
		$res = []; $i = 0;
		
		while (!feof($fh)) {

				$str = fgetcsv($fh, 0, $c['delimetr']);

				$i++;
				if($c['del_first'] && $i === 1) continue;

				if(strtolower($c['encoding']) != 'utf-8' && $str) 
						foreach ($str as &$cell) 
								$cell = iconv($c['encoding'], 'UTF-8', $cell);


				$str2 = [];
				if($str)
				foreach ($c['output'] as $okey => $oval)
						$str2[$oval] = $str[$okey];

				if($str2) $res[] = $str2;
				elseif($str) $res[] = $str; 
				
				if($c['max_str'] && $i > $c['max_str']) break;

		}

		fclose($fh) or die($php_errormsg);

		return $res;
}
