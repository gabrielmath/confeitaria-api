# Teste de Backend: Confeitaria API

Este foi um teste realizado a partir de um processo seletivo. O teste consiste na construção de um pequeno backend
(servindo como API) para cadastro de bolos, lista de interessados e disparo de emails em massa (através das queues).

## Enunciado

1. CRUD de rotas de API para o cadastro de bolos;
    - _Os bolos deverão ter nome, peso (em gramas), valor, quantidade disponivel e uma lista de espera de emails
      de interessados_;
2. Após o cadastro de emails, caso haja bolo disponível, o sistema deverá enviar um email para os interessados avisando
   sobre a disponibilidade do mesmo.
    - **Casos a avaliar:** _Pode ocorrer de 50.000 clientes se cadastrarem e o processo de envio de emails não deve ser
      um impediditivo._

### Tecnologia Exigida

- Banco de Dados: livre escolha;
- Framework: Laravel;
- Queues e Resources serão um diferencial.

## Ambiente Local Utilizado

No banco de dados resolvi utilizar o **MySQL** e para o processamento das filas, **Redis**. Ainda a respeito das filas,
como envolve disparo de emails, aproveitei do **Mailhog** já configurado no LaravelSail. Usei também o **NGINX** como
servidor HTTP e utilizei o **PHP em sua versão 8.1**. Como esperado, usei o **Docker a partir do LaravelSail**,
que já trás um ambiente robusto e completamente configurado.
Rodei isso no **WSL2 com Ubuntu** e o docker instalado nele, nativamente.

## Instalação

Após clonar o projeto em seu aparelho, basta rodar o comando do composer para instalar as
dependências:

```bash
composer install
```

OBS: caso não tenha o composer em sua máquina, recomendo utilizar seu [container
docker](https://hub.docker.com/_/composer) para instalar as dependências do projeto.

Em seguida, faça uma cópia do arquivo `.env.example` para `.env` e configure seu ambiente de desenvolvimento como
banco de dados e etc.

E lembre-se também de gerar uma chave para aplicação:

```bash
sail artisan key:generate
```

Finalmente, para rodar a aplicação:

```bash
sail up -d
```

## Resumo da construção da aplicação/API

### Banco de Dados

No banco de dados, criei apenas 2 tabelas: _cakes_ e _waiting_lists_. Ambas mantém gravado o que foi solicitado no
enunciado. Por precaução, criei um CRUD para a lista de interessados (tabela `waiting_lists`) onde, obrigatoriamente,
deverá ter algum bolo
cadastrado para servir de base para essa tabela (nela contém uma chave estrangeira `cake_id`).

Após criar sua base de dados local e configura-la em seu `.env`, execute o comando:

```bash
sail artisan migrate --seed
```

### Filas (Queues)

No passo anterior, sugiro rodar os seeders por conta das Queues. Crio alguns bolos e vinculo em torno de 2.000
interessados,
adicionando-os nas filas.

Logo em seguida, caso queira testar os disparos das queues:

1. Accesse seu servidor de email;
    - Caso não tenha nenhuma configuração disponível, recomendo o uso do `Mailhog` que já vem setado por padrão no
      LaravelSail. Basta subir os containers (como mencionado anteriormente) e acessar a url
      [`http://localhost:8025`](http://localhost:8025) (ou mude a porta de acesso caso tenha outra configuração).
2. Com o terminal aberto e dentro do diretório do projeto, basta usar o comando:

```bash
sail artisan queue:work
```

Se o projeto estiver configurado corretamente e não tiver conflito com portas, a fila será executada.

### Laravel Horizon

Se quiser uma interface visual para acompanhar a execução das filas, poderá instalar o horizon. Na verdade ele já está
instalado.

1. Instale o Horizon:

```bash
sail artisan horizon:install
```

2. Publique seus arquivos de configuração:

```bash
sail artisan horizon:publish
```

3. Acesse a rota ``/horizon``;
4. Execute as queues a partir do horizon:

```bash
sail artisan horizon
```

Verá na tela a execução das filas e um conjunto de relatórios e métricas a respeito das mesmas.

### Observações sobre as (Queues)

Apesar de ter implementado, não tenho experiência com elas. Um exemplo disso é que, para nao sobrecarregar a fila
`CakeAvailableAllJob`, fiz uma tentativa de incluir disparos individuais de email, onde a fila que recebe o lote
(mencionada anteriormente) cria uma outra fila `CakeAvailableClientJob` e, nessa última, a notificação
de email é disparada individualmente.

Infelizmente não consegui fazer funciona-la como queria, mas deixei o código de exemplo comentado lá.
Então acabei sobrecarregando a fila principal realizando o disparo a partir de um loop.

Pela falta de experiência no trabalho com as filas, também não incluí testes automatizados nela.

### Detalhes do Projeto

1. Tratamento dos dados com FormRequest;
2. Exibição dos dados em Json através das Resources;
3. Versionamento da API com criação e configuração de novo arquivo de rotas (`routes/api/v1.php`);
4. Criação de Observers, tanto para os Bolos (`CakeObserver`), quanto para a lista de espera (`WaitingListObserver`);
    - `CakeObserver`: Após a atualização do bolo, caso a quantidade disponível seja maior que zero e tenha uma lista de
      espera vinculada, incluo esse bolo na fila para disparo de sua lista.
    - `WaitingListObserver`: Após o cadastro de um novo interessado, o bolo em questão é incluso na fila de disparo de
      sua lista.
5. Criação de um Middleware (`CheckCakeWaitingList`) para não deixar que dados da lista de espera vinculados a um bolo
   diferente seja modificado de alguma forma. Ele é usado nas rotas de `show`, `update` e `delete`;
6. CRUDs construídos usando a metodologia do TDD.
    - Para rodar os testes:

```bash
sail test tests/Feature/Http/Controllers/Api/V1/CakeControllerTest.php
sail test tests/Feature/Http/Controllers/Api/V1/WaitingListControllerTest.php
```

Os separei de acordo com seu namespace porque facilita a leitura e também é mais fácil de identificar qual classe
está sendo testada.

7. Endpoints/rotas da API:

```php
  GET|HEAD        api/v1/cakes ...................................... api.v1.cakes.index › Api\V1\CakeController@index
  POST            api/v1/cakes ...................................... api.v1.cakes.store › Api\V1\CakeController@store
  GET|HEAD        api/v1/cakes/{cake} ............................... api.v1.cakes.show › Api\V1\CakeController@show
  PUT|PATCH       api/v1/cakes/{cake} ............................... api.v1.cakes.update › Api\V1\CakeController@update
  DELETE          api/v1/cakes/{cake} ............................... api.v1.cakes.destroy › Api\V1\CakeController@destroy
  GET|HEAD        api/v1/cakes/{cake}/waitingLists .................. api.v1.cakes.waitingLists.index › Api\V1\WaitingListController@index
  POST            api/v1/cakes/{cake}/waitingLists .................. api.v1.cakes.waitingLists.store › Api\V1\WaitingListController@store
  GET|HEAD        api/v1/cakes/{cake}/waitingLists/{waitingList} .... api.v1.cakes.waitingLists.show › Api\V1\WaitingListController@show
  PUT|PATCH       api/v1/cakes/{cake}/waitingLists/{waitingList} .... api.v1.cakes.waitingLists.update › Api\V1\WaitingListController@update
  DELETE          api/v1/cakes/{cake}/waitingLists/{waitingList} .... api.v1.cakes.waitingLists.destroy › Api\V1\WaitingListController@destroy
```

## Considerações Finais

Este foi um teste que me desafiou bastante em relação as queues. Espero ter conseguido passar um pouco da ideia que tive
pra solucionar o problema e como me organizei, como estruturei o código para deixá-lo melhor manutenível,
com fácil compreensão e rápida leitura.

### Obrigado!
