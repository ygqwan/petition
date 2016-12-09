<?php

foreach(D('petition')->getShouldEmailPetition() as $pid => $fllowers) {
    $detailRes = D('petition')->petition($pid);

    if($detailRes['errcode'] != 1) {
        continue;
    }
    $petition = $detailRes['res']['petition_info']['petition'];
    $mainInfo = array(
        'from' => 'luotonglong@domob.cn',
        'pwd' => '',//FIXME 密码
        'to' => array_unique($fllowers),
        'title' => $petition['title'],
        'content' => $petition['desc'],
    );
    $res = send_mail($mainInfo);
    if ($res === true) {
        //改变状态为DOING
        D("Petition")->boolChangeStatus($petition['id'], STATUS_PETITION_DOING);
    }else {
        file_put_contents("caocao.txt", $res->ErrorInfo);
        //echo $res->ErrorInfo;
        //var_dump($res);
    }
}


