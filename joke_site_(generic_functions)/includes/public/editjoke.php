<?php
include __DIR__.'/../includes/DatabaseConnection.php';
include __DIR__.'/../includes/DatabaseFunction.php';

try{
    if(isset($_POST['joketext'])) {
        update($pdo,'joke','id',['id' => $_POST['jokeid'],
                   'joketext' => $_POST['joketext'],
                   'authorId' => 1]) ;
    header('location: jokes.php');                
    } else {
        if (isset($_GET['id'])){
            $joke = findById($pdo,'joke','id',$_GET['id']);
        }
        $title = 'Edit joke';
        ob_start();
        include __DIR__.'/../templates/editjoke.html.php';
        $output = og_get_clean();
        }
        
}

catch (PDOException $e){
    $title  = 'An error has occured';
    $output = 'Databse error: '.$e->getMessage().' in '.
                $e->getFile()." : ".getLine();
}

include __DIR__.'/../templates/layout.html.php';