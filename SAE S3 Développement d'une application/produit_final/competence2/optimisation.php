<?php

//Cette fonction permet de générer un mot de passe aléatoire 
function genererMotDePasse($longueur=8){
 $comb = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*()-_';
 $pass = '';

$pass .= $comb[rand(0, 51)]; // Au moins une lettre
$pass .= $comb[rand(52, 61)]; // Au moins un chiffre
$pass .= $comb[rand(62, strlen($comb) - 1)]; // Au moins un caractère spécial
 for ($i = 3; $i < $longueur; $i++) {
     $n = rand(0, strlen($comb) - 1);
     $pass .= $comb[$n];
 }
 return $pass; 
}

/*
Les deux fonctions permettent de savoir si l'utilisateur a rentré des lettres, chiffres et caractères spéciaux 
dans son mot de passe lorsqu'il le modifie 
*/
//Première version
function testMotDePasse($mdp){
    //On stocke les lettres, chiffres et les caractères spéciaux dans trois tableaux différents
    $let = str_split('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
    $chif = str_split('1234567890');
    $car = str_split('!@#$%^&*()-_');
    
    $aUneLettre = false;
    $aUnChiffre = false;
    $aUnCaractère = false;
    // Parcourt chaque caractère du mot de passe passé en paramètre
    for($i = 0; $i < strlen($mdp); $i++){
        // Vérifie la présence d'au moins une lettre
        if(in_array($mdp[$i], $let)){
            $aUneLettre = true;
        }
        // Vérifie la présence d'au moins un chiffre
        if(in_array($mdp[$i], $chif)){
            $aUnChiffre = true;
        }
        // Vérifie la présence d'au moins un caractère spécial
        if(in_array($mdp[$i], $car)){
            $aUnCaractère = true;
        }
    }
    // Retourne vrai si toutes les conditions sont remplies
    return $aUneLettre & $aUnChiffre & $aUnCaractère;
}

//Version optimisée, on a décidé d'utiliser la fonction preg_match pour aller plus vite 
function verifierMotDePasse($mdp) {
    // Vérifie la présence d'au moins une lettre, un chiffre et un caractère spécial dans le mot de passe
    $aUneLettre = preg_match('/[a-zA-Z]/', $mdp);
    $aUnChiffre = preg_match('/[0-9]/', $mdp);
    $aUnCaractereSpecial = preg_match('/[^a-zA-Z0-9]/', $mdp);

    return $aUneLettre && $aUnChiffre && $aUnCaractereSpecial;
}


//Test pour voir laquelle est la plus performante
echo "<h3>testMotDePasse</h3>";
$t0 = microtime(true);
testMotDePasse(genererMotDePasse());
$t1 = microtime(true);
echo $t = ($t1 - $t0); 

echo "<h3>verifierMotDePasse</h3>";
$t0 = microtime(true);
verifierMotDePasse(genererMotDePasse());
$t1 = microtime(true);
echo $v = $t1 - $t0 ;

if($t < $v){
    $min = $t;
    $message = "testMotDePasse";
}else{
    $min = $v;
    $message = "verifierMotDePasse";
}
echo "</br>";
echo "<p>La meilleure est : <strong>$message</strong> avec un temps de $min ms.</p>";
?>