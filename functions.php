<?php

require_once('./api.php');

session_start();

function draw_player()
{
    $card = draw();
    array_push($_SESSION['player'], $card);
    $_SESSION['player_score'] = calculate_score($_SESSION['player']);
    $_SESSION['player_bust'] = is_bust($_SESSION['player_score']);
}

function draw_dealer()
{
    $card = draw();
    array_push($_SESSION['dealer'], $card);
    $_SESSION['dealer_score'] = calculate_score($_SESSION['dealer']);
    $_SESSION['dealer_bust'] = is_bust($_SESSION['dealer_score']);
}

function draw()
{
    $resp = DeckOfCards::Draw($_SESSION['deck_id'], 1);
    $_SESSION['remaining'] = $resp->remaining;
    $card_code = $resp->cards[0]->code;
    return $card_code;
}

function card_value(string $card)
{
    switch ($card[0]) {
        case 'A':
            return 11;
        case '2':
            return 2;
        case '3':
            return 3;
        case '4':
            return 4;
        case '5':
            return 5;
        case '6':
            return 6;
        case '7':
            return 7;
        case '8':
            return 8;
        case '9':
            return 9;
        case '0':
            return 10;
        case 'J':
            return 10;
        case 'Q':
            return 10;
        case 'K':
            return 10;
        default:
            throw new UnexpectedValueException();
            break;
    }
}

function card_is_ace(string $card)
{
    return $card[0] == 'A';
}

function calculate_score(array $cards)
{
    $score = 0;
    $aces = 0;
    foreach ($cards as $card) {
        $score += card_value($card);
        $aces += card_is_ace($card);
    }
    while ($score > 21 && $aces > 0) {
        $score -= 10;
        $aces -= 1;
    }
    return $score;
}

function is_bust($score)
{
    return $score > 21;
}

function board_reset()
{
    $_SESSION['dealer'] = array();
    $_SESSION['player'] = array();

    $_SESSION['dealer_score'] = 0;
    $_SESSION['player_score'] = 0;

    $_SESSION['dealer_bust'] = false;
    $_SESSION['player_bust'] = false;

    $_SESSION['winner'] = 'none';
}

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'next' && $_SESSION['remaining'] < 200)
        $_GET['action'] = 'reset';

    if ($_GET['action'] == 'new') {

        $resp = DeckOfCards::Deck("new", true, 6);

        $_SESSION['deck_id'] = $resp->deck_id;
        $_SESSION['remaining'] = $resp->remaining;

        board_reset();

        $_SESSION['stage'] = 'start';
    }
    if ($_GET['action'] == 'next' && $_SESSION['stage'] == 'end') {

        board_reset();

        $_SESSION['stage'] = 'start';
    }
    if ($_GET['action'] == 'reset') {

        $resp = DeckOfCards::Shuffle($_SESSION['deck_id']);

        $_SESSION['remaining'] = $resp->remaining;

        board_reset();

        $_SESSION['stage'] = 'start';
    }
    // ---

    if ($_SESSION['stage'] == 'start') {
        draw_dealer();
        draw_dealer();
        draw_player();
        draw_player();
        $_SESSION['stage'] = 'player';
    } else if ($_SESSION['stage'] == 'player') {
        switch ($_GET['action']) {
            case 'hit':
                draw_player();
                if ($_SESSION['player_bust'])
                    $_SESSION['stage'] = 'dealer';
                break;
            case 'stand':
                $_SESSION['stage'] = 'dealer';
                break;
            default:
                break;
        }
    }
}

if ($_SESSION['stage'] == 'dealer') {
    while ($_SESSION['dealer_score'] < 17)
        draw_dealer();
    $_SESSION['stage'] = 'end';
}

if ($_SESSION['stage'] == 'end') {
    if ($_SESSION['player_bust'] && $_SESSION['dealer_bust']) {
        $_SESSION['winner'] = 'draw';
    } else if ($_SESSION['player_bust']) {
        $_SESSION['winner'] = 'dealer';
    } else if ($_SESSION['dealer_bust']) {
        $_SESSION['winner'] = 'player';
    } else {
        if ($_SESSION['player_score'] > $_SESSION['dealer_score']) {
            $_SESSION['winner'] = 'player';
        } else if ($_SESSION['dealer_score'] > $_SESSION['player_score']) {
            $_SESSION['winner'] = 'dealer';
        } else {
            $_SESSION['winner'] = 'draw';
        }
    }
}

header('location: ./');
