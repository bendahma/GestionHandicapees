<?php

namespace App\Imports;

use App\Hand;
use App\CartHand;
use App\PaieInformation;
use App\CarteNational;
use App\SecuriteSociale;
use App\HandPaieStatus;
use App\HandSuspentionHistory;
use App\RenouvellementDossier;
use App\Commune;
use Maatwebsite\Excel\Concerns\ToModel;

class HandsImport implements ToModel
{ 
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {


        // ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 300); // 5 minutes

        $hand = new Hand();
        $cardHand = new CartHand();
        $compte = new PaieInformation();
        $national = new CarteNational();
        $securiteSociale = new SecuriteSociale();
        $status = new HandPaieStatus();
        $historySuspension = new HandSuspentionHistory();
        $renouvellement = new RenouvellementDossier();

        if(!isset($row[0]) && !isset($row[1]) && !isset($row[2]) && !isset($row[3]) && !isset($row[4]) && !isset($row[5]) ){
            return NULL;
        }
        
        $hand->codeCommune =  isset($row[0]) ? $row[0] : NULL;
        $hand->nameFr = isset($row[1]) ? $row[1] : NULL;
        $hand->sex =  isset($row[2]) ? $row[2] : NULL;
        $hand->dob =  isset($row[3]) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[3]) : NULL;
        $hand->address = isset($row[4]) ? $row[4] : NULL;
        $hand->addressAr = isset($row[22]) ? $row[22] : NULL;
        $hand->deleted_at = (isset($row[16]) && ($row[16] == 'suspendu' || $row[16] == 'Arrete')) ? date("Y-m-d H:i:s") : NULL;
        $hand->obs = isset($row[13]) ? $row[13] : NULL;
        $hand->save();
        


        // ----------------------------------------------------------------------------------------------------
        // SUSPENSION HISTORY

        // ----------------------------------------------------------------------------------------------------
        // Paiement Information
        
        $compte->CCP = isset($row[5]) ? $row[5] : NULL;
        $compte->RIP = isset($row[6]) ? ltrim($row[6],'*') : NULL;
        $compte->datePremierPension = isset($row[9]) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[9]) : NULL;
        $compte->Beneficier = isset($row[14]) ? $row[14] : NULL;

        $hand->paieinformation()->save($compte);
        
        // -----------------------------------------------------------------------------------------------------
        // Cart information 
        $cardHand->natureHandFr =  isset($row[7]) ? $row[7] : NULL;
        $cardHand->natureHandAr =  isset($row[21]) ? $row[21] : NULL;
        $cardHand->dateCarte = isset($row[8]) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[8]) : NULL;
        $cardHand->pourcentage = 100;
        $hand->cartehand()->save($cardHand);
        
        // -------------------------------------------------------------------------------------------------------
        $national->NumeroNational =0;
        $hand->cartenational()->save($national);
        
        // -------------------------------------------------------------------------------------------------------
        // UPLOAD SOCIALE SECURITE 
        $securiteSociale->NSS = isset($row[12]) ? ltrim($row[12],'*') : NULL;
        $hand->securitesociale()->save($securiteSociale);

        // UPLOAD HAND CURRENT SITUATION --------------------------------------------------------------------------------------------------------
        $status->status = isset($row[16]) ? $row[16] : NULL;
        $status->dateSupprission = !empty($row[17]) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[17]) : NULL;
        $status->motifAr = isset($row[18]) ? $row[18] : NULL;
        $hand->status()->save($status);

        // UPLOAD HAND HISTORU SUSPENSION --------------------------------------------------------------------------------------------------------
        // dd($row[16]);
        // if(isset($row[16]) && ($row[16] != 'en cours')){
        //     $historySuspension->status = isset($row[16]) ? $row[16] : NULL;
        //     $historySuspension->dateSupprission = isset($row[17]) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[17]) : NULL;
        //     $historySuspension->motif = isset($row[18]) ? $row[18] : NULL;
        //     $hand->handSuspentionHistories()->save($historySuspension);

        //  }
        //else if(isset($row[16]) && ($row[16] == 'en cours')){
        //     if(isset($row[10]) && isset($row[11])){
        //         $historySuspension->status = 'suspendu';
        //         $historySuspension->dateSupprission = isset($row[10]) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[10]) : NULL;
        //         $historySuspension->dateRemi = isset($row[11]) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[11]) : NULL;
        //         $historySuspension->motif = 'DOSSIER ANNUEL NON RENOUVELLE';
        //         $hand->handSuspentionHistories()->save($historySuspension);
        //     }
        // }


        // UPLOAD RENOUVELLEMENT DOSSIER ANNUEL
        $renouvellement->dossierRenouvelle = isset($row[15]) ? $row[15] : NULL;
        $renouvellement->DateRenouvellement = NULL;
        $renouvellement->AnneeRenouvelement = isset($row[15]) ? date('Y') : NULL;
        $hand->renouvellementdossier()->save($renouvellement);
        try {
            return $hand; 
        } catch (\Throwable $th) {
            dd($hand)    ;    }
    }
}