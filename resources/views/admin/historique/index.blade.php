@extends('layouts.template')


@section('history')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800">Listes des Handicapées mondate</h1>
          <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-file-excel"></i> <i class="fas fa-download fa-sm text-white-50"></i> Liste Hand Mondate</a>
        </div>

 <!-- DataTales Example -->
 <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Handicapées</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>Nom & Prenom</th>
              <th>Date Naissance</th>
              <th>Nature</th>
              <th>CCP</th>
              <th>Statut</th>
              <th>Historique</th>
              <th>Historique</th>
              
            </tr>
          </thead>
          <tbody>
            @foreach ($hands as $hand)
              <tr>
                <td>{{$hand->nameFr}}</td>
                <td>{{date('d/m/Y', strtotime($hand->dob))}}</td>
                <td>{{$hand->cartehand->natureHandFr}}</td>
                <td>{{$hand->paieinformation->CCP}}</td>
                <td>
                  <a href="{{$hand->status->status != 'En cours' ? route('hand.suspendu', $hand->id) : '#'}}">
                    {{$hand->status->status}}
                  </a>
                </td>
               <td>
                  <a class="btn btn-link" href="{{route('historique.MoisPaiements', $hand->id)}}" style="font-size: 1.4rem" style="font-size: 1.4rem; text-decoration:none"> 
                  <span style="font-size: 1rem; font-weight:700; text-decoration:none; color:rgb(61, 9, 204)">Paiement</span></a>
               </td>
               <td>
                  <a class="btn btn-link" href="" style="font-size: 1.4rem; text-decoration:none"> 
                  <span style="font-size: 1rem; font-weight:700; text-decoration:none; color:rgb(241, 10, 10)">Suspension</span> </a>
               </td>
              </tr>
            @endforeach
            
          </tbody>
        </table>
      </div>
    </div>
  </div>        

      </div>
      
@endsection