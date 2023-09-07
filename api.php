<?php
function GetJson($www)
{
    $json_str = file_get_contents($www);
    $json = json_decode($json_str);
    return $json;
}

class DeckOfCards
{
    private static $www = "https://www.deckofcardsapi.com/api/deck/";

    //https://www.deckofcardsapi.com/api/deck/new/shuffle/?deck_count=1

    public static function Deck(string $deck_id = 'new', bool $shuffle = true, int $count = 1)
    {
        $query = self::$www . $deck_id . '/';
        if ($shuffle) {
            $query .= 'shuffle/';
        }
        $query .= '?deck_count=' . $count;
        echo $query;
        return GetJson($query);
    }

    // https://www.deckofcardsapi.com/api/deck/<<deck_id>>/draw/?count=2

    public static function Draw(string $deck_id, int $count = 1)
    {
        $query = self::$www . $deck_id . '/draw/';
        $query .= '?count=' . $count;

        return GetJson($query);
    }

    // https://www.deckofcardsapi.com/api/deck/<<deck_id>>/shuffle/?remaining=true

    public static function Shuffle(string $deck_id, bool $remaining = false)
    {
        $query = self::$www . $deck_id . '/shuffle/';
        // API does NOT check bool value, only IF the GET var is set
        if ($remaining)
            $query .= '?remaining=true';

        return GetJson($query);
    }

    public static function Img(string $card)
    {
        return 'https://deckofcardsapi.com/static/img/' . $card . '.png';
    }

    public static function ImgBack()
    {
        return 'https://www.deckofcardsapi.com/static/img/back.png';
    }

    // PILES ONLY WORKS ON 1 DECK SO IS NOT FURTHER IMPLEMENTET

    // https://www.deckofcardsapi.com/api/deck/<<deck_id>>/pile/<<pile_name>>/add/?cards=AS,2S

    /*public static function PileAdd(string $deck_id, string $pile_name, string $cards)
    {
        $query = self::$www . $deck_id . '/pile/';
        $query .= $pile_name . '/add/';
        $query .= '?cards=' . $cards;

        return GetJson($query);
    }*/

    // https://www.deckofcardsapi.com/api/deck/<<deck_id>>/pile/<<pile_name>>/list/

    /*public static function PileList(string $deck_id, string $pile_name)
    {
        $query = self::$www . $deck_id . '/pile/';
        $query .= $pile_name . '/list/';

        return GetJson($query);
    }*/
}
