<?php

namespace app\core;


class RouterCore{
    private $uri;
    private $method;
    private $getArr = [];


    public function __construct()
    {
        $this->initialize();
        require_once('../app/config/Router.php');
        $this->execute();
    }

    private function initialize(){

        $this->method = $_SERVER['REQUEST_METHOD'];
        //dd($_SERVER); (Retorna os dados da requisição)       
        $uri = $_SERVER['REQUEST_URI'];


        //tratamento de string para o mecanismo de busca do PesquisaController
        if(strpos($uri, '?')){
            $uri = mb_substr($uri, 0, strpos($uri, '?'));
        }


        //transformando a uri em array
        $ex = explode('/', $uri);

        $uri = $this->normalizeURI($ex);
       
        for($i = 0; $i < UNSET_URI_COUNT; $i++){
            unset($uri[$i]);
            
        }
        
        //pegando o array e transformando em link
        $this->uri = implode('/' , $this->normalizeURI($uri));        
        if(DEBUG_URI)
            dd($this->uri);
    }

    private function get($router, $call){

        $this->getArr[] = [
            'router' => $router,
            'call' => $call
        ];

    }

    private function execute(){
        switch($this->method){
            case 'GET':
                $this->executeGet();
                break;

            case 'POST':
                
                break;
        }
    }

    private function executeGet(){
        foreach($this->getArr as $get){   
            //para realizar a comparação do link, removendo a barra
            $r = substr($get['router'], 1);        
            // dd($get, false);   
            
            if(substr($r, -1) == '/'){
                $r = substr($r, 0, -1);            
            }

            if($r == $this->uri){
               if(is_callable($get['call'])){
                    $get['call']();
                break;
               }else {
                   $this->executeController($get['call']);
               }
            }
        }   
    }

    private function executeController($get){
        //vai pegar os dados do get e tratá-los 
        //como exemplo na uri /cep, chamada na router como $this->get('/cep', 'TesteController@seta');
        // o resultado será com o explode [0]teste controller [1] cep
        $ex = explode('@', $get);

        //condição para verificar se existe método@uri
        //será chamada a função message404 com a mensagem de erro
        if(!isset($ex[0]) || !isset($ex[1])){
            (new \app\controller\MessageController)->message('Dados inválidos','Controller ou método não encontrado: ' . $get, 404);
            return;
        }

        //realizando verificação se a classe existe
        $cont = 'app\\controller\\' . $ex[0];
        if(!class_exists($cont)){
            (new \app\controller\MessageController)->message('Dados inválidos','Controller não encontrado: ' . $get, 404);
            return;
        }

        //realizando verificação se o método existe
        if(!method_exists($cont,$ex[1])){
            (new \app\controller\MessageController)->message('Dados inválidos','Método não encontrado: ' . $get, 404);
            return;
        }

        //Caso alguma das condições não seja atingida, será encaminhado para o index(construtor)
        call_user_func_array([
            new $cont,
            $ex[1]
        ],[]);
    }    

    private function normalizeURI($arr){
        
        return array_values(array_filter($arr));
        
    }
}