<?php 

function connectDatabase(){
    $servername = "localhost";
    $username = "root";
    $password = "mysql";
    $dbname = 'planningstool';
    $connection = null;


    try {
        $connect = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $connect;

    }
    catch(PDOException $e)
        {
        $e->getMessage();
        }
}

function getAllGames($options, $order){
    $connect = connectDatabase();
    $query = $connect->prepare("SELECT * FROM games ORDER BY $options $order");
    $query->execute();
    return $query->fetchAll();
}

function createGameleader($gameleader){
    $connect = connectDatabase();
    $query1 = $connect->prepare("SELECT * FROM gameleader WHERE name = :gameleader");
    $query1->execute(["gameleader" => $gameleader]);
    $result = $query1->fetch();
    if ($result == false){
        $query = $connect->prepare("INSERT INTO gameleader (name) VALUES (:gameleader)");
        return $query->execute(["gameleader" => $gameleader]);
    } 
    
}

function createDate($date){
    $connect = connectDatabase();
    $query1 = $connect->prepare("SELECT * FROM `date` WHERE `date` = :date");
    $query1->execute(["date" => $date]);
    $result = $query1->fetch();
    if ($result == false){
        $query = $connect->prepare("INSERT INTO `date` (`date`) VALUES (:date)");
        return $query->execute(["date" => $date]);
    } 
}

function createPlanning($date_id, $starttime, $gameleader, $players, $gameName){
    $connect = connectDatabase();
    $query1 = $connect->prepare("SELECT COUNT(date_id) FROM `planning` WHERE (SELECT date FROM date WHERE date = :date_id)");
    $query1->execute(["date_id" => $date_id]);
    $count = $query1->fetch();
    var_dump($count);
    if ($count == 10){
        return "Er zijn al 10 spellen ingepland op deze datum";
    }else{
        $query = $connect->prepare("INSERT INTO planning (date_id, starttime, gameleader_id, players, gameName_id) VALUES ((SELECT id FROM `date` WHERE `date` = :date), :starttime, (SELECT id FROM gameleader WHERE name = :gameleader LIMIT 1), :players, (SELECT id FROM games WHERE name = :gameName))");
        echo "<script>
        alert('Planning is toegevoegd.');
        window.location.href='planning.php';
        </script>";
        return $query->execute(["date" => $date_id, "starttime" => $starttime, "gameleader" => $gameleader, "players" => $players, "gameName"=> $gameName]);
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function getGame($id){
    $connect = connectDatabase();
    $data = $connect->prepare("SELECT * FROM games WHERE id = :id");
    $data->execute(['id' => $id]);
    return $data->fetch();
}

function getPlanning(){
    $connect = connectDatabase();
    $query = $connect->prepare("SELECT * FROM planning ORDER BY starttime ASC");
    $query->execute();
    return $query->fetchAll();
}


function updatePlanning($date_id, $starttime, $gameleader, $players, $gameName, $id){
    $connect = connectDatabase();
    $query = $connect->prepare("UPDATE planning SET `date_id` = (SELECT id FROM `date` WHERE `date` = :date), `starttime` = :starttime, `gameleader_id` = (SELECT id FROM gameleader WHERE name = :gameleader LIMIT 1), `players` = :players, gameName_id = (SELECT id FROM games WHERE name = :gameName) WHERE id = :id");
    return $query->execute(["date" => $date_id, 'starttime'=> $starttime, 'gameleader'=> $gameleader, 'players'=> $players, 'gameName'=> $gameName, 'id' => $id]);
}

function getDetailsPlanningUpdate($id){
    $connect = connectDatabase();
    $query = $connect->prepare("SELECT * FROM planning WHERE `id` = :id");
    $query->execute(['id'=> $id]);
    return $query->fetch();
}

function deletePlanningsItem($id){
    $connect = connectDatabase();
    $query = $connect->prepare("DELETE FROM planning WHERE id = :id");
    echo "<script>
    alert('Planning is verwijderd.');
    window.location.href='planning.php';
    </script>";
    return $query->execute(['id'=> $id]);
}

function deleteGameleader($id){
    $connect = connectDatabase();
    $query = $connect->prepare("DELETE FROM gameleader WHERE id = :id");
    echo "<script>
    alert('Spelleider is verwijderd.');
    window.location.href='gameleaders.php';
    </script>";
    return $query->execute(['id'=> $id]);
}

function getGamesFromId($gameName_id){
    $connect = connectDatabase();
    $query = $connect->prepare("SELECT * FROM games WHERE `id` = :gameName_id");
    $query->execute(['gameName_id'=> $gameName_id]);
    return $query->fetch();
}

function getGameleaderFromId($gameleader_id){
    $connect = connectDatabase();
    $query = $connect->prepare("SELECT * FROM gameleader WHERE `id` = :gameleader_id");
    $query->execute(['gameleader_id'=> $gameleader_id]);
    return $query->fetch();
}

function getDateFromId($date_id){
    $connect = connectDatabase();
    $query = $connect->prepare("SELECT * FROM date WHERE `id` = :date_id");
    $query->execute(['date_id'=> $date_id]);
    return $query->fetch();
}

function getPlanningDetails($id){
    $connect = connectDatabase();
    $query = $connect->prepare("SELECT * FROM planning WHERE `id` = :id");
    $query->execute(['id'=> $id]);
    return $query->fetch();
}

function countPlannedGames(){
    $connect = connectDatabase();
    $query = $connect->prepare("SELECT * FROM planning");
    $query->execute();
    return $query->rowCount();
}

function getGameleaders(){
    $connect = connectDatabase();
    $query = $connect->prepare("SELECT * FROM gameleader");
    $query->execute();
    return $query->fetchAll();
}

function getDetailsGameleader($name){
    $connect = connectDatabase();
    $query = $connect->prepare("SELECT * FROM planning WHERE gameleader_id = (SELECT id FROM gameleader WHERE name = :name)");
    $query->execute(['name'=> $name]);
    return $query->fetchAll();
}


?>