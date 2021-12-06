<?php

namespace App\Exports;

use App\Hand;
use App\Commune;



use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use DB;

class HandsPaiementExport implements FromCollection, WithMapping, WithHeadings
{

   

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $hands = Hand::orderBy('codeCommune','asc')->get();
        return $hands;
    }

    public function map($hand): array
    {   
        $commune = Commune::where('codeCommune',$hand->codeCommune)->first();     

        return [
            $hand->id?? '',
            $hand->nameFr?? '',
            $hand->sex?? '',
            $hand->dob?? '',
            $hand->address?? '',
            $commune->nomCommuneFr?? '',
            $hand->cartehand->numeroCart?? '',
            $hand->cartehand->natureHandFr?? '',
            $hand->cartehand->dateCarte?? '',
            $hand->paieinformation->CCP ?? '',
            $hand->paieinformation->RIP ?? '',
            // "*".$hand->securitesociale->NSS ?? '',
            $hand->addressAr ?? '',
            $commune->nomCommuneAr ?? '',
            $hand->status->status ?? '',
           
            
        ];
    }

    public function headings(): array
    {

        return [
            ['REPUBLIQUE  ALGERIENNE  DEMOCRATIQUE  ET  POPULAIRE'],
            ['WILAYA  D\'AIN  TEMOUCHENT'],
            ['DIRECTION DE L\'ACTION  SOCIALE'],
            ['LISTE GLOBALE DE L\'ALLOCATION DES HANDICAPES A 100%'],
            [''],
            [''],
            ['ID',
            'Nom & Prenom',
            'Sex',
            'Date de Naissance',
            'Adresse',
            'Commune',
            'Numero Carte',
            'Nature Carte',
            'Date Carte',
            'CCP',
            'RIP',
            // 'N° Securite Sociale',
            'العنوان',
            'البلدية',
            'status',
            ]
        ];
    }

}
