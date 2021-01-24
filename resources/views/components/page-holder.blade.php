<div>

    @section('page')
    <span class="text-dark"><i class="{{$icon}}"></i> </span> {{$title}}
    @endsection


    @section('listAttestation')
        <div class="container-fluid">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Listes des Handicapées</h1>
                {{$topAction ?? ''}}
          </div>
          <div class="card shadow mb-4">
              <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Handicapées</h6>
              </div>
              <div class="card-body">
                    {{$slot}}
            </div>
        </div>        
    </div>
    @endsection
</div>