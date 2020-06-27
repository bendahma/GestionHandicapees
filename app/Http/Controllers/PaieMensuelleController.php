<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

use App\Exports\HandExport;
use Maatwebsite\Excel\Facades\Excel;
use DB;


use App\Hand;
use App\Paie;
use App\HandPaieStatus;
use App\Hand_Paie;
use App\MoisAnnee;
use App\Budget;

require_once('ChiffresEnLettres.php');

class PaieMensuelleController extends Controller
{


    public function index() {
        // Getting the lists of hands - En cours : Suspendu : Arrete
        $hands = Hand::whereHas('status',function($s){
            $s->where('status', 'en cours');
        })->get();

        $handsSuspendu = Hand::onlyTrashed()->whereHas('status',function($s){
            $s->where('status', 'suspendu');
        })->get();

        $handsArrete = Hand::onlyTrashed()->whereHas('status',function($s){
            $s->where('status', 'Arrete');
        })->get();

        // Calculating
        $countHand = $hands->count();
        $montantPaie = $countHand * config('paie.MontantPaie');
        $montantAssurance = $countHand * config('paie.MontantAssurance');
        $budgetI = new Budget();
        $budget = $budgetI->CreateNewYearBudget(date('Y'));
        $paieExist = Paie::where('anneesPaiement',date('Y'))->where('moisPaiement', date('m'))->first();
        
        
        if(!$paieExist){
            //Create Paiement 
            $currentPaie = Paie::create([
                'moisPaiement'=>date('m'),
                'anneesPaiement'=>date('Y'),
                'montantPaiement'=>$montantPaie,
                'montantAssurance'=>$montantAssurance
            ]);
            
        }

        if($paieExist){
            $paieExist->hands()->detach($handsSuspendu);
            $paieExist->hands()->detach($handsArrete);
            if (!$paieExist->hands->contains($paieExist->id)) {
                $paieExist->hands()->attach($hands);
            }
        }
        
        
        return view('admin.paie.resume')
                    ->with('CurrentPaie',$paieExist)
                    ->with('count',$countHand);
        
    }

    public function export() {
        $filename = 'EtatPaiementHands' .date('YmdHis'). '.xlsx';
        ob_end_clean();
        ob_start();
        return Excel::download(new HandExport, $filename);
    }

    public function Cnas($papier){   
        if($papier == 'BORDEREAU'){
            $template = new \PhpOffice\PhpWord\TemplateProcessor(dirname(dirname(__DIR__)) . '\Templates\CnasBord.docx');
            $output = 'BordereauCnas.docx';
        }else if($papier == 'COTISATION'){
            $template = new \PhpOffice\PhpWord\TemplateProcessor(dirname(dirname(__DIR__)) . '\Templates\CotisationCnas.docx');
            $output = 'CotisationCnas.docx';
        }else if($papier == 'AVIS'){
            $template = new \PhpOffice\PhpWord\TemplateProcessor(dirname(dirname(__DIR__)) . '\Templates\AVISDEVIREMENTCNAS.doc');
            $output = 'AvisVirement.docx';
        }
        else{
            return redirect()->back();
        }
        
        $nbrHand= Hand::whereHas('status',function($s){
            $s->where('status', 'en cours');
        })->get();
        $annee = date('Y');
        $moisN = date('m');
        $mois = MoisAnnee::find($moisN);
        $nbrHandCount = $nbrHand->count();
        $VSAC = $nbrHandCount * 20000;
        $MSAC = ($VSAC * 5)/100;
        $ChiffreEnLettre = new ChiffreEnLettres();
        $ChiffreEnLettreOutput= $ChiffreEnLettre->Conversion($MSAC);
        $template->setValue('nbrHand', $nbrHandCount);
        $template->setValue('VSAC', number_format($VSAC,2,',',' '));
        $template->setValue('MSAC',  number_format($MSAC,2,',',' '));
        $template->setValue('annee',  $annee);
        $template->setValue('mois',  $mois->moisFr);
        $template->setValue('montantLettre',  strtoupper($ChiffreEnLettreOutput));
        ob_end_clean();
        ob_start();
        $template->saveAs(storage_path($output));
        return response()->download(storage_path($output));
    }

    public function decision(){
        $template = new \PhpOffice\PhpWord\TemplateProcessor(dirname(dirname(__DIR__)) . '\Templates\DECISION.docx');
        $output = 'Decision.docx' ;

        $nbrHand= Hand::whereHas('status',function($s){
            $s->where('status', 'en cours');
        })->get();
        $annee = date('Y');
        $mois = MoisAnnee::find(date('m'));
        $nbrHandCount = $nbrHand->count();
        $montantChiffre = $nbrHandCount * config('paie.MontantPaie');
        // dd($montantChiffre);
        $montantLettreAr = new ChiffreEnLettresArb($montantChiffre,"male");
        $c = $montantLettreAr->convert_number();
        $template->setValue('montantChiffreAr', number_format($montantChiffre,2,',',' '));
        $template->setValue('montantLettreAr', $c);
        $template->setValue('annee',  $annee);
        $template->setValue('moisAr',  $mois->moisAr);
        ob_end_clean();
        ob_start();
        $template->saveAs(storage_path($output));
        return response()->download(storage_path($output));
    }

    public function Journal(){      
        $template = new \PhpOffice\PhpWord\TemplateProcessor(dirname(dirname(__DIR__)) . '\Templates\JOURNAL.docx');
        $output = 'JOURNAL.docx';
        $nbrHand= Hand::whereHas('status',function($s){
            $s->where('status', 'en cours');
        })->get();
        $an = date('Y');
        $nbrHandCount = $nbrHand->count();
        $montantPaie = $nbrHandCount * config('paie.MontantPaie');
        $montantCnas = $nbrHandCount * config('paie.MontantAssurance');
        $template->setValue('montantPaie', number_format($montantPaie,2,',',' '));
        $template->setValue('montantCnas', number_format($montantCnas,2,',',' '));
        $template->setValue('an', $an);
        ob_end_clean();
        ob_start();
        $template->saveAs(storage_path($output));
        return response()->download(storage_path($output));
    }

    public function BordereauCf(){        
        $template = new \PhpOffice\PhpWord\TemplateProcessor(dirname(dirname(__DIR__)) . '\Templates\BORDEREAUCF.docx');
        $annee = date('Y');
        $mois = MoisAnnee::find(date('m'));
        $template->setValue('annee', $annee);
        $template->setValue('mois', $mois->moisAr);
        ob_end_clean();
        ob_start();
        $template->saveAs(storage_path('Bordereau CF.docx'));
        return response()->download(storage_path('Bordereau CF.docx'));
    }

    public function BordereauCD(){  
        
        $nbrHand= Hand::whereHas('status',function($s){
            $s->where('status', 'en cours');
        })->get();

        $montantPaie= $nbrHand->count()*config('paie.MontantPaie');
        
        $template = new \PhpOffice\PhpWord\TemplateProcessor(dirname(dirname(__DIR__)) . '\Templates\BordereauCD.docx');
        $annee = date('Y');
        $mois = MoisAnnee::find(date('m'));
        $template->setValue('annee', $annee);
        $template->setValue('mois', $mois->moisAr);
        $template->setValue('montant', number_format($montantPaie,2,',',' '));
        ob_end_clean();
        ob_start();
        $template->saveAs(storage_path('BordereauCD.docx'));
        return response()->download(storage_path('BordereauCD.docx'));
    }

    public function Repartition(){
        
        $template = new \PhpOffice\PhpWord\TemplateProcessor(dirname(dirname(__DIR__)) . '\Templates\Repartition.docx');
        
        $hands = DB::table('hands')
                    ->join('hand_paie_statuses', function ($join) {
                        $join->on('hands.id', '=', 'hand_paie_statuses.hand_id')
                        ->where('hand_paie_statuses.status', '=', 'en cours');
                })->select('commune', DB::raw('count(*) as total'))
                ->groupBy('commune')
                ->having('total', '>=', '0')
                ->get();

        // $t =0;
        // foreach ($hands as $h) {
        //     dump($h->commune . "  " . $h->total);
        //     $t+=$h->total;
        // }
        // dd($t);

        $nbrt =0;
        foreach ($hands as $h) {
            $nbrt+=$h->total;
        }
       
        foreach ($hands as $hand){
            switch($hand->commune){
                case " EL AMRIA":
                    $ELAMRIA = $hand->total;
                case "A-TEMOU":
                    $AT = $hand->total;
                case "AGHLLAL":
                    $AGHLLAL = $hand->total;
                case "AIN KIHAL":
                    $AINKIHAL = $hand->total;
                case "AIN LABAA":
                    $AINLABAA = $hand->total;
                case "AIN TOLBA":
                    $AINTOLBA = $hand->total;
                case "AOUGBELLIL":
                    $AOUGBELLIL = $hand->total;
                case "BENI SAF":
                    $BENISAF = $hand->total;
                case "BOUZEDJAR":
                    $BOUZEDJAR = $hand->total;
                case "CHAABAT":
                    $CHAABAT = $hand->total;
                case "CHENTOUF":
                    $CHENTOUF = $hand->total;
                case "El MALEH":
                    $ElMALEH = $hand->total;
                case "EMIR-AEK":
                    $EMIRAEK = $hand->total;
                case "H-El-Ghella":
                    $HElGhella = $hand->total;
                case "HASSASNA":
                    $HASSASNA = $hand->total;
                case "HBH":
                    $HBH = $hand->total;
                case "M'SAID":
                    $MSAID = $hand->total;
                case "O-Berkeche":
                    $OBerkeche = $hand->total;
                case "O-Boudjema":
                    $OBoudjema = $hand->total;
                case "O-SEBBAH":
                    $OSEBBAH = $hand->total;
                case "Ouled- kihal":
                    $Ouledkihal = $hand->total;
                case "OULHAÇA":
                    $OULHACA = $hand->total;
                case "S-Boumediene":
                    $SBoumediene = $hand->total;
                case "SIDI BEN ADDA":
                    $SIDIBENADDA = $hand->total;
                case "SIDI Ouriache":
                    $SIDIOuriache = $hand->total;
                case "SIDI SAFI":
                    $SIDISAFI = $hand->total;
                case "TAMEZOURA":
                    $TAMEZOURA = $hand->total;
                case "TERGA":
                    $TERGA = $hand->total;
            }
        }

        $montantChiffre = $nbrt*config('paie.MontantPaie');
        $annee = date('Y');
        $mois = MoisAnnee::find(date('m'));
        
        $montantLettre = new ChiffreEnLettresArb($montantChiffre,"male");
        $montantLettreAr = $montantLettre->convert_number();

        $template->setValue('annee', $annee);
        $template->setValue('mois', $mois->moisAr);
        
        $template->setValue('AT', $AT);
        $template->setValue('ADDA', $SIDIBENADDA);
        $template->setValue('MALEH', $ElMALEH);
        $template->setValue('CHABAT', $CHAABAT);
        $template->setValue('TERGA', $TERGA);
        $template->setValue('OKIHEL', $Ouledkihal);
        $template->setValue('AMRIA', $ELAMRIA);
        $template->setValue('HASSI', $HElGhella);
        $template->setValue('BOUDJ', $OBoudjema);
        $template->setValue('BOUZED', $BOUZEDJAR);
        $template->setValue('MSAID', $MSAID);
        $template->setValue('HBH', $HBH);
        $template->setValue('CHENT', $CHENTOUF);
        $template->setValue('HSASNA', $HASSASNA);
        $template->setValue('BERKEC', $OBerkeche);
        $template->setValue('AREBA', $AINLABAA);
        $template->setValue('BOUMD', $SBoumediene);
        $template->setValue('SEBAH', $OSEBBAH);
        $template->setValue('TEMEZ', $TAMEZOURA);
        $template->setValue('AKIHEL', $AINKIHAL);
        $template->setValue('TOLBA', $AINTOLBA);
        $template->setValue('AGHLAL', $AGHLLAL);
        $template->setValue('OUBEL', $AOUGBELLIL);
        $template->setValue('BSAF', $BENISAF);
        $template->setValue('SSAF', $SIDISAFI);
        $template->setValue('EMIR', $EMIRAEK);
        $template->setValue('OULHACA', $OULHACA);
        $template->setValue('OURIACH', $SIDIOuriache);

        $template->setValue('ATMNT', number_format($AT * config('paie.MontantPaie'),'2',',',' '));
        $template->setValue('ADDAMNT', number_format($SIDIBENADDA * config('paie.MontantPaie'),'2',',',' '));
        $template->setValue('MALEHMNT', number_format($ElMALEH* config('paie.MontantPaie'),'2',',',' '));
        $template->setValue('CHABATMNT', number_format($CHAABAT* config('paie.MontantPaie'),'2',',',' '));
        $template->setValue('TERGAMNT', number_format($TERGA* config('paie.MontantPaie'),'2',',',' '));
        $template->setValue('OKIHELMNT', number_format($Ouledkihal* config('paie.MontantPaie'),'2',',',' '));
        $template->setValue('AMRIAMNT', number_format($ELAMRIA* config('paie.MontantPaie'),'2',',',' '));
        $template->setValue('HASSIMNT', number_format($HElGhella* config('paie.MontantPaie'),'2',',',' '));
        $template->setValue('BOUDJMNT', number_format($OBoudjema* config('paie.MontantPaie'),'2',',',' '));
        $template->setValue('BOUZEDMNT', number_format($BOUZEDJAR* config('paie.MontantPaie'),'2',',',' '));
        $template->setValue('MSAIDMNT', number_format($MSAID* config('paie.MontantPaie'),'2',',',' '));
        $template->setValue('HBHMNT', number_format($HBH* config('paie.MontantPaie'),'2',',',' '));
        $template->setValue('CHENTMNT', number_format($CHENTOUF* config('paie.MontantPaie'),'2',',',' '));
        $template->setValue('HSASNAMNT', number_format($HASSASNA* config('paie.MontantPaie'),'2',',',' '));
        $template->setValue('BERKECMNT', number_format($OBerkeche* config('paie.MontantPaie'),'2',',',' '));
        $template->setValue('AREBAMNT', number_format($AINLABAA* config('paie.MontantPaie'),'2',',',' '));
        $template->setValue('BOUMDMNT', number_format($SBoumediene* config('paie.MontantPaie'),'2',',',' '));
        $template->setValue('SEBAHMNT', number_format($OSEBBAH* config('paie.MontantPaie'),'2',',',' '));
        $template->setValue('TEMEZMNT', number_format($TAMEZOURA* config('paie.MontantPaie'),'2',',',' '));
        $template->setValue('AKIHELMNT', number_format($AINKIHAL* config('paie.MontantPaie'),'2',',',' '));
        $template->setValue('TOLBAMNT', number_format($AINTOLBA* config('paie.MontantPaie'),'2',',',' '));
        $template->setValue('AGHLALMNT', number_format($AGHLLAL* config('paie.MontantPaie'),'2',',',' '));
        $template->setValue('OUBELMNT', number_format($AOUGBELLIL* config('paie.MontantPaie'),'2',',',' '));
        $template->setValue('BSAFMNT', number_format($BENISAF* config('paie.MontantPaie'),'2',',',' '));
        $template->setValue('SSAFMNT', number_format($SIDISAFI* config('paie.MontantPaie'),'2',',',' '));
        $template->setValue('EMIRMNT', number_format($EMIRAEK* config('paie.MontantPaie'),'2',',',' '));
        $template->setValue('OULHACAMNT', number_format($OULHACA* config('paie.MontantPaie'),'2',',',' '));
        $template->setValue('OURIACHMNT', number_format($SIDIOuriache* config('paie.MontantPaie'),'2',',',' '));

        $template->setValue('DATEM', number_format(($AT + $SIDIBENADDA) * config('paie.MontantPaie'),'2',',',' '));
        $template->setValue('DAMALEH', number_format(($ElMALEH + $CHAABAT+$Ouledkihal +$TERGA )* config('paie.MontantPaie'),'2',',',' '));
        $template->setValue('DAAMRIA', number_format(($ELAMRIA +$HElGhella +$OBoudjema +$BOUZEDJAR + $MSAID)* config('paie.MontantPaie'),'2',',',' '));
        $template->setValue('DAHBH', number_format(($HBH +$CHENTOUF +$HASSASNA +$OBerkeche)* config('paie.MontantPaie'),'2',',',' '));
        $template->setValue('DAAREBA', number_format(($AINLABAA +$SBoumediene +$OSEBBAH +$TAMEZOURA)* config('paie.MontantPaie'),'2',',',' '));
        $template->setValue('DAAINKIHEL', number_format(($AINKIHAL + $AINTOLBA+$AGHLLAL +$AOUGBELLIL)* config('paie.MontantPaie'),'2',',',' '));
        $template->setValue('DABENISAF', number_format(($BENISAF +$SIDISAFI +$EMIRAEK)* config('paie.MontantPaie'),'2',',',' '));
        $template->setValue('DAOULHACA', number_format(($OULHACA+ $SIDIOuriache)* config('paie.MontantPaie'),'2',',',' '));

        $template->setValue('SOMCOMUN', ($AT + $SIDIBENADDA + $ElMALEH + $CHAABAT+$Ouledkihal +$TERGA + $ELAMRIA +$HElGhella +$OBoudjema +$BOUZEDJAR + $MSAID+$HBH +$CHENTOUF +$HASSASNA +$OBerkeche+$AINLABAA +$SBoumediene +$OSEBBAH +$TAMEZOURA+$AINKIHAL + $AINTOLBA+$AGHLLAL +$AOUGBELLIL));
        $template->setValue('MNCOMUN', number_format(($AT + $SIDIBENADDA + $ElMALEH + $CHAABAT+$Ouledkihal +$TERGA + $ELAMRIA +$HElGhella +$OBoudjema +$BOUZEDJAR + $MSAID+$HBH +$CHENTOUF +$HASSASNA +$OBerkeche+$AINLABAA +$SBoumediene +$OSEBBAH +$TAMEZOURA+$AINKIHAL + $AINTOLBA+$AGHLLAL +$AOUGBELLIL) * config('paie.MontantPaie') ,'2',',',' '));
        $template->setValue('SOMCOMDX', ($BENISAF +$SIDISAFI +$EMIRAEK + $OULHACA+ $SIDIOuriache));
        $template->setValue('MNCOMDX', number_format(($BENISAF +$SIDISAFI +$EMIRAEK + $OULHACA+ $SIDIOuriache) * config('paie.MontantPaie') ,'2',',',' '));
        $template->setValue('nbrhandtotal', ($AT + $SIDIBENADDA + $ElMALEH + $CHAABAT+$Ouledkihal +$TERGA + $ELAMRIA +$HElGhella +$OBoudjema +$BOUZEDJAR + $MSAID+$HBH +$CHENTOUF +$HASSASNA +$OBerkeche+$AINLABAA +$SBoumediene +$OSEBBAH +$TAMEZOURA+$AINKIHAL + $AINTOLBA+$AGHLLAL +$AOUGBELLIL) + ($BENISAF +$SIDISAFI +$EMIRAEK + $OULHACA+ $SIDIOuriache));
        $template->setValue('mnthandtotal', number_format((($AT + $SIDIBENADDA + $ElMALEH + $CHAABAT+$Ouledkihal +$TERGA + $ELAMRIA +$HElGhella +$OBoudjema +$BOUZEDJAR + $MSAID+$HBH +$CHENTOUF +$HASSASNA +$OBerkeche+$AINLABAA +$SBoumediene +$OSEBBAH +$TAMEZOURA+$AINKIHAL + $AINTOLBA+$AGHLLAL +$AOUGBELLIL) + ($BENISAF +$SIDISAFI +$EMIRAEK + $OULHACA+ $SIDIOuriache)) * config('paie.MontantPaie') ,'2',',',' '));
        
        
        $template->setValue('montantAr', $montantLettreAr);

        ob_end_clean();
        ob_start();
        $template->saveAs(storage_path('Repartition.docx'));
        return response()->download(storage_path('Repartition.docx'));
        
    }

    public function Engagement($papier){

        $template = ($papier == 'Paiement') ? new \PhpOffice\PhpWord\TemplateProcessor(dirname(dirname(__DIR__)) . '\Templates\ENGAGEMENTPaie.docx')
                                            : new \PhpOffice\PhpWord\TemplateProcessor(dirname(dirname(__DIR__)) . '\Templates\ENGAGEMENTCnas.docx');
        $filename = ($papier == 'Paiement') ? 'Engagement 46-15'
                                            : 'Engagement 33-13';
        
        $paie = Paie::where('anneesPaiement',date('Y'))->where('moisPaiement', date('m'))->first();
        $budgetIns = new Budget();
        $budget = $budgetIns->Consommation(date('Y'));

        $moisN = date('m');
        $mois = MoisAnnee::find($moisN);
        $moisAr = $mois->moisAr;

        $montantLettre = new ChiffreEnLettresArb($paie->montantPaiement,"male");
        $montantLettreAr = $montantLettre->convert_number();

        $montantLettreCnas = new ChiffreEnLettresArb($paie->montantAssurance,"male");
        $montantLettreCNasAr = $montantLettreCnas->convert_number();


        $template->setValue('annee', date('Y'));
        $template->setValue('mois', $moisAr);
        //46-15
        $template->setValue('montanP', number_format($paie->montantPaiement,0,',',' '));
        $template->setValue('consom', number_format($budget['ancienConsommationBudgetPaie'],0,',',' '));
        $template->setValue('reste', number_format($budget['nouveauConsommationBudgetPaie'],0,',',' '));
        $template->setValue('montantLettre', $montantLettreAr);
        //33-13
        $template->setValue('montanCnas', number_format($paie->montantAssurance,0,',',' '));
        $template->setValue('consomCnas', number_format($budget['ancienConsommationBudgetAssurance'],0,',',' '));
        $template->setValue('resteCnas', number_format($budget['nouveauConsommationBudgetAssurance'],0,',',' '));
        $template->setValue('montantLettreCNas', $montantLettreCNasAr);
        ob_end_clean();
        ob_start();
        $template->saveAs(storage_path($filename. " ".$mois->moisFr." ".date('Y').".docx"));
        return response()->download(storage_path($filename. " ".$mois->moisFr. " ".date('Y').".docx"));
        
    }

    public function Mondate($papier){

        $template = ($papier == 'Paiement') ? new \PhpOffice\PhpWord\TemplateProcessor(dirname(dirname(__DIR__)) . '\Templates\MANDATEPaiement.docx')
                                            : new \PhpOffice\PhpWord\TemplateProcessor(dirname(dirname(__DIR__)) . '\Templates\MANDATECnas.docx');
        $filename = ($papier == 'Paiement') ? 'Mondate 46-15'
                                            : 'Mondate 33-13';
        
        $paie = Paie::where('anneesPaiement',date('Y'))->where('moisPaiement', date('m'))->first();
        $budget = Budget::where('annee',date('Y'))->first();

        
        $moisN = date('m');
        $mois = MoisAnnee::find($moisN);
        $moisAr = $mois->moisFr;

        $ChiffreEnLettrePaie = new ChiffreEnLettres();
        $ChiffreEnLettrePaieFr = $ChiffreEnLettrePaie->Conversion($paie->montantPaiement);
        
        $ChiffreEnLettreCnas = new ChiffreEnLettres();
        $ChiffreEnLettreCnasFr = $ChiffreEnLettreCnas->Conversion($paie->montantAssurance);

        


        $template->setValue('annee', date('Y'));
        $template->setValue('mois', strtoupper($moisAr));
        //46-15
        $template->setValue('montantP', number_format($paie->montantPaiement,2,',',' '));
        $template->setValue('ChiffreEnLettreFr', strtoupper($ChiffreEnLettrePaieFr));
        //33-13
        $template->setValue('montantCnas', number_format($paie->montantAssurance,2,',',' '));
        $template->setValue('ChiffreEnLettreCnasFr', strtoupper($ChiffreEnLettreCnasFr));
        ob_end_clean();
        ob_start();
        $template->saveAs(storage_path($filename. " ".$mois->moisFr." ".date('Y').".docx"));
        return response()->download(storage_path($filename. " ".$mois->moisFr. " ".date('Y').".docx"));
        
    }
}