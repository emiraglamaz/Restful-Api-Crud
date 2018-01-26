<?php
header('Content-Type: application/json');
class restful_api { 
    protected $method = ''; 
    protected $endpoint = ''; 
    protected $params = array(); 
    protected $file = null; 
    public function __construct(){ 
        $this->_input(); $this->_process_api(); 
    } 
    private function _input(){ 
         header("Access-Control-Allow-Orgin: *");
         header("Access-Control-Allow-Methods: *");
         $this->params = explode('/', trim($_SERVER['PATH_INFO'],'/'));

         $this->endpoint = array_shift($this->params); 
         // İsteğin metodu
         $method = $_SERVER['REQUEST_METHOD']; 
         $allow_method = array('GET', 'POST', 'PUT', 'DELETE'); 
         if (in_array($method, $allow_method)){ 
             $this->method = $method; 
                } 
         // Her bir yöntem türüne karşılık gelen veri
         switch ($this->method) { 
             case 'POST': $this->params = $_POST; break;
             case 'GET': // alınmasına gerek yok cunku params URL den alındı 
                        break; 
             case 'PUT': $this->file = file_get_contents("php://input"); break; 
             case 'DELETE': //Parametreler URL'den alındığından, alınması gerekiyor 
                        break; 
         default: $this->response(500, "Gecersiz Yontem"); break; }
    } 
    private function _process_api(){ 
            // islevin kodu
            if (method_exists($this, $this->endpoint)){
                 $this->{$this->endpoint}(); 
            } 
            else { 
                $this->response(500, "Bilinmeyen Son Nokta"); 
            }
    }
    protected function response($status_code, $result = NULL){ 
        header($this->_build_http_header_string($status_code)); 
        header("Content-Type: application/json"); 
        echo json_encode($result); die(); 
    } 
    private function _build_http_header_string($status_code){ 
        $status = array( 
            200 => 'OK', 
            404 => 'Bulunmuuyor', 
            405 => 'Yonteme izin verilmiyor!!!', 
            500 => 'Dahili Sunucu Hatası' ); 
        return "HTTP/1.1 " . $status_code . " " . $status[$status_code]; }
}

?>