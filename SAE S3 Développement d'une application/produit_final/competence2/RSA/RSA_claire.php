<?php

//Fonction qui génère une clef publique et sa clef privée sous la forme ["PU" => [e,n], "PR" => [d,n]]
//Les clefs generées ne font que 8 chiffres pour éviter d'avoir des nombres trop grand lors des calculs vu qu'au dela d'un certain stade les int deviennent des float
//Cela permet quand même de chiffrer par paquet de 3 caractères
function generer_clef(){
    for ($i=2; $i < 10000; $i++) {              //Pour commencer on va faire une liste des nombres entiers jusqu'à 10000
        $erato[$i] = True;
    }
    for ($i=2; $i*$i < count($erato) ; $i++) {  //On applique l'algorithme du crible d'eratosthène
        if($erato[$i]){
            for ($j=$i*$i; $j < count($erato); $j+=$i) { 
                $erato[$j] = False;
            }
        }
    }
    foreach($erato as $c => $v){
        if($v){
            $prime[] = $c;          //$prime contient une liste de nombres premiers
        }
    }
    $n = 0;                         //On initialise $n à 0 qui sera après le résultat de $p * $q            
    while ($n < 16777215 or $n > 100000000) {           //Tant que la valeur du n pour la clef n'est pas dans l'interval que l'on veut on va changer les valeurs de $p et $q pour recalculer n
        $p= $prime[random_int(0,count($prime))];        //On choisis un indice aléatoire pour selectionner deux nombres premiers dans $prime
        $q= $prime[random_int(0,count($prime))];
        $n = $p*$q;                                     //On recalcule $n jusqu'a ce que la condition de boucle ne soit plus respectée
    }
    $e = 2;                     //Maintenant qu'on a le $n on initalise notre $e à 2
    $i = 1;     
    $phi = ($p-1)*($q-1);       //On calcule phi qui va nous permettre de trouver d l'inverse modulaire de $e modulo $phi
    $d = invers_mod($e,$phi);   //On appelle la fonction qui permet de calculer l'inverse modulaire 'd' avec les parametres $e et $phi                         
    while($d["g"]!=1){          //Tant que le $e choisi n'est pas inversible modulo $phi on va choisir le nombre premier suivant pour $e
        $e = $prime[$i];
        $d = invers_mod($e,$phi);
        $i++;
    }
    $d = $d["x"]%$phi; //On arrive ici en ayant un $e inversible et on calcule $d
    if($d < 0){        //Pour eviter un $d negatif
        $d += $phi;
    }
    return ["PU" => ["e" => $e, "n" => $n],"PR" => ["d" => $d, "n" => $n]]; //On retourne les deux clefs sous le format ["PU" => ["e" : $e, "n" : $n], "PR" => ["d" : $d, "n" : $n]]

}


//Fonction qui calcule fait l'algorithme du PGCD etendu
//Pour faire cette algo je me suis basé sur celui vu en TD
function invers_mod($a,$n){
    if($a == 0){        //condition qui se réalise quand on arrive à la fin des divisions
        return ["g" => $n,"x" => 0,"y" => 1];
    } 
    else{               //Tant qu'il reste des nombres à traiter la fonction s'appelle et renvoie à chaque fois ses valeurs à l'appelle précedent qui permet de revenir jusqu'aux nombres de départ et obtenir notre inverse
        $tab = invers_mod($n%$a,$a);
        return ["g" => $tab["g"],"x" => $tab["y"]-(intdiv($n,$a)*$tab["x"]),"y" => $tab["x"]];
    }
}

//Fonction à laquelle on donne notre message à encoder ($m) et la clef publique avec laquelle on va chiffrer, il y en a une valide de base mais elle peut etre remplacé par une generer par la fonction generer_clef()
function encodage($m , $pu = ["e" => 101, "n" => 35001427]){
    $msg = str_split($m,3);                             //On traite des paquets de 3 caracteres avec la longueur de notre clef donc on commence par separer la chaine $m en un tableau de sous-chaine de longueur 3
    foreach ($msg as $v) {                              
        $msg_c[] = coder($v,$pu["e"],$pu["n"]);         //Pour chacun des paquets de notre message on va les restocker de la meme maniere dans un tableau mais cette fois leur version crypté
    }
    $msg_c = implode("-",$msg_c);                       //On créer un chaine sous le format XXXXXX-XXXXXXX-XXXXXX Ou chacun des blocs représentent un paquet 
    return base64_encode($msg_c);                       //On retourne cette chaine encoder en base64 pour brouiller un peu la visibilitée
}

//Fonction à laquelle on donne notre chaine en base64 à decrypter ($m) et la clef privée, de même que pour l'encodage il y a de base la clef correspondante
function decodage($m, $pr = ["d" => 692493, "n" => 35001427]){
    $msg = base64_decode($m);                           //On transforme le message en base64 en son format originale XXXXXX-XXXXXXX-XXXXX
    $msg = explode("-",$msg);                           //On reparti chacun des paquets séparés par un '-' dans un tableau
    foreach($msg as $v){
        $msg_d[] = decoder($v,$pr["d"],$pr["n"]);       //Pour chacun des paquets de notre message on va les restocker de la meme maniere dans un tableau mais cette fois leur version decryptée
    }
    $msg_d = implode("",$msg_d);                        //On recolle tous les morceaux en une seule chaine qui correspond au message d'origine
    return $msg_d;
}

//Fonction de calcule d'exponentiation modulaire rapide $nb : nombre a caculer / $e = exposant de $nb / $n : modulo
function EMR($nb,$e,$n){
    $ebin = decbin($e);                         //On stock l'écriture binaire de l'exposant
    $tab[0] = $nb;                              //On stock pour la valeur binaire de 2**0
    $res = 1;                                   
    for ($i=1; $i < strlen($ebin); $i++) {      //On applique l'algorithme donc pour chacun des bits présents dans l'ecriture binaire de $e
        $tab[$i] = ($tab[$i-1]**2)%$n;          //On calcule donc le carré de la valeur précedente du tableau modulo $n
    }
    $i = strlen($ebin)-1;
    foreach ($tab as $v) {                      //Une fois qu'on a complété notre tableau avec les valeurs modulo $n pour chacun des bits
        if($ebin[$i]=="1"){                     //On teste si le bit est à 1 pour savoir si on va prendre la valeur qui lui est associé dans le calcule final
            $res = ($res*$v)%$n;                //On multiplie notre résultat par le résultat qui lui est associé toujours modulo $n
        }
        $i--;
    }
    return $res;                     //Après la boucle $res stock le résultat de $nb**$e modulo $n donc on le retourne
}

//Fonction qui code un message en renvoyant l'entier qui correspond au message crypté par RSA
function coder($m, $e, $n){     
    $hex = bin2hex($m);         //On converti la chaine de caractère en hexadecimale pour eviter toute perte d'information
    $i = hexdec($hex);          //On recupere la valeur décimale de la chaine en hexadecimale pour pouvoir faire des calculs sur ce nombre
    $c = EMR($i,$e,$n);         //On applique l'EMR pour obtenir la version crypté de l'entier tel que (entier correspondant au message)**$e modulo $n
    return $c;
}

//Fonction qui decode un message en renvoyant la chaine de caractere dechiffré correspondant à l'entier passé en paramètre
function decoder($m,$d,$n){
    $dch = EMR($m,$d,$n);       //On applique l'EMR pour obtenir l'entier décrypté tel que (entier correspondant au paquet crypté)**$d modulo $n
    $hex = dechex($dch);        //On retransforme notre entier en sa version hexadecimale
    return hex2bin($hex);       //On transforme la version hexadecimale en chaine de caractère (paquet decrypté)
}

/* 
POUR TESTER :

$clef = generer_clef();

$t = encodage("Bonjour je suis un test", $clef["PU"]);
var_dump($t);
$t1 = decodage($t, $clef["PR"]);
var_dump($t1);
*/

?>

