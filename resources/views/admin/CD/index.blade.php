@extends('layouts.template')

@section('page')
    CD
@endsection

@section('CDsection')
    <div class="container-fluid">
        <h4 style="font-weight: 900;color:darkred" class="mb-3">Les CDs du Paiement</h4>
        <div class="row">
            <div class="col">
                <form action="{{route('cds.CdClassique')}}" method="post">
                    @csrf
                    <input type="hidden" name="moisPaiement" value="{{date('m')}}">
                    <input type="hidden" name="anneePaiement" value="{{date('Y')}}">
                    <div class="card card-default border-primary">
                        <div class="card-header">
                            <h6>CD Classique</h6>
                        </div>
                        <div class="card-body">
                            <input type="submit" value="Télécharger CD Classique" class="btn btn-primary btn-block">
                        </div>
                    </div>
                </form>
            </div>
            <div class="col">
                <form action="{{route('cds.CdMondatement')}}" method="post">
                    @csrf
                    <input type="hidden" name="moisPaiement" value="{{date('m')}}">
                    <input type="hidden" name="anneePaiement" value="{{date('Y')}}">
                    <div class="card card-default border-primary">
                        <div class="card-header">
                            <h6>CD Mondatement</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4">
                                    <input type="number" name="NumeroMondatePaie" id="" class="form-control" placeholder="N° Mondate">
                                </div>
                                <div class="col-lg-8">
                                    <input type="submit" value="Télécharger CD Mondate" class="btn btn-success btn-block">
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </form>
            </div>
            <div class="col">
                <form action="{{route('cds.CdBeneficier')}}" method="post">
                    @csrf
                    <input type="hidden" name="moisPaiement" value="{{date('m')}}">
                    <input type="hidden" name="anneePaiement" value="{{date('Y')}}">
                    <div class="card card-default border-warning">
                        <div class="card-header">
                            <h6>CD Bénéficier</h6>
                        </div>
                        <div class="card-body">
                            <input type="submit" value="Télécharger CD Bénéficier" class="btn btn-warning btn-block">
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- <h4 style="font-weight: 900;color:darkred" class="mb-3 mt-5">Verifier Les CDs du Paiement</h4>
        <div class="row">
            <div class="col">
                <div class="card card-default border-primary">
                    <div class="card-header">
                        <h6>CD Classique</h6>
                    </div>
                    <div class="card-body">
                        
                        <form action="" method="POST">
                            @csrf
                            <input type="file" name="cdClassique" id="" class="form-control" >
                        </form>
                            
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card card-default border-success">
                    <div class="card-header">
                        <h6>CD Mondate</h6>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST">
                            @csrf
                            <input type="file" name="cdClassique" id="" class="form-control" >
                        </form>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card card-default border-warning">
                    <div class="card-header">
                        <h6>CD Mondate</h6>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST">
                            @csrf
                            <input type="file" name="cdClassique" id="" class="form-control" >
                        {{-- </form> --}}
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

@endsection