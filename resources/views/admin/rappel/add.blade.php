@extends('layouts.template')

@section('addOrEditRappel')

    <div class="container-fluid">

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">{{isset($rappel) ? 'Mise a Jour Les Informations Du rappel' : 'Ajouter un rappel'}} </h1>
         </div>

        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>N°</th>
                          <th>Nom & Prenom</th>
                          <th>Date Naissance</th>
                          <th>CCP</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($hands as $n => $hand)
                          <tr>
                            <td>{{$n=$n+1}}</td>
                            <td>{{$hand->nameFr ?? ''}}</td>
                            <td>{{date('d/m/Y', strtotime($hand->dob) ?? '')}}</td>
                            <td>{{isset($hand->paieinformation->CCP) ? $hand->paieinformation->CCP : ''}}</td> 
                            <td>
                              <ul class="nav ">
                                   <div class="d-flex">
                                        <button class="btn btn-success btn-block" onclick="addRappel({{$hand->id}})" > Ajouter Rappel</button>
                                    </div>
                              </ul>
                            </td>
                          </tr>
                        @endforeach
                      </tbody> 
                    </table>
                  </div>
            </div>
        </div>
    </div>
    

<form action="{{route('rappel.store')}}" method="POST" id="AddRappelForm">

    @csrf

        <div class="modal fade" id="AddRappel" tabindex="-1" role="dialog" aria-labelledby="AddRappelTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="AddRappelTitle">Ajouter Rappel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <div class="row mt-1">
                            <div class="col">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Date Debut</label>
                                    <input type="date" class="form-control" required id="name" name="dateDebut" placeholder="Date Debut Rappel ..." value="{{isset($rappel) ? $rappel->DateDebut : ''}}">
                                  </div>
                             </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="" class="font-weight-bold">Date Fin</label>
                                    <input type="date" class="form-control" id="" name="dateFin" placeholder="Date Fin Rappel ..." value="{{ isset($rappel) ? $rappel->DateFin : '' }}">
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="hand_id" value="" name="hand_id">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulé</button>
                            <button type="submit" class="btn btn-danger">Ajouter</button>
                        </div>
                </div>
            </div>
        </div>

      </form>

@endsection