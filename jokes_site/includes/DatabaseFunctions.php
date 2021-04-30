<?php

function query($pdo, $sql, $parameters=[]){
    $query = $pdo->prepare($sql);
    $query->execute($parameters);
    return $query;
}

function totalJokes($pdo) {
    $query = query($pdo, 'SELECT COUNT(*) FROM `joke`');
    $row = $query->fetch();
    return $row[0];
}

 function getJoke($pdo, $id){
    $parameters = [':id' => $id];
    $query = query ($pdo, 'SELECT * FROM `joke`
                    WHERE `id` = :id',$parameters);
    return $query->fetch(); 
 }

 function insertJoke($pdo,$joketext,$authorid) {
    $query = 'INSERT INTO `joke` (`joketext`,`jokedate`,`authorid`)
                VALUES (:joketext, CURDATE(), :authorId)';
    $parameters = [':joketext' => $joketext,
                    ':authorid' => $authorId];
    query($pdo,$query,$parameters);
 }

 function updateJoke($pdo, $jokeId, $joketext,$authorId){
    $parameters = [':joketext'=>$joketext,
                    ':authorId' => $authorId,
                    ':id'=>$jokeId];
    query($pdo, 'UPDATE `joke` SET `authorId` = `:authorId`,
                `joketext` = :joketext WHERE `id` = :id', $parameters);
 }

 function deleteJoke($pdo, $id){
     $parameter = [':id' => $id];

     query($pdo, 'DELETE FROM `joke` WHERE `id` =:id', $parameters);
 }

 function allJokes($pdo) {
     $jokes = query($pdo, 'SELECT `joke`.`id` ,`joketext`,
                    `name`,`email` FROM `joke` INNER JOIN `author`
                    ON `authorid` = `author`.`id`');

    return $jokes -> fetchAll();
 }

 function allAuthors($pdo) {
     $authors = query($pdo,'SELECT * FROM `author`');
     return $authors->fetchAll();
 }

 function deleteAuthor($pdo,$table,$id){
     $parameters = [':id'=>$id];
     query($pdo, 'DELETE FROM `author`
                WHERE `id` =: id',$parameters);
 }

 function insertAuthor($pdo, $fields){
     $query = 'INSERT INTO `author` (';

     foreach ($fields as $key => $value) {
         $query .= '`'.$key.'`';
     }
     $query = rtrim($query,',');

     $query .= ') VALUES (';

     foreach ($fields as key => $value){
         $query .= ':'.$key.',';
     }

     $query = rtrim($query,',');
     $query .= ')';
     $fields = processDates($fields);
     query($pdo,$query,$fields);
 }
