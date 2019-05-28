<?php

class Users {

    /**
     * Haalt het ID op van de gebruiker met de naam
     * @param string $name is de naam die je wilt omzetten naar de id.
     * @return int het ID van de gebruiker
     */
    public static function nameToId($name) {
        return DB::queryFirstField("SELECT id FROM users WHERE username=%s ", $name);
    }

    /**
     * haalt alle informatie op van de gebruiker doormiddel van id
     * @param int @id is het id van de gebruiker waar je de informatie van wilt ophalen.
     * @return array alle informatie van de gebruiker
     */
    public static function getCurrentUser($id){
        return DB::query("SELECT * FROM users WHERE id=%s", $id);
    }
}