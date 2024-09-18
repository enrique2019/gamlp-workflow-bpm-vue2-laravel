<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <link rel="stylesheet" href="{{ asset('/css/app.css') }}">
  <script>
    window.Laravel = <?php echo json_encode(array(
                        'csrf_token' => csrf_token(),
                        'usr_id' => Auth::user()->id,
                        'usr_name' => Auth::user()->name,
                        'rol_id' => Auth::user()->role_id,
                        'emp_id' => Auth::user()->branch_id
                      )); ?>
  </script>
</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper" id="app">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" collapsed="true" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
        </li>
      </ul>

      <!-- SEARCH FORM -->
      <form class="form-inline ml-3">
        <!--div class="input-group input-group-sm">
          <input class="form-control form-control-navbar" type="search" placeholder="Buscar" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-navbar" type="submit">
              <i class="fa fa-search"></i>
            </button>
          </div>
        </div> &nbsp;-->
        <div class="input-group input-group-sm">
          <input class="form-control form-control-navbar" type="search" value="{{ Auth::user()->name }}" aria-label="Search">
        </div> &nbsp;

      </form>

    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="/home" class="brand-link">
        <img src="{{ asset('/img/logoEmpresa.png') }}" alt="The Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">&nbsp;</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="{{ asset('/img/profile.png') }}" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            <router-link to="/profile" class="nav-link">
              {{ Auth::user()->name }}
            </router-link>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- *** Escritorio *** -->
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-inbox blue"></i>
                <p>
                  Escritorio
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <router-link to="/dashboard" class="nav-link">
                  &nbsp; &nbsp; &nbsp;<i class="nav-icon fas fa-tachometer-alt blue"></i>
                    <p>
                      KPIs
                    </p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/nodosTrabajos" class="nav-link">
                    &nbsp; &nbsp; &nbsp;<i class="nav-icon fas fa-inbox blue"></i>
                    <p>
                      Nodos Trabajos
                    </p>
                  </router-link>
                </li>
              </ul>
            </li>

            <li class="nav-item">
              <router-link to="/crearCaso" class="nav-link">
                <i class="nav-icon fas fa-plus blue"></i>
                <p>
                  Crear Caso
                </p>
              </router-link>
            </li>
            <li class="nav-item">
              <router-link to="/misCasos" class="nav-link">
                <i class="nav-icon fas fa-inbox blue"></i>
                <p>
                  Mis Pendientes
                </p>
              </router-link>
            </li>
            <li class="nav-item">
              <router-link to="/buscarCasos" class="nav-link">
                <i class="nav-icon fas fa-search blue"></i>
                <p>
                  Buscar Casos
                </p>
              </router-link>
            </li>

            <!-- *** Correspondencia *** -->
            <!--li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-inbox blue"></i>
                <p>
                  #CeroPapel
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <router-link to="/miCorrespondencia" class="nav-link">
                    &nbsp; &nbsp; &nbsp;<i class="nav-icon fas fa-inbox blue"></i>
                    <p>
                      Documentos
                    </p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/buscarDocumento" class="nav-link">
                    &nbsp; &nbsp; &nbsp;<i class="nav-icon fas fa-inbox blue"></i>
                    <p>
                      Buscar Archivo
                    </p>
                  </router-link>
                </li>
              </ul>
            </li-->

            @if (Auth::user()->role_id == 1)
            <!-- *** Diseño *** -->
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-cog green"></i>
                <p>
                  Diseño
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <router-link to="/procesos" class="nav-link">
                    &nbsp; &nbsp; &nbsp;<i class="nav-icon far fa-copy green"></i>
                    <p>
                      Procesos
                    </p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/actividades" class="nav-link">
                    &nbsp; &nbsp; &nbsp;<i class="nav-icon fas fa-list green"></i>
                    <p>
                      Actividades
                    </p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/formularios" class="nav-link">
                    &nbsp; &nbsp; &nbsp;<i class="nav-icon far fa-file green"></i>
                    <p>
                      Formularios
                    </p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/impresiones" class="nav-link">
                    &nbsp; &nbsp; &nbsp;<i class="nav-icon fa fa-print green"></i>
                    <p>
                      Impresiones
                    </p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/modeladoFormularios" class="nav-link">
                    &nbsp; &nbsp; &nbsp;<i class="nav-icon fa fa-file green"></i>
                    <p>
                      Diseñador Formularios
                    </p>
                  </router-link>
                </li>
              </ul>
            </li>

            <!-- *** Administracion *** -->
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-cog green"></i>
                <p>
                  Admin. Procesos
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <router-link to="/catalogos" class="nav-link">
                    &nbsp; &nbsp; &nbsp;<i class="nav-icon far fa-circle green"></i>
                    <p>
                      Catálogos
                    </p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/nodos" class="nav-link">
                    &nbsp; &nbsp; &nbsp;<i class="nav-icon far fa-circle green"></i>
                    <p>
                      Nodos
                    </p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/users" class="nav-link">
                    &nbsp; &nbsp; &nbsp;<i class="nav-icon far fa-circle green"></i>
                    <p>
                      Usuarios
                    </p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/usersNodos" class="nav-link">
                    &nbsp; &nbsp; &nbsp;<i class="nav-icon far fa-circle green"></i>
                    <p>
                      Usuarios Nodos
                    </p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/nodosProcesos" class="nav-link">
                    &nbsp; &nbsp; &nbsp;<i class="nav-icon far fa-circle green"></i>
                    <p>
                      Nodos Procesos <small>(Creadores)</small>
                    </p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/" class="nav-link">
                    &nbsp; &nbsp; &nbsp;<i class="nav-icon far fa-circle green"></i>
                    <p>
                      Reasignar Casos
                    </p>
                  </router-link>
                </li>
              </ul>
            </li>


            <!-- *** Administracion *** -->
            <!--li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-cog green"></i>
                <p>
                  Admin. #CeroPapel
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <router-link to="/users" class="nav-link">
                    &nbsp; &nbsp; &nbsp;<i class="nav-icon far fa-circle green"></i>
                    <p>
                      Buscar en Archivo
                    </p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/users" class="nav-link">
                    &nbsp; &nbsp; &nbsp;<i class="nav-icon far fa-circle green"></i>
                    <p>
                      Archivar (Digitalización)
                    </p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/users" class="nav-link">
                    &nbsp; &nbsp; &nbsp;<i class="nav-icon far fa-circle green"></i>
                    <p>
                      Transferir
                    </p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/users" class="nav-link">
                    &nbsp; &nbsp; &nbsp;<i class="nav-icon far fa-circle green"></i>
                    <p>
                      Disposición
                    </p>
                  </router-link>
                </li>
              </ul>
            </li-->

            <!-- *** Parametros *** -->
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-cog orange"></i>
                <p>
                  Parametros Procesos
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <router-link to="/actuaciones" class="nav-link">
                    &nbsp; &nbsp; &nbsp;<i class="nav-icon far fa-circle orange"></i>
                    <p>
                      Actuaciones
                    </p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/tactividades" class="nav-link">
                    &nbsp; &nbsp; &nbsp;<i class="nav-icon far fa-circle orange"></i>
                    <p>
                      Tipos Actividades
                    </p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/tformularios" class="nav-link">
                    &nbsp; &nbsp; &nbsp;<i class="nav-icon far fa-circle orange"></i>
                    <p>
                      Tipos Formularios
                    </p>
                  </router-link>
                </li>
              </ul>
            </li>

            <!--li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-cog orange"></i>
                <p>
                  Parametros Servicios
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <router-link to="/tipows" class="nav-link">
                    &nbsp; &nbsp; &nbsp;<i class="nav-icon far fa-circle orange"></i>
                    <p>
                      Tipos Servicios Web
                    </p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/ws" class="nav-link">
                    &nbsp; &nbsp; &nbsp;<i class="nav-icon far fa-circle orange"></i>
                    <p>
                      Servicios Web
                    </p>
                  </router-link>
                </li>
              </ul>
            </li-->

            <!--li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-cog orange"></i>
                <p>
                  Parametros #CeroPapel
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <router-link to="/archivos" class="nav-link">
                    &nbsp; &nbsp; &nbsp;<i class="nav-icon far fa-circle orange"></i>
                    <p>
                      Archivos
                    </p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/tiposArchivo" class="nav-link">
                    &nbsp; &nbsp; &nbsp;<i class="nav-icon far fa-circle orange"></i>
                    <p>
                      Tipos de Archivo
                    </p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/tiposDoc" class="nav-link">
                    &nbsp; &nbsp; &nbsp;<i class="nav-icon far fa-circle orange"></i>
                    <p>
                      Tipos Documentales
                    </p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/subtiposDoc" class="nav-link">
                    &nbsp; &nbsp; &nbsp;<i class="nav-icon far fa-circle orange"></i>
                    <p>
                      Subtipos Documentales
                    </p>
                  </router-link>
                </li>
              </ul>
            </li-->
            @endif

            <li class="nav-item">
              <a href="#" class="nav-link" onclick="event.preventDefault();
              document.getElementById('logout-form').submit();">
                <i class="nav-icon fas fa-power-off red"></i>
                <p>
                  {{ __('Logout') }}
                </p>
              </a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
              </form>
            </li>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    {{-- Content Wrapper. Contains page content --}}
    <div class="content-wrapper">
      {{-- Main content --}}
      <div class="content">
        <div class="container-fluid">
          <router-view></router-view>
        </div>
      </div>
      {{-- /.content --}}
    </div>
    {{-- /.content-wrapper --}}

    {{-- Main Footer --}}
    <footer class="main-footer">
      {{-- To the right --}}
      <div class="float-right d-none d-sm-inline">
        Municipio 365
      </div>
      {{-- Default to the left --}}
      <strong>Potenciado por <a href="http://xpan-dt.com">xpan-dt.com</a></strong>
    </footer>
  </div>
  {{-- ./wrapper --}}

  <script src="{{ asset('/js/app.js') }}"></script>
</body>

</html>