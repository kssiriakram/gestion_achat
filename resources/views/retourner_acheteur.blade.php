
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



  <form action="{{env('APP_URL')}}/acheteur_edit_dm" method="post" enctype="multipart/form-data" >
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
                            <label for="formrow-numero-input" class="form-label">Date de validation du directeur : {{ Carbon::parse($dm[0]->date_directeur)->format('Y-d-m H:i:s')}}</label>
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

                        <div class="mb-3">
                            <label for="formrow-numero-input" class="form-label">Observation du directeur : {{ $dm[0]->commentaire_directeur }}</label>
                        </div>

                        <div class="mb-3">
                            <label for="formrow-numero-input" class="form-label">Motif du retour : {{ $dm[0]->commentaire_acheteur }}</label>
                        </div>




                        <div class="card">
                            <div class="card-body">

                                <h4 class="card-title">Les detailles de la demande</h4>
                                <p class="card-title-desc">
                                </p>

                                <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">

                                    <thead>
                                        <tr>
                                            <th>N° DA</th>
                                            <th style="width:200px;">Designation</th>
                                            <th>Quantité</th>
                                            <th>Référence</th>
                                            <th>Code Budget</th>
                                            <th>Code Article</th>
                                            <th>Nom de l'acheteur</th>
                                            <th style="width:1000px;">Jointure</th>
                                           <th>Observation du manager</th>
                                            <th>Observation du directeur</th>
                                            <th>Motif du retour</th>



                                        </tr>
                                    </thead>


                                    <tbody>
                                    @foreach ($dm as $dm_ligne )


                                        <tr>
                                            <td>
                                                <input type="text" class="form-control" id="formrow-f-input"  value="{{ $dm_ligne->id }}" readonly="true">
                                            </td>

                                            <td>
                                                <input type="text" class="form-control" id="formrow-fi-input" name="designation[]" value="{{ $dm_ligne->designation }}">
                                                <span class="text-danger">@error('designation.*'){{ $message }}@enderror</span>
                                            </td>

                                            <td>
                                                <input type="number" class="form-control" id="formrow-firs-input" name="quantite[]" value="{{ $dm_ligne->qte }}">
                                                <span class="text-danger">@error("quantite.*"){{ $message }}@enderror</span>
                                            </td>

                                            <td>
                                                <input type="text" class="form-control" id="formrow-fir-input" name="reference[]" value="{{ $dm_ligne->reference }}">
                                                 <span class="text-danger">@error("reference.*"){{ $message }}@enderror</span>
                                            </td>


                                            <td>
                                                <input type="text" class="form-control" id="formrow-email-input" name='ccout[]' value="{{ $dm_ligne->code_CC }}">
                                            </td>


                                            <td>
                                                 <input type="text" class="form-control" id="formrow-email-input" name='cnecono[]' value="{{ $dm_ligne->code_NE }}">
                                                <span class="text-danger">@error('cnecono.*'){{ $message }}@enderror</span>
                                            </td>

                                            <td>{{ $acheteurs->username }}</td>

                                            <td>
                                                <input style="width:1000px;" type="file" class="form-control"  name='file[]'>
                                            </td>

                                            <td>{{ $dm_ligne->commentaire_manager }}</td>
                                            <td>{{ $dm_ligne->commentaire_directeur }}</td>
                                            <td>{{ $dm_ligne->commentaire_acheteur }}</td>


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



                        <div>
                            <div class="mb-3">
                                <label for="formrow-code-input" class="form-label">fournisseur souhaite</label>
                                <input type="text" class="form-control" id="formrow-email-input" name='fournisseur' value="{{$dm[0]->fournisseur}}">
                                <span class="text-danger">@error('fournisseur'){{ $message }}@enderror</span>
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
<script >$(document).ready(function() {


    var table = $('#datatable-buttons').DataTable({
        lengthChange: false,


        buttons: [  'copy', 'excel',{

                extend: 'pdfHtml5',
                title: "DEMANDE D'ACHAT",
                orientation: 'landscape',
                pageSize: 'LEGAL',
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
<!-- Datatable init js-->


@endsection
