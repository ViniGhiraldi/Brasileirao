<?php
    header('Content-type: application/json');

    $dadosEnviados = file_get_contents('php://input');
    $dadosEnviados = json_decode($dadosEnviados, true);

    $cxPdo = new PDO('mysql:host=localhost;port=3306;dbname=campeonato_futbol','root','');

    $cmdSql = '';

    if(isset($dadosEnviados['busca'])){
        $busca = $dadosEnviados['busca'];
        $cmdSql = "SELECT jogo.id, jogo.data_hota,jogo.estadio,c1.nome as time, cj1.num_gols as gol_time,c2_nome as adversario,cj2.num_gols as 
        gol_adversario from clube as c1, clube_em_jogo as cj1,jogo,clube_em_jogo as cj2, clube as c2 where c1.nome = cj1.fk_clube and 
        cj1.fk_jogo = jogo.id and jogo.id = cj2.fk_jogo and cj2.fk_clube = c2.nome and c1.nome <> c2.nome and c1.nome = '$busca'";
    }
       
    $cxPrepare = $cxPdo->prepare($cmdSql);
    $dados = [
        'result'=>false,
        'cmdExec'=>$dadosEnviados,
        'dados' => []
    ];

    if($cxPrepare->execute()){
        if($cxPrepare->rowCount() > 0){
            $dados['result'] = true;
            $dados['dados'] = $cxPrepare->fetchAll();
        }
    }

    echo json_encode($dados);