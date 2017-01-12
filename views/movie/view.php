<?php

$this->params['breadcrumbs'] = [
	['label' => $movie->genre, 'url' => $movie->genre],
	['label' => $movie->title]
];
$this->title = $movie->title;
?>

<?= $movie->title ?>