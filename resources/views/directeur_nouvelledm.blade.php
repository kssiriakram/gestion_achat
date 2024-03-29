
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
   use Carbon\Carbon;


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
                        <input type="text" name="id" value="{{ $dm[0]->id }}" hidden>
                        <div class="mb-3">
                            <label for="formrow-numero-input" class="form-label">Numero Demande : {{ $dm[0]->id }}</label>
                        </div>

                        <div class="mb-3">
                            <label for="formrow-numero-input" class="form-label">Date de validation du manager : {{ Carbon::parse($dm[0]->date_chef_service)->format('Y-d-m H:i:s')}}</label>
                        </div>

                        <div class="mb-3">
                            @if($dm[0]->delai)
                            <label for="formrow-numero-input" class="form-label">Delai souhaite : {{ $dm[0]->delai}}</label>
                            @else
                            <label for="formrow-numero-input" class="form-label">Delai souhaite : non determine</label>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="formrow-numero-input" class="form-label">Emetteur : {{ $emetteur->username}}</label>
                        </div>

                        <div class="mb-3">
                            <label for="formrow-numero-input" class="form-label">Manager : {{ $manager->username }}</label>
                        </div>

                        <div class="mb-3">
                            <label for="formrow-numero-input" class="form-label">Acheteur : {{ $acheteurs->username }}</label>
                        </div>

                        <div class="mb-3">
                            <label for="formrow-numero-input" class="form-label">Observation de manager : {{ $dm[0]->commentaire_manager }}</label>
                        </div>




                        <div class="card">
                            <div class="card-body">

                                <h4 class="card-title">Les detailles de la demande</h4>
                                <p class="card-title-desc">
                                </p>

                                <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>

                                            <th>Designation</th>
                                            <th>Quantité</th>
                                            <th>Référence</th>
                                            <th>Code Budget</th>
                                            <th>Code Article</th>

                                            <th>Afficher le fichier</th>

                                            <th>fournisseur souhaite</th>


                                        </tr>
                                    </thead>


                                    <tbody>
                                     @foreach ($dm as $dm_ligne )
                                     <tr>

                                        <td>{{ $dm_ligne->designation }}</td>
                                        <td>{{ $dm_ligne->qte }}</td>
                                        <td>{{ $dm_ligne->reference }}</td>
                                        @if($dm_ligne->code_CC)
                                            <td>{{ $dm_ligne->code_CC }}</td>
                                            @else
                                            <td>non determine</td>
                                            @endif

                                        <td>{{ $dm_ligne->code_NE }}</td>

                                        @if($dm_ligne->file)
                                        <td><a  class="form-control"   href={{ asset("uploads/".$dm_ligne->file) }}> cliquez ici </a></td>
                                        @else
                                        <td>Aucun fichier</td>
                                        @endif

                                        

                                        @if($dm_ligne->fournisseur)
                                            <td>{{ $dm_ligne->fournisseur }}</td>
                                            @else
                                            <td>non determine</td>
                                            @endif

                                    </tr>
                                     @endforeach


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
    <script >$(document).ready(function() {
        console.log("hyize gu");

         var table = $('#datatable-buttons').DataTable({
             lengthChange: false,


             buttons: [  'copy', 'excel',{

                     extend: 'pdfHtml5',
                     orientation: 'landscape',
                     pageSize: 'LEGAL',
                     title: "DEMANDE D'ACHAT",
                     customize:  function (doc) {
                        for (var row = 1; row < doc.content[1].table.body.length; row++) {
                console.log(@json($dm));

                doc.content[1].table.body[row][7] = {text: 'Cliquez ici', link: '{{ env('APP_URL') }}'+"/uploads/"+@json($dm)[row-1].file ,style: "tableBodyOdd"};
              }

                         doc.content[0].text="DEMANDE D'ACHAT";
                         doc.layout = 'lightHorizotalLines;'
                        doc.content[1].table.widths=[{width: 'auto', _minWidth: 51.052734375, _maxWidth: 97.001953125, _calcWidth: 51.052734375},
                                             {width: '35%', _minWidth: 676.1240234375, _maxWidth: 676.1240234375, _calcWidth: 676.1240234375},
                                              {width: 'auto', _minWidth: 45.568359375, _maxWidth: 45.568359375, _calcWidth: 45.568359375},
                                             {width: 'auto', _minWidth: 54.43359375, _maxWidth: 54.43359375, _calcWidth: 54.43359375},
                                              {width: 'auto', _minWidth: 44.193359375, _maxWidth: 109.20703125, _calcWidth: 44.193359375},
                                              {width: 'auto', _minWidth: 50.37548828125, _maxWidth: 102.99609375, _calcWidth: 50.37548828125},
                                              {width: 'auto', _minWidth: 51.943359375, _maxWidth: 96.873046875, _calcWidth: 51.943359375},
                                              {width: 'auto', _minWidth: 42.29296875,_maxWidth: 91.41796875,_calcWidth: 42.29296875},
                                              {width: 'auto', _minWidth: 42.29296875,_maxWidth: 91.41796875,_calcWidth: 42.29296875},
                                              {width: 'auto', _minWidth: 42.29296875,_maxWidth: 91.41796875,_calcWidth: 42.29296875},
                                              {width: 'auto', _minWidth: 42.29296875,_maxWidth: 91.41796875,_calcWidth: 42.29296875}],
                         doc.pageMargins = [30, 30, 30, 30];
                         doc.defaultStyle.fontSize = 11;
                         doc.styles.tableHeader.fontSize = 12;
                         doc.styles.title.fontSize = 14;
                 }}
                 , 'colvis']
         });

         table.buttons().container()
             .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');

         $(".dataTables_length select").addClass('form-select form-select-sm');
     });
     </script>
@endsection
