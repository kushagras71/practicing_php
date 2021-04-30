<?php
try{
   include __DIR__.'/../includes/DatabaseConnection.php';
   include __DIR__.'/../includes/DatabaseFunctions.php';

    $sql = 'SELECT `joke`.`id`, `joketext` ,`name`,`email`
            FROM `joke` INNER JOIN `author`
            ON `authorid` = `author`.`id`';

    $jokes = allJokes($pdo);

    $title = 'Joke List';

    $totalJokes = totalJokes($pdo);

    ob_start();

    include __DIR__.'/../templates/jokes.html.php';

    $output = ob_get_clean();

} 
catch (PDOException $e) {
    $title = 'An error has occured';
    $output = 'Database error: '.$e->getMessage().' in '.
                $e->getFile().' : '.$e->getLine();

}

include __DIR__.'/../templates/layout.html.php';