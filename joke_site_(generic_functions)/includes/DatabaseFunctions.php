<?php

function query($pdo, $sql, $parameters=[]){
    $query = $pdo->prepare($sql);
    $query->execute($parameters);
    return $query;
}

function processDates($fields) {
    foreach ($fields as $key => $value) {
        if ($value instanceof DateTime) {
            $fields [$key] = $value->format('Y-m-d');
        }
    }
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

//  function insertJoke($pdo,$joketext,$authorid) {
//     $query = 'INSERT INTO `joke` (`joketext`,`jokedate`,`authorid`)
//                 VALUES (:joketext, CURDATE(), :authorId)';
//     $parameters = [':joketext' => $joketext,
//                     ':authorid' => $authorId];
//     query($pdo,$query,$parameters);
//  }

function insertJoke($pdo, $fields) {
    $query = 'INSERT INTO `joke` (';

    foreach ($fields as $key => $value) {
        $query .= '`' . $key . '`,';
    }

    $query = rtrim($query, ',');

    $query .= ') VALUES (';

    foreach ($fields as $key => $value) {
        $query .= ':' . $key . ',';
    }

    $query = rtrim($query, ',');
    $query .= ')';

    query($pdo, $query);
}

//  function updateJoke($pdo, $jokeId, $joketext,$authorId){
//     $parameters = [':joketext'=>$joketext,
//                     ':authorId' => $authorId,
//                     ':id'=>$jokeId];
//     query($pdo, 'UPDATE `joke` SET `authorId` = `:authorId`,
//                 `joketext` = :joketext WHERE `id` = :id', $parameters);
//  }
// }

function updateJoke($pdo, $fields){
    $query = 'UPDATE `joke` SET ';

    foreach ($fields as $key => $value) {
        $query .= '`'.$key.'` = :'.$key.',';
    }
    $query = rtrim($query,',');
    $query .= 'WHERE `id` = :primaryKey';

    $fields = processDates($fields);

    $fields['primaryKey'] = $fields['id'];

    query($pdo,$query,$fields);
}                


 function deleteJoke($pdo, $id){
     $parameter = [':id' => $id];

     query($pdo, 'DELETE FROM `joke` WHERE `id` =:id', $parameters);
 }

 function allJokes($pdo) {
     $jokes = query($pdo, 'SELECT `joke`.`id` ,`joketext`, `jokedate`,
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

     foreach ($fields as $key => $value){
         $query .= ':'.$key.',';
     }

     $query = rtrim($query,',');
     $query .= ')';
     $fields = processDates($fields);
     query($pdo,$query,$fields);
 }

// ---------------------------------------------
 ######### Creating Generic Functions ##########
 // --------------------------------------------

 function findAll($pdo,$table) {
     $result = query($pdo, 'SELECT * FROM `'.$table.'`');
     return $result->fetchAll();

 }


 function delete($pdo,$table,$primaryKey,$id) {
    $parameters = [':id'=>$id];
    query($pdo, 'DELETE FROM `'.$table.'`
               WHERE `'.$primaryKey.'` = :id', $parameters);
}

function insert($pdo, $table, $fields) {
    $query = 'INSERT INTO `' . $table . '` (';

    foreach ($fields as $key => $value) {
        $query .= '`' . $key . '`,';
    }

    $query = rtrim($query, ',');

    $query .= ') VALUES (';

    foreach ($fields as $key => $value) {
        $query .= ':' . $key . ',';
    }

    $query = rtrim($query, ',');

    $query .= ')';

    $fields = processDates($fields);

    query($pdo, $query, $fields);
}

function update($pdo, $table, $primaryKey, $fields) {

    $query = ' UPDATE `' . $table .'` SET ';

    foreach ($fields as $key => $value) {
        $query .= '`' . $key . '` = :' . $key . ',';
    }

    $query = rtrim($query, ',');

    $query .= ' WHERE `' . $primaryKey . '` = :primaryKey';

    // Set the :primaryKey variable
    $fields['primaryKey'] = $fields['id'];

    $fields = processDates($fields);

    query($pdo, $query, $fields);

}

function findById($pdo,$table,$primaryKey,$value) {
    $query = 'SELECT * FROM `'.$table.'` WHERE `'.$primaryKey.'` = :value';
    $parameters = [
        'value' => $value
    ];
    $query = query($pdo, $query, $parameters);
    return $query->fetch();
}

function total($pdo, $table) {
    $query = query($pdo, 'SELECT COUNT(*) 
    FROM `' . $table . '`');
    $row = $query->fetch();
    return $row[0];
}

function save($pdo, $table, $primaryKey, $record) {
    try{
        if ($record[$primaryKey] == '') {
            $record[$primaryKey] = null;
        }
        insert($pdo, $table, $record);
    }
    catch (PDOException $e) {
        update($pdo, $table, $primaryKey, $record);
    }
}