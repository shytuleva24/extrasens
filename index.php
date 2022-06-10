<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
    <main>
        <form action="index.php" method="POST">
            <h1>Гра екстрасенс</h1>
            <h2>Спробуй свої можливості</h2>
            <p class="text">Вгадай число яке загадав комп'ютер! Для цього тобі потрібно лише обрати число з випадаючого списку.</p>
            <?php 
                session_start();

                $newGame = 0;
                if (isset($_POST['new'])) {
                    header("refresh: 0;");
                    session_destroy();
                }

                if (isset($_SESSION["disabledNumbers"])){
                    $disabledNumbers = json_decode($_SESSION["disabledNumbers"]);
                } else {
                    $disabledNumbers = [];
                }
               
                if (isset($_POST['password'])) {
                    $password = $_POST['password'];
                } else {
                    $password = rand(1, 10);
                }  
                // echo "$password<br>";   
                if (isset($_POST['number'])) {
                    $number = $_POST['number'];
                    if ($password == $number) {
                        echo ("<h1>ПЕРЕМОГА!</h1>");
                        $newGame++;
                         if (count($disabledNumbers) == 2) {
                            echo ("<p>З тебе вийде такий собі екстрасенс!</p>");
                        } else {
                            echo ("<p>З тебе вийде чудовий екстрасенс!</p>");
                        }
                        echo "<p style='display: none;'>";
                    } else if ($password != $number && count($disabledNumbers) < 3) {
                        echo ("<h1>Не вгадав!</h1>");
                        echo "<p>";
                        array_push($disabledNumbers, $number);
                        if (count($disabledNumbers) == 2) {
                            echo ("<h1>Залишилась ще одна спроба!</h1>");
                            echo "<p>";
                        } else if (count($disabledNumbers) == 3) {
                            echo ("<h1>З тебе не вийде екстрасенсу!</h1>");
                            echo "<p style='display: none;'>";
                            $newGame++;
                        }
                    }
                }

                $numbers = [];
                    for ($i = 1; $i <=10; $i++) {
                        $numbers[] .= $i;
                    }
                ?>
                <select name="number">   
                    <?php foreach ($numbers as $value): ?> 
                    <option value="<?= $value ?>" 
                    <?php 
                        if (count($disabledNumbers) != 0) {
                            if (in_array($value, $disabledNumbers)) {
                                echo 'disabled';
                            } else if (count($disabledNumbers) == 3 || $password == $number) {
                                echo 'disabled';
                                $newGame++;
                            }
                        } else if ((isset($_POST['number']) == $password) && count($disabledNumbers) == 0) {
                            echo 'disabled';
                            $newGame++;
                        }
                    ?>> 
                    <?= $value?> 
                    </option>  
                    <?php 
                        endforeach;
                        $_SESSION["disabledNumbers"] = json_encode($disabledNumbers);
                    ?> 
                </select>
            <input class="btn b" type="submit" name="sub" value="Спробувати"></p>
            <input type="hidden" name="password" value="<?php echo($password) ?>"><br>
            <hr>
            <p <?php 
            if ($newGame == 0) {
                echo ("style='display: none;'");
            }
            ?> >
            <button class="btn t" type="submit" name="new">Нова гра!</button>
            </p>
        </form>
    </main>
</body>
</html>