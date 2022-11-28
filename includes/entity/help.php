<?php
include_once "../connection.php";
include_once "user.php";

    $question = $_POST["question"];
    $date = date("Y/m/d");

    if(isset($_POST["question"])){
        $sql = "INSERT INTO help (username, question, `date`) VALUES ('John', '$question', '$date')";
        echo "Your question is being processed, we will get back to you as soon as possible";
    } else {
        echo "Operation pending, fill in the text box if you did not.";
    }
?>