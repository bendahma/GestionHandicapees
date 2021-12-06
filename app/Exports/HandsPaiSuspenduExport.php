<?php

namespace App\Exports;

use App\Hand;
use App\Commune;



use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use DB;

class HandsPaiSuspenduExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $hands = Hand::onlyTrashed()->whereHas('status',function($s){
            $s->where('status', 'suspendu')->orWhere('status', 'arrete');
        })->get();
        
        return $hands;
    }

    public function map($hand): array
    {   
        $commune = Commune::where('codeCommune',$hand->codeCommune)->first();     

        return [
            '',
            $hand->nameFr,
            $hand->sex,
            $hand->dob,
            $hand->address,
            $commune->nomCommuneFr,
            $hand->paieinformation->CCP,
            $hand->cartehand->natureHandFr?? '',
            $hand->status->motifAr,
            $hand->status->autreMotif,
            $hand->status->dateSupprission,
            $hand->securitesociale->NSS,
        ];
    }

    public function headings(): array
    {

        return [
            ['REPUBLIQUE  ALGERIENNE  DEMOCRATIQUE  ET  POPULAIRE'],
            ['WILAYA  D\'AIN  TEMOUCHENT'],
            ['DIRECTION DE L\'ACTION  SOCIALE'],
            ['LISTE DES HANDICAPES SUSPENDU OU ARRETE'],
            [''],
            [''],
            ['N° ORD',
            'Nom & Prenom',
            'Sex',
            'Date de Naissance',
            'Adresse',
            'Commune',
            'CCP',
            'NTURE',
            'status',
            'status',
            'date supprission',
            'NSS',
            ]
        ];
    }
}
