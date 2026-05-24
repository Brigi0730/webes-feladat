<?php

header("Content-Type: application/json; charset=utf-8");

$conn = mysqli_connect("localhost", "root", "", "nevnapok");

if(!$conn){
    echo json_encode([
        "hiba" => "Adatbázis kapcsolat sikertelen."
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

mysqli_set_charset($conn, "utf8");

function honapNev($ho){
    $honapok = [
        1 => "január",
        2 => "február",
        3 => "március",
        4 => "április",
        5 => "május",
        6 => "június",
        7 => "július",
        8 => "augusztus",
        9 => "szeptember",
        10 => "október",
        11 => "november",
        12 => "december"
    ];

    return $honapok[(int)$ho];
}

function jsonValasz($sor){
    echo json_encode([
        "datum" => honapNev($sor["ho"]) . " " . $sor["nap"] . ".",
        "nevnap1" => $sor["nev1"],
        "nevnap2" => $sor["nev2"]
    ], JSON_UNESCAPED_UNICODE);
}

if(isset($_GET["nap"])){

    $datum = $_GET["nap"];
    $reszek = explode("-", $datum);

    if(count($reszek) != 2){
        echo json_encode([
            "hiba" => "Hibás dátumformátum. Példa: 4-30"
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }

    $ho = (int)$reszek[0];
    $nap = (int)$reszek[1];

    $sql = "SELECT * FROM nevnapok 
            WHERE ho = $ho AND nap = $nap 
            LIMIT 1";

    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0){
        $sor = mysqli_fetch_assoc($result);
        jsonValasz($sor);
    }
    else{
        echo json_encode([
            "hiba" => "nincs találat"
        ], JSON_UNESCAPED_UNICODE);
    }

}
else if(isset($_GET["nev"])){

    $nev = mysqli_real_escape_string($conn, $_GET["nev"]);

    $sql = "SELECT * FROM nevnapok 
            WHERE nev1 = '$nev' OR nev2 = '$nev' 
            LIMIT 1";

    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0){
        $sor = mysqli_fetch_assoc($result);
        jsonValasz($sor);
    }
    else{
        echo json_encode([
            "hiba" => "nincs találat"
        ], JSON_UNESCAPED_UNICODE);
    }

}
else{

    echo json_encode([
        "minta1" => "/?nap=12-31",
        "minta2" => "/?nev=Szilveszter"
    ], JSON_UNESCAPED_UNICODE);

}

?>