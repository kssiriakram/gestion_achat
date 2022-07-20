
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
    <form action="{{env('APP_URL')}}/manager_add_dm" method="post">
        @if(Session::has('success'))
        <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
        @if(Session::has('fail'))
        <div class="alert alert-danger">{{ Session::get('fail') }}</div>
        @endif


                        @csrf

                        <div class="mb-3">
                            <label for="formrow-numero-input" class="form-label">Numero Demande : {{ $dm->id }}</label>
                        </div>

                        <div class="mb-3">
                            <label for="formrow-numero-input" class="form-label">Date d'emission : {{ $dm->date_emetteur}}</label>
                        </div>

                        <div class="mb-3">
                            <label for="formrow-numero-input" class="form-label">Emetteur : {{ $emetteur->username}}</label>
                        </div>




                        <div class="card">
                            <div class="card-body">

                                <h4 class="card-title">Les detailles de la demande</h4>
                                <p class="card-title-desc">
                                </p>

                                <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>Numéro Demande</th>
                                            <th>Designation</th>
                                            <th>Quantité</th>
                                            <th>Référence</th>
                                            <th>Code Centre de cout</th>
                                            <th>Code Nature écono</th>
                                            <th>Nom de l'acheteur</th>
                                            <th>Afficher le fichier</th>


                                        </tr>
                                    </thead>


                                    <tbody>

                                        <tr>
                                            <td>{{ $dm->id }}</td>
                                            <td>{{ $dm->designation }}</td>
                                            <td>{{ $dm->qte }}</td>
                                            <td>{{ $dm->reference }}</td>
                                            <td>{{ $dm->code_CC }}</td>
                                            <td>{{ $dm->code_NE }}</td>
                                            <td>{{ $acheteurs->username }}</td>
                                            <td><a  class="form-control"   href={{ asset("uploads/".$dm->file) }}> cliquez ici </a></td>

                                        </tr>

                                    </tbody>
                                </table>
            <br/>
            <br/>

            <br/>
            <br/>
            <br/>

            <br/>
            <br/>
            <br/>

                                    </tbody>
                                </table>
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
