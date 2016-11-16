<?php 

use Framework\Core\HTTP\Controller as Controller;
use Framework\Core\HTTP\Route as Route;
use Framework\Core\HTTP\Request as Request;

Class ormController extends Controller {

	function index() {
		// debugArray(Artist::find(1)->getJson());
		// debugArray(Artist::all()->getJson());
		// $artistWhere = Artist::where('id>=', '1');
		// debugArray($test = $artistWhere->get());
		// debugArray($artistWhere->first()->getJson());
		// debugArray($artistWhere->count());
		// debugArray($artistWhere->max('id')->getJson());
		// echo Listener::where('id>=', '4')->delete();

		// $albums = Album::find(1);
		// echo $albums = Album::all();

		// debugArray($albums);


		// debugArray($albums);
		// debugArray(end($albums));
		// debugArray(key($albums));

		// echo is_object(end($albums));

		// foreach ($albums as $album) {
		// 	debugArray($album);
		// }
		// $listeners = Listener::all();
		// $artists = Artist::all();

		// foreach ($artists as $artist) {
		// 	debugArray($artist->albums);
		// }
	}
}