<?php

class DatabaseConnection {

    static function connect() {
        return new DB\SQL(
            'mysql:host=localhost;port=3306;dbname=sedin110_GalleryData',
            'sedin110_user2',
            'pEDK9!GK,ZCQ'
        );
    }

}
