<?php
/**
 * De Template class is een extensie op RainTPL.
 * @example $template->tplError($text) Een andere template error dan normaal.
 */
class Template extends RainTPL {

    /**
     * Maak een eigen error template.
     * @param type $text Wat is de error?
     */
    public function tplError($text = '') {
        exit('<center><font face="verdana" size="2"><p>Multiversum - Template Error!</b><hr>'.$text.'</font></center>');
    }
}
?>