<?php 

namespace Procrastinateur\Sae51\Controllers;

use Procrastinateur\Sae51\Middleware\AuthMiddleware;
use Procrastinateur\Sae51\Utils\View;

class EthernetController {

    function index(){
        AuthMiddleware::handle();



        View::render('ethernet');
    }

}

?>

