<?php
if (isset($_POST['joketext'])) {
    try {
        
        include __DIR__.'/../includes/DatabaseConnection.php';
        include __DIR__.'/../includes/DatabaseFunctions.php';

        insert($pdo,'joke',['authorId' => 1,
                        'jokeText' => $_POST['joketext'],
                        'jokedate' =>new DateTime()]);

        header('location: jokes.php');
    }

    catch (PDOException $e) {
        $title = 'An error has occured';

        $output = 'Database error: '.$e->getMessage() . 
                    ' in '.$e->getFile().' : '.$e->getLine();

    }

} else {
    $joke  =findById($pdo, 'joke', 'id',$_GET['id']);
    
    $title = 'Edit joke';
    ob_start();

    include __DIR__.'/../templates/addjoke.html.php';

    $output = ob_get_clean();
}

