@extends('layouts.master')


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
    <form action="{{env('APP_URL')}}/add_tab_comparatif" method="post" enctype="multipart/form-data">
        @if(Session::has('success'))
        <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
        @if(Session::has('fail'))
        <div class="alert alert-danger">{{ Session::get('fail') }}</div>
        @endif


                        @csrf
                        <div class="mb-3">
                            <label for="formrow-numero-input" class="form-label">N° table comparatif</label>
                            <input type="text" class="form-control" id="formrow-f-input" name="id" value="{{ $id }}" readonly="true">
                            @foreach ( $id_das as $id_da )
                            <input  class="form-control" id="formrow-f-input" name="id_das[]" value={{ $id_da->id }} hidden>
                            @endforeach




                        </div>



                        <div class="mb-3">
                            <label for="formrow-numero-input" class="form-label">Nombre des fournisseurs</label>
                            <input type="text" class="form-control" id="Nb_ligne_da" name='nb_fournisseur' value="{{old('nb_fournisseur')}}" >

                        </div>



                    <div id="ligne_das">
                        <div  class="accordion">
                            <div class="accordion-item" id="ligne_da">
                                <h2 class="accordion-header" id="1">
                                  <button class="accordion-button fournisseur-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" >
                                    Fournisseur Nº1
                                  </button>
                                </h2>
                            </div>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="mb-3">
                                        <label for="formrow-designation-input" class="form-label">nom fournisseur</label>
                                        <input type="text" class="form-control" id="formrow-fi-input" name="fournisseur[]">
                                        <span class="text-danger">@error('fournisseur.*'){{ $message }}@enderror</span>
                                    </div>
                                    @for($i=0;$i<$count;$i++)
                                    <div  class="accordion">
                                        <div class="accordion-item" id="produit">
                                            <h2 class="accordion-header" id="2">
                                              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                Produit Nº{{$i+1}}  <br><br> designation : {{$id_das[$i]->designation}}
                                              </button>
                                            </h2>
                                        </div>

                                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                    <div class="mb-3">
                                        <label for="formrow-reference-input" class="form-label">Prix</label>
                                        <input type="text" class="form-control" id="formrow-fir-input" name="prix[{{$i}}][]">
                                        <span class="text-danger">@error("prix.*"){{ $message }}@enderror</span>
                                    </div>

                                    <div class="mb-3">
                                        <label for="formrow-reference-input" class="form-label">Remise</label>
                                        <input type="text" class="form-control" id="formrow-fir-input" name="remise[{{$i}}][]">
                                        <span class="text-danger">@error("remise.*"){{ $message }}@enderror</span>
                                    </div>

                                    <div class="mb-3">
                                        <label for="formrow-inputState" class="form-label">devise</label>
                                        <select id="formrow-inputState" class="form-select" name="devise[{{$i}}][]">
                                            <option selected>Choose...</option>
                                            <option value="EUR">EUR</option>
                                            <option value="DH">DH</option>
                                        </select>
                                    </div>
                                            </div>
                                        </div>
                                </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        var initialState = $(document.getElementById("ligne_das")).clone();
                        $('#Nb_ligne_da').change(function () {
                            $(document.getElementById("ligne_das")).replaceWith(initialState);

                            for(let i=0;i<$('#Nb_ligne_da').val()-1;i++){
                            document.getElementById("ligne_das").innerHTML += document.getElementsByClassName("accordion")[0].innerHTML;
                            }

                            $('.accordion-button.fournisseur-button').map((index,e)=> e.textContent = "Fournisseur Nº"+(index+1));


                        })

                        $('#Nb_ligne_da').ready(function () {
                            $(document.getElementById("ligne_das")).replaceWith(initialState);
                            console.log($('#Nb_ligne_da').val());
                            for(let i=0;i<$('#Nb_ligne_da').val()-1;i++){
                            document.getElementById("ligne_das").innerHTML += document.getElementsByClassName("accordion")[0].innerHTML;
                            }

                            $('.accordion-button.fournisseur-button').map((index,e)=> e.textContent = "Fournisseur Nº"+(index+1));


                        })
                    </script>
                        <div class="mt-3">
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
