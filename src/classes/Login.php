<?php
require_once 'conBdd.php';

class Login {
    protected $conBdd;

    function __construct($conBdd){
        $this->conBdd = $conBdd;
    }

    public function testPass(){
        $e = new MessageDigestPasswordEncoder();
        //var_dump($e->isPasswordValid("d7kHL+mNXQ/aLnEJMYxaTrqgVp/UNXEZapetPwWZ3vtv9GykZC8AxsIc86MEFdLsxWESVxhRqtNPxgCdSq8rKw==", "XXX", "lr0sukcrpo0o0oco4g4scsc8cgok0wo"));
    }
}
?>