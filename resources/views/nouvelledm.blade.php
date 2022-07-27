@extends('layouts.master')

@section('title') @lang('translation.Data_Tables') @endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') Tables @endslot
        @slot('title')  @endslot
    @endcomponent
    <?php
   Use App\Http\Controllers\HomeController;
   use App\Models\Da;


  // $im = DB::table('da')->get()->last() ;



   ?>
   <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <form action="{{env('APP_URL')}}/add_dm" method="post" enctype="multipart/form-data">
        @if(Session::has('success'))
        <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
        @if(Session::has('fail'))
        <div class="alert alert-danger">{{ Session::get('fail') }}</div>
        @endif


                        @csrf
                        <div class="mb-3">
                            <label for="formrow-numero-input" class="form-label">Numero Demande</label>
                            <input type="text" class="form-control" id="formrow-f-input"  value="{{ $id }}" readonly="true">

                        </div>

                        <div class="mb-3">
                            <label for="formrow-delai-input" class="form-label">Délai Souhaité</label>
                            <input type="date" class="form-control" id="formrow-firs-input" name="delai">
                            <span class="text-danger">@error("delai"){{ $message }}@enderror</span>
                        </div>

                        <div class="mb-3">
                            <label for="formrow-numero-input" class="form-label">Nombre des lignes de la demande d'achat</label>
                            <input type="text" class="form-control" id="Nb_ligne_da"  value="" >

                        </div>


                    <div id="ligne_das">
                        <div  class="accordion">
                            <div class="accordion-item" id="ligne_da">
                                <h2 class="accordion-header" id="1">
                                  <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Ligne de la demande d'achat Nº1
                                  </button>
                                </h2>
                            </div>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="mb-3">
                                        <label for="formrow-designation-input" class="form-label">designation</label>
                                        <input type="text" class="form-control" id="formrow-fi-input" name="designation[]">

                                    </div>
                                    <div class="mb-3">
                                        <label for="formrow-reference-input" class="form-label">Reference</label>
                                        <input type="text" class="form-control" id="formrow-fir-input" name="reference[]">
                                        <span class="text-danger">@error("reference[]"){{ $message }}@enderror</span>
                                    </div>

                                    <div class="mb-3">
                                        <label for="formrow-quantite-input" class="form-label">Quantité</label>
                                        <input type="number" class="form-control" id="formrow-firs-input" name="quantite[]">
                                        <span class="text-danger">@error("quantite[]"){{ $message }}@enderror</span>
                                    </div>

                                    <div class="row">

                                        <div >
                                            <div class="mb-3">
                                                <label for="formrow-code-input" class="form-label">Code Centre de cout</label>
                                                <input type="text" class="form-control" id="formrow-email-input" name='ccout[]'>
                                                <span class="text-danger">@error('ccout[]'){{ $message }}@enderror</span>
                                            </div>
                                            <div class="mb-3">
                                                <label for="formrow-code-input" class="form-label">Code Nature écono</label>
                                                <input type="text" class="form-control" id="formrow-email-input" name='cnecono[]'>
                                                <span class="text-danger">@error('cnecono[]'){{ $message }}@enderror</span>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="mb-3">
                                        <label for="formrow-code-input" class="form-label">Joindre un fichier</label>
                                        <input type="file" class="form-control"  name='file[]'>
                                        <span class="text-danger">@error('file[]'){{ $message }}@enderror</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        $('#Nb_ligne_da').change(function () {
                            console.log($('#Nb_ligne_da').val());
                            for(let i=0;i<$('#Nb_ligne_da').val()-1;i++){
                            document.getElementById("ligne_das").innerHTML += document.getElementsByClassName("accordion")[0].innerHTML;
                            }
                            $('.accordion-button').map((index,e)=> e.textContent = "Ligne de la demande d'achat Nº"+(index+1));


                        })
                    </script>

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="formrow-inputState" class="form-label">acheteur</label>
                                    <select id="formrow-inputState" class="form-select" name='acheteur'>
                                        <option selected>Choose...</option>

                                               @foreach ($acheteurs as $acheteur )
                                               <option value="{{ $acheteur->id }}">{{ $acheteur->username }}</option>
                                               @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="formrow-inputState" class="form-label">societe</label>
                                    <select id="formrow-inputState" class="form-select" name="societe">
                                        <option selected>Choose...</option>
                                               <option value="COFMA">Coficab maroc</option>
                                               <option value="COFINTER">Coficab international</option>
                                    </select>
                                </div>
                            </div>
                        </div>



                        <div>
                            <div class="mb-3">
                                <label for="formrow-code-input" class="form-label">Nom de fournisseur</label>
                                <input type="text" class="form-control" id="formrow-email-input" name='fournisseur'>
                                <span class="text-danger">@error('fournisseur'){{ $message }}@enderror</span>
                            </div>
                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary w-md" >Valider</button>
                        </div>
                    </form>
@endsection
@section('script')
    <!-- Required datatable js -->
    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/pdfmake/pdfmake.min.js') }}"></script>
    <!-- Datatable init js -->
    <script src="{{ URL::asset('/assets/js/pages/datatables.init.js') }}"></script>

@endsection
