<?php
include("includes/functions.php");
connectDatabase();
if(empty($_GET['options']) || empty($_GET['order']))
{
  $_GET['options'] = 'name';
  $_GET['order'] = 'ASC';
}
$data = getAllGames($_GET['options'], $_GET['order']);

$gameName = $starttime = $gameleader = $players = $date = "";
$starttimeErr = $gameleaderErr = $playersErr = $dateErr = "";
$valid =false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["plannedGame"])) {
        $gameNameErr = " * Verplicht";
    } else {
        $gameName = test_input($_POST["plannedGame"]);
        if (!preg_match("/^[a-zA-Z ]*$/",$gameName)) {
        $gameNameErr = " Alleen letters en spaties toegestaan";
        }
    }
    
    if (empty($_POST["date"])) {
        $dateErr = " * Verplicht";
    } else {
        $date = test_input($_POST["date"]);
        createDate($_POST["date"]);
    }

    if (empty($_POST["starttime"])) {
        $starttimeErr = " * Verplicht";
    } else {
        $starttime = test_input($_POST["starttime"]);
    }

    if (empty($_POST["gameleader"])) {
        $gameleaderErr = " * Verplicht";
    } else {
        $gameleader = test_input($_POST["gameleader"]);
        if (!preg_match("/^[a-zA-Z ]*$/",$gameleader)) {
        $gameleaderErr = " Alleen letters en spaties toegestaan";
        }else{
            createGameleader($_POST["gameleader"]);
        }
    }

    if (empty($_POST["players"])) {
        $playersErr = " * Verplicht";
    } else {
        $players = test_input($_POST["players"]);
        if (!preg_match("/^[a-zA-Z , ]*$/",$players)) {
        $playersErr = " Alleen letters en spaties toegestaan";
        }
    }
    if (!empty($_POST["starttime"]) && !empty($_POST["gameleader"]) && !empty($_POST["players"]) && !empty($_POST["date"])){
        $valid = true;
    }

    if($valid){
        $count = createPlanning($date, $starttime, $gameleader, $players, $gameName);
    }
    
}

include("includes/header.php");
?>
<h2>Maak je planning</h2> <br>
<form  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>?<?= $_SERVER["QUERY_STRING"]?>" method="post">

<div class="form-group row center">

    <label class="col-sm-2 col-form-label">Kies een spel</label>
    <div class="col-sm-10">
    <select name="plannedGame">
    <?php foreach($data as $game){?>
        <option value="<?php echo htmlentities($game["name"])?>"><?php echo htmlentities($game["name"])?></option>
    <?php } ?>
    </select>
    </div>
  </div>

  <div class="form-group row">
    <label class="col-sm-2 col-form-label">Vul de datum in:</label>
    <div class="col-sm-10">
    <input type="date" name="date"><span class="text-danger"><?php echo $dateErr; echo $count;?></span>
    </div>
  </div>

  <div class="form-group row">
    <label class="col-sm-2 col-form-label">Wat is de starttijd?</label>
    <div class="col-sm-10">
    <input type="time" name="starttime"><span class="text-danger"><?php echo $starttimeErr;?></span><br>
    </div>
  </div>

  <div class="form-group row">
    <label class="col-sm-2 col-form-label">Wie legt het spel uit?</label>
    <div class="col-sm-10">
    <input type="text" name="gameleader"><span class="text-danger"><?php echo $gameleaderErr;?></span>
    </div>
  </div>

  <div class="form-group row">
    <label class="col-sm-2 col-form-label">Wie zijn de spelers?</label>
    <div class="col-sm-10">
    <input type="text" name="players"><span class="text-danger"><?php echo $playersErr;?></span>
    </div>
  </div>

  <input class = "mb-3 btn btn-danger" type="submit" value = "Inplannen">
</form>




<?php
include("includes/footer.php");
?>
</div>
</body>