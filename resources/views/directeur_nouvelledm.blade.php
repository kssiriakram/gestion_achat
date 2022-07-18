
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
    <form action="{{env('APP_URL')}}/directeur_add_dm" method="post">
        @if(Session::has('success'))
        <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
        @if(Session::has('fail'))
        <div class="alert alert-danger">{{ Session::get('fail') }}</div>
        @endif


                        @csrf

                        <div class="mb-3">
                            <label for="formrow-numero-input" class="form-label">Numero Demande</label>
                            <input type="text" class="form-control" id="formrow-f-input" name="id" value="{{ $dm->id }}" readonly="true">

                        </div>


                        <div class="mb-3">
                            <label for="formrow-designation-input" class="form-label">designation</label>
                            <input type="text" class="form-control" id="formrow-fi-input" name="designation" value="designation">

                        </div>
                        <div class="mb-3">
                            <label for="formrow-reference-input" class="form-label">Reference</label>
                            <input type="text" class="form-control" id="formrow-fir-input" name="reference" value="{{ $dm->reference }}">
                            <span class="text-danger">@error("reference"){{ $message }}@enderror</span>
                        </div>

                        <div class="mb-3">
                            <label for="formrow-quantite-input" class="form-label">Quantité</label>
                            <input type="number" class="form-control" id="formrow-firs-input" name="quantite" value="{{ $dm->qte }}">
                            <span class="text-danger">@error("quantite"){{ $message }}@enderror</span>
                        </div>
                        <div class="mb-3">
                            <label for="formrow-delai-input" class="form-label">Délai Souhaité</label>
                            <input type="date" class="form-control" id="formrow-firs-input" name="delai" value="{{ $dm->delai }}">
                            <span class="text-danger">@error("delai"){{ $message }}@enderror</span>
                        </div>
                        <div class="row">

                            <div >
                                <div class="mb-3">
                                    <label for="formrow-code-input" class="form-label">Code Centre de cout</label>
                                    <input type="text" class="form-control" id="formrow-email-input" name='ccout' value="{{ $dm->code_CC }}">
                                    <span class="text-danger">@error('ccout'){{ $message }}@enderror</span>
                                </div>

                                <div class="mb-3">
                                    <label for="formrow-code-input" class="form-label">Code Nature écono</label>

                                    <input type="text" class="form-control" id="formrow-email-input" name='cnecono' value="{{ $dm->code_NE }}">
                                    <span class="text-danger">@error('cnecono'){{ $message }}@enderror</span>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="formrow-inputState" class="form-label">acheteur</label>
                                    <select id="formrow-inputState" class="form-select" name='acheteur'>



                                               <option value="{{ $acheteurs->id }}">{{ $acheteurs->username }}</option>

                                    </select>
                                </div>
                            </div>
                        </div>
                        <!--
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="formrow-inputState" class="form-label">societe</label>
                                    <select id="formrow-inputState" class="form-select" name="societe">
                                        <option selected>Choose...</option>

                                               <option value=""></option>

                                    </select>
                                </div>
                            </div>
                        </div>-->

                        <div>
                            <div class="mb-3">
                                <label for="formrow-code-input" class="form-label">Observation</label>
                                <textarea type="text" class="form-control" id="formrow-email-input" name='observation'></textarea>
                                <span class="text-danger">@error('observation'){{ $message }}@enderror</span>
                            </div>
                        </div>

                        <div>
                        <label>validation</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="validation" value="yes" id="flexCheckDefault" checked>
                            <label class="form-check-label" for="flexCheckDefault">
                              Valider
                            </label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="radio"  name="validation" value="no"   id="flexCheckDefault">
                            <label class="form-check-label" for="flexCheckDefault">
                              Refuser
                            </label>
                          </div>
                        </div>



                        <div>
                            <button type="submit" class="btn btn-primary w-md" >Valider</button>
                        </div>
                    </form>
@endsection
@section('script')
    <!-- Required datatable js-->
    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/pdfmake/pdfmake.min.js') }}"></script>
    <!-- Datatable init js-->
    <script src="{{ URL::asset('/assets/js/pages/datatables.init.js') }}"></script>

@endsection
