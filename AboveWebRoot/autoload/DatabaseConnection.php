<?php

class DatabaseConnection {

    static function connect() {
        return new DB\SQL(
            'mysql:host=localhost;port=3306;dbname=GalleryData',
            'sedin110_s2250677',
            'cklodonnell2001!'
        );
    }

}
