<?php
class Config {

    /**
     * Een static var.
     * @var type $config is voor de lees en schrijf regels.
     */
    static $config = array();

    /**
     * Schrijf een regel.
     * @param string $k Dit is hoe het aangeroepen moet worden.
     * @param string $v En dit is wat je wilt laten zien.
     */
    public static function W($k, $v) {
        self::$config[$k] = $v;
    }

    /**
     * Lees de geschreven regel.
     * @param string $k Dit is wat er aangeroepen moet worden.
     * @return string Die laat zien wat er geschreven is.
     */
    public static function R($k) {
        return self::$config[$k];
    }
}
?>