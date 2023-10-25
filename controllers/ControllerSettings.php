<?php

// correspond a controller profil
class ControllerSettings
{

    public function DefaultAction(): void
    {
        header("Location: /Settings/ManageAccount");
        //MotorView::show('profileSettings/manageAccount.php');
    }

    public function ManageAccountAction(Array $A_parametres = null,array $A_postParams = null): void
    {
        print_r($A_postParams);
        MotorView::show('profileSettings/manageAccount');
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            echo "on rentre dans le post";


            if (SessionManager::isUserConnected()) { //TODO reflechir a plus de sécurisation
                // premier post, on change l'image de profil de l'utilisateur dans le model par le fichier uploader
                if (isset($A_postParams["ChangeProfilePicture"])) {
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                    $minFileSize = 1000; // Taille minimale en octets
                    $maxFileSize = 5000000; // Taille maximale en octets (ici, 5 Mo)
                    $uploadDirectory = Constants::mediaDirectoryUsers()  . $_SESSION["UserId"] ; //double retour en arriere car on est pas a la racine ici

                    echo "$uploadDirectory";
                    error_log($uploadDirectory);
                    error_log("current path : " . getcwd());

                    if (!is_dir($uploadDirectory)) {
                        if (mkdir($uploadDirectory)) { // création dossier
                            error_log("Le dossier a été créé avec succès.");
                        } else {
                            error_log("Une erreur est survenue lors de la création du dossier.");
                        }
                    }
                    error_log("On va demander la verif d'image");
                    try {
                        $result = PictureVerificator::VerifyPDP($_FILES['ProfilePicture'], $uploadDirectory,
                                                                       $allowedExtensions, $minFileSize, $maxFileSize);
                        //error_log( " resultat de l'upload : $result");
                        if ($result[0] != "success") {
                            throw new ExceptionsUpload($result);
                        }
                        else {
                            (new UserSite)->update_picture($_SESSION["UserId"],$result[1]);
                            $_SESSION['UrlPicture']=$result[1]; //synchronisation de la session au niveau de l'image
                        } // maj bdd de l'image

                    } catch (ExceptionsUpload $e) {
                        echo $e->getMessage();
                    }

                } else {

                    echo "Une erreur est survenue lors de l'upload du fichier.";
                }
                // deuxieme post, on change le pseudo

                // troisieme post, on change le mail

                // quatrieme post, on change le mot de passe

                // cinquieme post, on supprime le compte


            } else {
                echo "warning : nobody is connected"; // TODO log
            }
        }

    }


    public function LanguageAction(): void
    {
        MotorView::show('profileSettings/language');
    }
}

?>