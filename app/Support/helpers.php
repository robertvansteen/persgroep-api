<?php


function getRandomImage($width, $height) {
	$images = json_decode(file_get_contents('database/seeds/data/unsplash.json'));
    return 'https://unsplash.it/' . $width . '/' . $height . '?image=' . $images[array_rand($images)]->id;
}
