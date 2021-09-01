<?php 


//não existe método get acessível aqui na classe
//mas como foi realizado require_once, dentro do RouterCore
//na classe constructor, ela, consegue ser acessada.

$this->get('/', 'PagesController@home');

$this->get('/cep', 'PagesController@cep');

$this->get('/quem-somos', 'PagesController@quemSomos');

$this->get('/contato', 'PagesController@contato');

$this->get('/pesquisa', 'PesquisaController@pesquisar');



