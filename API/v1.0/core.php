<?php
    final class API {
        private $server = '';
        private $request = '';

        public function __construct( $server, $request ) {
            // headers
            header( 'Access-Control-Allow-Origin: *' );
            header( 'Access-Control-Allow-Headers: access-control-allow-origin, content-type' );
            header( 'Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT' );
            // set origin
            $this->server = $server;
            $this->request = $request;
        }

        private function config_api() {
            require_once( './api_config.php' );
            $newAPI = new configAPI();
            return $newAPI->set_settings();
        }

        private function prepare_response( $data, $code ) {
            $array['data'] = $data;
            $array['code'] = $code;
            return json_encode( $array );
        }
        public function runAPI() {
            echo( $this->config_api() );
            //echo( file_get_contents('../CustomSettings/apiEndpointsConfig.json'));
            //var_dump( json_decode('../CustomSettings/apiEndpointsConfig.json') );
            //var_dump( json_decode(file_get_contents('../CustomSettings/apiEndpointsConfig.json'), true));
            //var_dump( $var );
        }

        /* This function should always return json file */
        /*public function runAPI() {
            $this->check_client_list()
            return $this->prepare_response( [$this->server, $this->request], 404 );
        }*/
    }
?>
