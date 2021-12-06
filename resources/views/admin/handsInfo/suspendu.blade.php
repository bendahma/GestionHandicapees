@extends('layouts.template')

@section('page')
      Suspension
@endsection

@section('showSuspenduInfo')

<div class="container">

   <div class="d-sm-flex align-items-center justify-content-between mb-4">
       {{-- <h1 class="h3 mb-0 text-gray-800">Les informations du handicapé(e) Suspendu</h1> --}}
         <div>
          
          @if($hand->status->status == 'En cours')
          <div class="form-group">
            <label for="" style="color:black;font-weight:700">Decision réglement</label>
            <select class="custom-select text-dark" onchange="window.location.href=this.value;" style="color:black;font-weight:700">
              <option selected disabled >Selectionée</option>
              <option value="{{route('decision.telecharger', [$hand->id,'reglement'])}}" style="color:black;font-weight:700">Décision Du Réglement</option>
              <option value="{{route('decision.renouvellement', $hand->id)}}" style="color:black;font-weight:700">Décision Du Dossier Annuel</option>
            </select>
          </div>
              {{-- <a class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm" href=""> <span style="color:rgb(255, 255, 255)"><i class="fas fa-file-download"></i></span> Décision Du Réglement </a>  --}}
          @endif
          @if($hand->status->status == 'Suspendu')
              <a class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm" href="{{route('convocation.suspension',$hand->id)}}"> 
                    <span style="color:white">
                          <i class="fas fa-envelope-open-text"></i>
                    </span> 
                    Convocation 
              </a> 
              <a class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm" href="{{route('decision.telecharger', [$hand->id,'suspension'])}}"> <span style="color:rgb(255, 255, 255)"><i class="fas fa-file-download"></i></span> Décision Du suspension </a> 
              <a class="d-none d-sm-inline-block btn btn-sm btn-warning text-dark shadow-sm" href="{{route('notification.telecharger', [$hand->id,'suspension'])}}"> <span style=""><i class="fas fa-file-download"></i></span> Notification </a> 
          @endif
          @if($hand->status->status == 'Arrete')
              <a class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm" href="{{route('decision.telecharger', [$hand->id,'arrete'])}}"> <span style="color:rgb(255, 255, 255)"><i class="fas fa-file-download"></i></span> Décision d'Arrete </a> 
              <a class="d-none d-sm-inline-block btn btn-sm btn-warning text-dark shadow-sm" href="{{route('notification.telecharger', [$hand->id,'arrete'])}}"> <span style=""><i class="fas fa-file-download"></i></span> Notification </a> 

          @endif
          @if($hand->status->status != 'En cours')
                <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" onclick="remiHandaler({{$hand->id}})"> <span><i class="far fa-check-circle"></i></span> Régle la situation </button>
          @endif
        </div>
         
   </div>

   <div class="card shadow">
       <div class="card-body">
           <form>
               <h4 class="text-danger font-weight-bold">Etat d'Handicapée</h3>
               <hr>
               <div class="row mt-1">
                   <div class="col-lg-4">
                       <div class="form-group">
                           <label for="name" class="font-weight-bold">Nom & Prenom</label>
                           <input type="text" readonly class="form-control" id="name" name="nameFr" placeholder="Nom & Prenom ..." value="{{isset($hand) ? $hand->nameFr : ''}}">
                         </div>
                    </div>
                   <div class="col">
                       <div class="form-group">
                           <label for="" class="font-weight-bold">Date de Naissance</label>
                           <input type="date" readonly class="form-control" id="" name="dob" placeholder="Date de naissance ..." value="{{ isset($hand) ? $hand->dob : '' }}">
                       </div>
                   </div>
                   <div class="col">
                     <div class="form-group">
                         <label for="" class="font-weight-bold">Commune</label>
                         <input type="text" readonly class="form-control" id="" name=""value="{{ isset($hand) ? $commune->nomCommuneFr : '' }}">
                     </div>
                 </div>
               </div>
               <div class="row mt-1">
                  <div class="col">
                      <div class="form-group">
                          <label for="name" class="font-weight-bold">Etat</label>
                          <input type="text" readonly class="form-control" id="" name="" value="{{isset($hand) ? $hand->status->status : ''}}">
                        </div>
                   </div>
                   @if($hand->status->motifAr != NULL)
                        <div class="col" >
                              <div class="form-group">
                                  <label for="" class="font-weight-bold" style="text-align: center">Motif</label>
                                  <input type="text" readonly class="form-control text-right" id="" name="" value="{{
                                      isset($hand) ? $hand->status->getMotifAr($hand->status->motifAr) : ''
                                  }}">
                              </div>
                        </div>
                        @if ($hand->status->motifAr == 'AUTRE')
                        <div class="col">
                          <div class="form-group">
                              <label for="" class="font-weight-bold">Autre</label>
                              <input type="text" readonly class="form-control" id="" name="" value="{{ isset($hand) ? $hand->status->autreMotif : '' }}">
                          </div>
                        </div>
                        @else
                        <div class="col">
                          <div class="form-group">
                              <label for="" class="font-weight-bold">MOTIF</label>
                              <input type="text" readonly class="form-control" id="" name="" value="{{ isset($hand) ? $hand->status->motifAr : '' }}">
                          </div>
                        </div>
                        @endif
                        <div class="col">
                          <div class="form-group">
                              <label for="" class="font-weight-bold">Date Suppression</label>
                              <input type="date" readonly class="form-control" id="" name="" value="{{ isset($hand) ? $hand->status->dateSupprission : '' }}">
                          </div>
                        </div>
                      @else
                        <div class="col">
                          <div class="form-group">
                            <label for="" class="font-weight-bold" style="text-align: center">Raison</label>
                            <input type="text" readonly class="form-control" id="" name="" value="{{
                                isset($hand) ? $hand->status->raisonEnAttente : ''
                            }}">
                        </div>
                        </div>
                        <div class="col">
                          <div class="form-group">
                              <label for="" class="font-weight-bold">Date Commission Pension</label>
                              <input type="date" readonly class="form-control" id="" name="" value="{{ isset($hand) ? $hand->status->EnAttentedateComissionPension : '' }}">
                          </div>
                        </div>
                    @endif
                   
                  
               </div>
              
               @if($hand->status->motifAr != NULL)
               <div class="row">
                <div class="col">
                  <div class="form-group">
                      <label for="" class="font-weight-bold">justification</label>
                      <input type="text" readonly class="form-control" id="" name="" value="{{ isset($hand) ? $hand->status->justification : '' }}">
                  </div>
              </div>
              <div class="col">
                  <div class="form-group">
                      <label for="" class="font-weight-bold">Declare par</label>
                      <input type="text" readonly class="form-control" id="" name="" value="{{ isset($hand) ? $hand->status->declarepar : '' }}">
                  </div>
              </div>
              {{-- <div class="col">
                  <div class="form-group">
                      <label for="" class="font-weight-bold">Dossier annuel</label>
                      @if ($hand->renouvellementdossier->dossierRenouvelle == false) 
                      <input type="text" readonly class="form-control" id="" name="" style="color: red;font-weight:700" value="Dossier Annuel non renouvelle">

                      @endif                 
                  </div>
              </div> --}}
              </div>
              @endif
              <div class="row">
                <div class="col">
                  <label for="" class="font-weight-bold">Observation</label>
                  <textarea name="obs" id="" cols="30" rows="2" class="form-control">{{$hand->status->ObsSuspension}} 
                    
                  </textarea>
                </div>
              </div>
             </form>
       </div>
   </div>
</div>
    
<form action="" method="POST" id="remiForm">
    @csrf
    <div class="modal fade" id="remiHand" tabindex="-1" role="dialog" aria-labelledby="remiHand" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="deleteModelTitle">Régle la situation du paiement</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div class="row">
                  <div class="col">
                    <div class="form-group">
                        <label for="" class="font-weight-bold">Situation</label>
                        <select name="status" class="form-control" id="NewSituation">
                          <option value="" selected disabled>Choisi ...</option>
                          <option value="en cours">En cours</option>
                          <option value="En attente">En attente</option>

                        </select>
                    </div>
                  </div>      
                  <div class="col" style="display: none" id="dateRemi">
                    <div class="form-group">
                        <label for="" class="font-weight-bold">Date de Réglement</label>
                       <input type="date" name="dateRemi" id="" class="form-control">
                    </div>
                  </div>   
                  <div class="col" style="display: none" id="EnAttentedateComissionPension">
                    <div class="form-group">
                        <label for="" class="font-weight-bold">Date Comission Pension</label>
                       <input type="date" name="EnAttentedateComissionPension" id="" class="form-control">
                    </div>
                  </div>     
                </div> 
                <div class="row" style="display: none" id="raisonRemi">
                    <div class="col">
                      <div class="form-group">
                        <label for="" class="font-weight-bold">Raison</label>
                        <input type="text" name="raison" id="" class="form-control">
                      </div>
                    </div>
                </div> 
                <div class="row" style="display: none" id="raisonEnAttente">
                    <div class="col">
                      <div class="form-group">
                        <label for="" class="font-weight-bold">Raison En Attente</label>
                        <input type="text" name="raisonEnAttente" id="" class="form-control">
                      </div>
                    </div>
                </div> 
                <h5 style="color:black;font-weight:700; margin-bottom:1rem;text-decoration:underline; display:none"  id="RappelTitle">Rappel</h5>
                <div class="row" style="display: none" id="meriteRappel">
                  <div class="col">
                    <div class="form-group">
                      <label for="" class="font-weight-bold">Rappel</label>
                      <select name="meriteRappel" id="" class="form-control">
                        <option value="oui">Oui</option>
                        <option value="non" selected>Non</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row" style="display: none" id="rappelDates">
                  <div class="col">
                    <div class="form-group">
                      <label for="" class="font-weight-bold">Date Debut</label>
                      <input type="date" name="dateDebut" id="" class="form-control" value="{{$hand->status->dateSupprission}}">
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group">
                      <label for="" class="font-weight-bold">Date Fin</label>
                      <input type="date" name="dateFin" id="" class="form-control">
                    </div>
                  </div>
              </div>
              <div class="row" style="display: none" id="rappelObs">
                <div class="col">
                  <div class="form-group">
                      <label for="" class="font-weight-bold">Obs</label>
                      <textarea name="rappelObs" id="" cols="5" rows="3" class="form-control"></textarea>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <div class="row">
                <div class="col-lg-6">
                  <button type="submit" class="btn btn-success btn-block">Régle</button>
                </div>
                <div class="col-lg-6">
                  <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">Annulé</button>
                </div>
              </div>
            </div>
        </div>
        </div>
    </div>

</form>

@endsection