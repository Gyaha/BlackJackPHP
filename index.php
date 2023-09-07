<?php
require_once("./api.php");

session_start();

if (!isset($_SESSION['deck_id'])) {
    header('location: ./function.php?action=new');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Blackjack</title>
</head>

<body>
    <div class="container">
        <div class="menu">
            <a href="./functions.php?action=reset">Reset</a>
            <a href="./functions.php?action=new">New</a>
        </div>
        <div class="dealer hand">
            <?php if ($_SESSION['stage'] == 'end') { ?>
                <?php foreach ($_SESSION['dealer'] as $card) : ?>
                    <img src="<?php echo DeckOfCards::Img($card) ?>" alt="">
                <?php endforeach ?>
            <?php } else { ?>
                <img src="<?php echo DeckOfCards::Img($_SESSION['dealer'][0]) ?>" alt="">
                <img src="<?php echo DeckOfCards::ImgBack() ?>" alt="">
            <?php } ?>
        </div>
        <div class="winner">
            <?php if ($_SESSION['winner'] == 'player') { ?>
                <p>WON</p>
            <?php } else if ($_SESSION['winner'] == 'dealer') { ?>
                <p>LOST</p>
            <?php } else if ($_SESSION['winner'] == 'draw') { ?>
                <p>DRAW</p>
            <?php } ?>
        </div>
        <div class="player hand">
            <?php foreach ($_SESSION['player'] as $card) : ?>
                <img src="<?php echo DeckOfCards::Img($card) ?>" alt="">
            <?php endforeach ?>
        </div>
        <div class="menu controls">
            <?php if ($_SESSION['stage'] == 'player') { ?>
                <a href="./functions.php?action=hit">Hit</a>
                <a href="./functions.php?action=stand">Stand</a>
            <?php } else { ?>
                <a href="./functions.php?action=next">Next</a>
            <?php } ?>
        </div>
    </div>
</body>

</html>