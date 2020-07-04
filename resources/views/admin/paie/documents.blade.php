@extends('layouts.template')

@section('DocumentsPaie')
    <div class="container-fluid">
        <div class="card shadow">
            <div class="card-header">
               La Documents du chapitre 46-15 article U
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-lg-2">
                 <h5 class="pt-2" style="color:black;font-weight:600">Engagement 46-15</h5>
                </div>
                <div class="col-lg-2">
                  <a href="{{route('paie.engagement', 'Paiement')}}" class="btn btn-primary btn-block"><i class="fas fa-download"></i> Télécharger</a>
                </div>
                <div class="col-lg-2">
                  <h5 class="pt-2" style="color:black;font-weight:700">Mondate 46-15</h5>
                </div>
                <div class="col-lg-2">
                   <a href="{{route('paie.mondate', 'Paiement')}}" class="btn btn-primary btn-block"><i class="fas fa-download"></i> Télécharger</a>
                </div>
               </div>
               <div class="row mt-3">
                  <div class="col-lg-2">
                    <h5 class="pt-2" style="color:black;font-weight:700">Etat Paiement</h5>
                  </div>
                  <div class="col-lg-2">
                     <a href="{{route('paie.export')}}" class="btn btn-primary btn-block"><i class="fas fa-download"></i> Télécharger</a>
                  </div>
                  <div class="col-lg-2">
                     <h5 class="pt-2" style="color:black;font-weight:700">Répartition</h5>
                   </div>
                   <div class="col-lg-2">
                      <a href="{{route('paie.repartition')}}" class="btn btn-primary btn-block"><i class="fas fa-download"></i> Télécharger</a>
                   </div>
                   <div class="col-lg-2">
                     <h5 class="pt-2" style="color:black;font-weight:700">Décision</h5>
                   </div>
                   <div class="col-lg-2">
                      <a href="{{route('paie.decision')}}" class="btn btn-primary btn-block"><i class="fas fa-download"></i> Télécharger</a>
                   </div>
               </div>
            </div>
        </div>
        <div class="card shadow mt-3">
              <div class="card-header">
                  La paperasse du chapitre 33-13 article 02
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-lg-2">
                    <h5 class="pt-2" style="color:black;font-weight:600">Engagement 33-13</h5>
                  </div>
                  <div class="col-lg-2">
                    <a href="{{route('paie.engagement', 'Assurance')}}" class="btn btn-primary btn-block"><i class="fas fa-download"></i> Télécharger</a>
                  </div>
                  <div class="col-lg-2">
                    <h5 class="pt-2" style="color:black;font-weight:700">Mondate 33-13</h5>
                  </div>
                  <div class="col-lg-2">
                      <a href="{{route('paie.mondate', 'Assurance')}}" class="btn btn-primary btn-block"> <i class="fas fa-download"></i> Télécharger</a>
                  </div>
                  
                  </div>
                  <div class="row mt-3">
                    <div class="col-lg-2">
                      <h5 class="pt-2" style="color:black;font-weight:700">Bordereau Cnas</h5>
                    </div>
                    <div class="col-lg-2">
                        <a href="{{route('paie.Cnas', 'BORDEREAU')}}" class="btn btn-primary btn-block"><i class="fas fa-download"></i>Télécharger</a>
                    </div>
                    <div class="col-lg-2">
                        <h5 class="pt-2" style="color:black;font-weight:700">Cotisation Cnas</h5>
                      </div>
                      <div class="col-lg-2">
                        <a href="{{route('paie.Cnas', 'COTISATION')}}" class="btn btn-primary btn-block"><i class="fas fa-download"></i>Télécharger</a>
                      </div>
                      <div class="col-lg-2">
                        <h5 class="pt-2" style="color:black;font-weight:700">Avis Virement</h5>
                      </div>
                      <div class="col-lg-2">
                        <a href="{{route('paie.Cnas', 'AVIS')}}" class="btn btn-primary btn-block"><i class="fas fa-download"></i>Télécharger</a>
                      </div>
                  </div>
              </div>
        </div>
        <div class="card shadow mt-3">
                <div class="card-header">
                  La Bordereaux & jounales
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-2">
                        <h5 class="pt-2" style="color:black;font-weight:700">Bordereau CF</h5>
                        </div>
                        <div class="col-lg-2">
                          <a href="{{route('paie.BordereauCf')}}" class="btn btn-primary btn-block"><i class="fas fa-download"></i> Télécharger</a>
                        </div>
                        <div class="col-lg-2">
                          <h5 class="pt-2" style="color:black;font-weight:700">Bordereau CD</h5>
                        </div>
                        <div class="col-lg-2">
                          <a href="{{route('paie.BordereauCD')}}" class="btn btn-primary btn-block"><i class="fas fa-download"></i> Télécharger</a>
                        </div>
                        <div class="col-lg-2">
                          <h5 class="pt-2" style="color:black;font-weight:700">Journal</h5>
                        </div>
                        <div class="col-lg-2">
                          <a href="{{route('paie.Journal')}}" class="btn btn-primary btn-block"><i class="fas fa-download"></i> Télécharger</a>
                        </div>
                    </div>
                 </div>
        </div>
    </div>
@endsection