<?php

//Cette fonction permet de générer la clé privée et publique 
function genererClesRSA() {

    //On génère d'abord la clé privée
    $privateKey = openssl_pkey_new(array(
        'private_key_bits' => 2048,
        'private_key_type' => OPENSSL_KEYTYPE_RSA,
    ));

    //On exporte la clé privée sous forme de chaîne au format PEM et on stocke dans $privateKeyPEM
    openssl_pkey_export($privateKey, $privateKeyPEM);

    //On récupère les détails de la clé privée pour pouvoir récupérer la clé publique 
    $details = openssl_pkey_get_details($privateKey);
    $publicKey = $details['key'];

    //On stocke les deux clés dans deux fichiers différents
    file_put_contents("keyprivate.txt", $privateKeyPEM);
    file_put_contents("keypublic.txt", $publicKey);
}

//Cette fonction permet de crypter un message  
function crypte($message){

    //On récupère la clé publique qu'on a stocké dans le fichier 
    $publicKey = file_get_contents("keypublic.txt");

    //On crypte le message et on le stocke dans $crypted 
    openssl_public_encrypt($message, $crypted, $publicKey);

    //On retourne le message crypté encoder en base64
    return base64_encode($crypted);
}

//Cette fonction permet de décrypter un message crypter
function decrypte($crypted) {

    //On récupère la clé privée qu'on a stocké dans le fichier
    $privateKey = file_get_contents("keyprivate.txt");

    //On décode le message crypté qu'on avait encodé en base64
    $crypted = base64_decode($crypted);

    //On décrypte le message avec la clé privée et on le stocke dans $decrypted
    openssl_private_decrypt($crypted, $decrypted, $privateKey);

    //On retourne le message clair
    return $decrypted;
}

?>