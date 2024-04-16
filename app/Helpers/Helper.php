<?php
namespace App\Helpers;

function redirect($url){

	header("Location: $url");
	exit();
}
