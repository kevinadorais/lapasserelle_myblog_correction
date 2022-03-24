<?php
namespace App\Services;

use DateTime;

class FileService
{
    public function saveFile(string $path, $file, $old = false){
        if($old){
            $this->removeFile($path, $old);
        }
        // Récuperation du nom du fichier
        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        // Ajout au nom du fichier l'extension (.jpg)
        $fileName = $fileName.'.'.$file->guessExtension();
        // Déplacement de mon fichier dans notre dossier
        $file->move($path, $fileName);

        return $fileName;

    }

    public function removeFile(string $path, string $filename){
        unlink($path.'/'.$filename);
    }





}