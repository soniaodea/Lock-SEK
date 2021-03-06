@extends('layouts.userDashboard')
@section('title', 'LockSEK')
@section('css')
  <link href="{{asset('assets/user/plugins/bootstrap-select/css/bootstrap-select.css')}}" rel="stylesheet">
  <link rel="stylesheet" href="{{asset('assets/js/leaflet/leaflet.css')}}" />
  <link rel="stylesheet" href="{{asset('assets/css/map-loc.css')}}"/>

@stop
@section('scriptsTop')
  <script src="{{asset('assets/js/leaflet/leaflet.js')}}"></script>
  <script src="{{asset('assets/js/esri-leaflet.js')}}"></script>
  <script src="{{asset('assets/js/jquery.min.js')}}"></script>
  <script src="{{asset('assets/js/map-search.js')}}"></script>
@stop
@section('content')

  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Nueva Cerradura</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{route('locks.create')}}" method="post">
          @csrf
          <div class="modal-body">
            <div class="form-group row">
              <label for="colFormLabelSm" class="col-sm-2 col-form-label col-form-label-sm">Nombre</label>
              <div class="col-sm-10">
                <input type="text" name="lockName" class="form-control form-control-sm" id="lockName" value="{{ old('lockName') }}" placeholder="nombre">
              </div>
            </div>
            <div class="form-group row">
              <label for="colFormLabel" class="col-sm-2 col-form-label">Numero de serie</label>
              <div class="col-sm-10">
                <input type="text" name="lockSerial" class="form-control" id="lockSerial" value="{{ old('lockSerial') }}" placeholder="Serial">
              </div>
            </div>
            <div class="form-group row">
              <label for="colFormLabel" class="col-sm-2 col-form-label">Dirección</label>
              <div class="switch">
                <label>NO<input name="checkAddress" type="checkbox" value="checked" id="yespapa" checked><span class="lever"></span>SI</label>
              </div>

              <div class="col-sm-10">
                <div id="mapid"></div>
                <input type="text" id="latitude" name="latitude" value="" hidden>
                <input type="text" id="longitude" name="longitude" value="" hidden>

              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary" >Registrar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- /Modal -->



  <div class="card text-center">
    <div class="header font-25">
      <p>
        Tutorial de como registrar una cerradura
      </p>
    </div>
    <div class="body text-left">
      <ol class="font-18">
        <li>Tienes que <b>adquirir</b> tu <b>cerradura electronica</b> y tenerla <b>cerca</b>.</li>
        <li>Abre el producto y busca el <b>numero de serie</b>.</li>
        <li>El <b>numero de serie</b> contiene 15 caracteres, ubiquelo, le hara falta para completar el <b>registro</b>.</li>
        <li>Pulsa el boton de <b>Empezar!</b> e <b>introduce</b> el nombre de la cerradura y el <b>numero de serie</b>.</li>
        <img src="{{asset('assets/img/serialnumber.png')}}" width="400" alt="numero se serie de ejemplo">
        <li>Añadale una <b>ubicacion</b> si desea.</li>
      </ol>
    </div>
    <div class="card-header">
      <br>
      <h1>Registra tu cerradura</h1>
    </div>
    <div class="card-body">
      <p class="card-text">Para completar el registro de tu cerradura asegurate de tener cerca la cerradura, la necesitaras durante el proceso.</p>
      @if (Auth::user()->roleId == 4 && $locks >= 3)
        <h2>ACTUALMENTE NO ERES UN USUARIO PREMIUM</h2>
        <h2>Los usuarios premium pueden crear mas de 3 cerraduras</h2>
      @else
        <button type="button" class="btn btn-primary" id="begin" data-toggle="modal" data-target="#exampleModal">
          Empezar!
        </button>
      @endif



    </div>
    <br>
  </div>
  @if ($errors->any())
    <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <script src="{{asset('assets/js/map.js')}}"></script>


@stop
