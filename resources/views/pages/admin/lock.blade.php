@extends('layouts.dashboard')
@section('title', 'LockSEK')
@section('content')



  <div class="row">
    <div class="col-lg-8">
      <div class="panel panel-default">
        <div class="panel-heading">
          <i class="fa fa-bar-chart-o fa-fw"></i> Cerradura
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
          <div class="col-sm-3"><!--left col-->


            <div class="text-center">
              <img src="https://cdn.website.thryv.com/716ee54454d94272ba5bf64e492f084d/MOBILE/png/961.png" class="avatar img-thumbnail" alt="avatar">
              <div class="prf-img-inp config">
                <input type="file" class="text-center center-block file-upload">
              </div>

            </div></hr><br>

          </div><!--/col-3-->
          <div class="col-sm-9">
            <div class="tab-content">
              <hr>
              @csrf
              <div class="form-group">

                <div class="col-xs-6">
                  <label for="first_name"><h4>Nombre de la cerradura</h4></label>
                  <p>{{$lock->name}}</p>
                </div>
                <div class="col-xs-6">
                  <label for="first_name"><h4>Dueño</h4></label>
                  <p>{{$lock->user->name}}</p>
                </div>
                <div class="col-xs-6">
                  <label for="first_name"><h4>Numero de serie</h4></label>
                  <p>{{$lock->serial_n}}</p>
                </div>
              </div>
              <div class="col-xs-6">
                <label for="first_name"><h4>Fecha de Registro</h4></label>
                <p>{{$lock->created_at}}</p>
              </div>
              <hr>
            </div>
          </div><!--/col-9-->
        </div>
        <!-- /.panel-body -->
      </div>
      <!-- /.panel -->
      <div class="panel panel-default">
        <div class="panel-heading">
          <i class="fa fa-bar-chart-o fa-fw"></i> Permisos
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
          <div class="col-lg-6">
            <div class="table-responsive">
              <table class="table table-bordered table-hover table-striped">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Usuario</th>
                    <th>Fecha permiso</th>
                    <th>Permiso</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($lock->privileges as $privilege)
                    <tr>
                      <td>{{$privilege->id}}</td>
                      <td>{{$privilege->name}}</td>
                      <td>{{$privilege->created_at}}</td>
                      <td>{{$privilege->privilege}}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <!-- /.table-responsive -->
          </div>
        </div>
        <div class="panel-body">
          <div class="col-lg-6">
            
            <!-- /.table-responsive -->
          </div>
        </div>
        <!-- /.panel-body -->
      </div>
    </div>
    <!-- /.col-lg-8 -->

    <div class="col-lg-4">
      <div class="panel panel-default">
        <div class="panel-heading">
          <i class="fa fa-bell fa-fw"></i> Notifications Panel
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
          <div class="list-group">
            <a href="#" class="list-group-item list-group-item-success">
              <i class="fa fa-unlock fa-fw"></i> <span class="negrita">Asier</span> apertura de cerradura
              <span class="pull-right text-muted small"><em>4 minutes ago</em>
              </span>
            </a>

          </div>
          <!-- /.list-group -->
          <a href="#" class="btn btn-default btn-block">View All Alerts</a>
        </div>
        <!-- /.panel-body -->
      </div>
      <!-- /.panel -->


      <!-- /.panel .chat-panel -->
    </div>
    <!-- /.col-lg-4 -->
  </div>



@stop