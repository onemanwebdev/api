<?php
    class configAPI {
        private $dbConfig = [
            'server'    => "localhost",
            'username'  => "root",
            'pass'      => "",
            'dbname'    => "api",
            'charset'   => 'utf8'
        ];
        private $connection = '';
        private $configArray = false;
        private $checksum = '';

        public function __construct() {
            $this->getContent();
        }
//  - - - OK - - - //
        /* This method get loaded JSON file, then prase it into the array
         * and after serialize make hash for storedChecksum,
         * Fire another method via container to check if update is need
         */
        private function getContent() {
            if( is_file( '../CustomSettings/apiEndpointsConfig.json' ) ) {
                $config_json = file_get_contents( '../CustomSettings/apiEndpointsConfig.json' );
                $this->configArray = json_decode( $config_json, true );
                $this->checksum = hash( 'crc32', serialize( $this->configArray ) );
                $this->temporaryDatabaseFunction( 'self::checkUpdateNeed', $this->checksum );
            } else {
                print_r( '#ERROR - no file loaded' );
            }
        }

        /* This method update endpoints' settings in DB
         */
        private function updateEndpointsSetting( $connect ) {
            $configData = $this->configArray['globalSettings'];
            $updateDefaultQueryBegin = "UPDATE endpoints_default_config SET setting_value='";
            $updateDefaultQueryId[1] = isset( $configData['sortKeyword'] ) ? $configData['sortKeyword'] : null;
            $updateDefaultQueryId[2] = isset( $configData['searchKeyword'] ) ? $configData['searchKeyword'] : null;
            $updateDefaultQueryId[3] = isset( $configData['fieldSelectionKeyword'] ) ? $configData['fieldSelectionKeyword'] : null;
            $updateDefaultQueryId[4] = isset( $configData['paginationDefault']['enable'] ) ? $configData['paginationDefault']['enable'] : null;
            $updateDefaultQueryId[5] = isset( $configData['paginationDefault']['offsetKeyword'] ) ? $configData['paginationDefault']['offsetKeyword'] : null;
            $updateDefaultQueryId[6] = isset( $configData['paginationDefault']['limit'][0] ) ? $configData['paginationDefault']['limit'][0] : null;
            $updateDefaultQueryId[7] = isset( $configData['paginationDefault']['limit'][1] ) ? $configData['paginationDefault']['limit'][1] : null;
            $updateDefaultQueryEnd = "' WHERE id=";
            $controlNumber = 1;
            for ( $i = 1; $i < 8; $i++ ) {
                if ( $updateDefaultQueryId[$i] !== null ) {
                    $result = $connect->query( $updateDefaultQueryBegin . $updateDefaultQueryId[$i] . $updateDefaultQueryEnd . $i );
                }
                $controlNumber *= $result;
            }
            print_r( $controlNumber ? 'Update succesfull' : '#ERROR Durig Update' );
        }

        /* This method check if loaded json file is up-to-date or DB needs update,
         * fire update method and set id DB new fingerprint after succesful update
         */
        private function checkUpdateNeed( $connect, $checksum ) {
            $selectQuery = "SELECT checksum FROM endpoints_update_checkfield WHERE id=1";
            $result = $connect->query( $selectQuery );
            $storedChecksum = $result->fetch_assoc()['checksum'];
            if ( $storedChecksum != $checksum ) {
                $date = date('YmdHis');
                $updateQuery = "
                    UPDATE endpoints_update_checkfield
                    SET update_date='$date', checksum='$checksum'
                    WHERE id=1
                ";
                print_r( $updateResult = $connect->query( $updateQuery ) ? 'Update succesful' : '#ERROR' );
                $this->updateEndpointsSetting( $connect );
            } else {
                print_r( 'No update needed' );
            }
        }

        /* This method support DB connection as container and takes as arguments:
         * 1. callback function to operate queries on DB,
         * 2. params to pass to callback
         */
        private function temporaryDatabaseFunction( $callback, $param = null ) {
            $cfg = $this->dbConfig;

            $connect = new mysqli( $cfg['server'], $cfg['username'], $cfg['pass'], $cfg['dbname'] );
            $connect->query( "SET CHARSET " . $cfg['charset'] );

            call_user_func( $callback, $connect, $param );

            $connect->close();
        }
//  - - - OK - - - //
        public function set_settings( ) {
        }
    }
 ?>
