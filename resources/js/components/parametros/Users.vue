<template>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5>{{ plural }}</h5>
            </div>
            <div class="card-body">
                <table class="table table-hover table-striped table-responsive" id="divTable">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th>
                                <button type="button" class="btn btn-success" @click="doLimpiar()" data-toggle="modal"
                                    data-target="#modalNuevo">
                                    <i class="fa fa-plus white" aria-hidden="true"></i> Nuevo
                                </button>
                            </th>
                            <th scope="col">NAME</th>
                            <th scope="col">EMAIL</th>
                            <th scope="col">ROL</th>
                            <th scope="col">CREADO</th>
                            <th scope="col">MODIFICADO</th>
                            <th scope="col">ESTADO</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(r, index) in registros">
                            <td width="3%" scope="row">{{ r.id }}</td>
                            <td width="15%" scope="row">
                                <button v-if="r.status !== 'X'" type="button" class="btn btn-warning btn-circle" v-on:click="doVer( index )"
                                    title="Editar" data-toggle="modal" data-target="#modalEditar">
                                    <i class="fa fa-pen white" aria-hidden="true"></i>
                                </button>
                                <button v-if="r.status !== 'X'" type="button" class="btn btn-danger btn-circle" v-on:click="doVer( index )"
                                    title="Eliminar" data-toggle="modal" data-target="#modalEliminar">
                                    <i class="fa fa-trash white" aria-hidden="true"></i>
                                </button>
                            </td>
                            <td>{{ r.name }}</td>
                            <td>{{ r.email }}</td>
                            <td>{{ r.description }}</td>
                            <td>{{ r.created_at }}</td>
                            <td>{{ r.updated_at }}</td>
                            <td>
                                <span v-if="r.status === 'A'" class="badge badge-success">ACTIVO</span>
                                <span v-else-if="r.status === 'X'" class="badge badge-danger">ELIMINADO</span>
                                <span v-else class="badge badge-warning">{{ r.status}}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- modalVer
        <div class="modal fade" id="modalVer" tabindex="-1" role="dialog" aria-labelledby="modalVer" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="exampleModalLabel">Ver {{ singular }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-6">
                                <label>Codigo</label>
                                <input v-model="registro.com_nit" class="form-control" placeholder="Código" disabled>
                            </div>
                            <div class="col-md-6">
                                <label>Descripcion</label>
                                <input v-model="registro.com_razon_social" class="form-control" placeholder="Descripción"
                                    disabled>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div> -->

        <!-- modalNuevo -->
        <div class="modal fade" id="modalNuevo" tabindex="-1" role="dialog" aria-labelledby="modalNuevo"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="exampleModalLabel">Nuevo {{ singular }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div v-for="error in errores" class="col-md-3"><span class="badge badge-danger">{{ error
                                    }}</span></div>
                        </div>
                        <div class="row justify-content-left">
                            <div class="col-md-12">
                                <label>Nombres Usuario</label>
                                <input v-model="registro.name" class="form-control" placeholder="Ingrese Name">
                                <p v-if="!registro.name" class="mensaje">Complete</p>
                            </div>
                            <div class="col-md-6">
                                <label>Correo Electrónico</label>
                                <input v-model="registro.email" class="form-control" placeholder="Ingrese Email">
                                <p v-if="!registro.email" class="mensaje">Complete</p>
                            </div>
                            <div class="col-md-6">
                                <label>Clave</label>
                                <input type="password" v-model="registro.password" class="form-control" placeholder="Ingrese Password">
                                <p v-if="!registro.password" class="mensaje">Complete</p>
                            </div>
                            <div class="col-md-6">
                                <label>Branch</label>
                                <input v-model="registro.branch_id" class="form-control" placeholder="Ingrese Branch">
                                <p v-if="!registro.branch_id" class="mensaje">Complete</p>
                            </div>
                            <div class="col-md-6">
                                <label>Rol</label>
                                <select v-model="registro.role_id" class="form-control"
                                    placeholder="Rol">
                                    <option value="-1">-- Seleccione Rol --</option>
                                    <option v-for="s in rol" v-bind:value="s.role_id">
                                        {{ s.role_description}} - {{ s.role_code}}
                                    </option>
                                </select>
								<p v-if="registro.role_id=='-1'" class="mensaje">Seleccione</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" @click="insertarRegistro($event)"
                            data-dismiss="modal">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- modalEditar -->
        <div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="modalEditar"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="exampleModalLabel">Editar {{ singular}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-12">
                                <label>Nombres Usuario</label>
                                <input v-model="registro.name" class="form-control" placeholder="Ingrese Name">
                                <p v-if="!registro.name" class="mensaje">Complete</p>
                            </div>
                            <div class="col-md-6">
                                <label>Correo Electrónico</label>
                                <input v-model="registro.email" class="form-control" placeholder="Ingrese Email">
                                <p v-if="!registro.email" class="mensaje">Complete</p>
                            </div>
                            <div class="col-md-6">
                                <label>Clave</label>
                                <input type="password" v-model="registro.password" class="form-control" placeholder="Ingrese Password">
                                <p v-if="!registro.password" class="mensaje">Complete</p>
                            </div>
                            <div class="col-md-6">
                                <label>Branch</label>
                                <input v-model="registro.branch_id" class="form-control" placeholder="Ingrese Branch">
                                <p v-if="!registro.branch_id" class="mensaje">Complete</p>
                            </div>
                            <div class="col-md-6">
                                <label>Rol</label>
                                <select v-model="registro.role_id" class="form-control"
                                    placeholder="Rol">
                                    <option value="null">-- Seleccione Rol --</option>
                                    <option v-for="s in rol" v-bind:value="s.role_id">
                                        {{ s.role_description}} - {{ s.role_code}}
                                    </option>
                                </select>
								<p v-if="registro.role_id=='-1'" class="mensaje">Seleccione</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" @click="actualizarRegistro($event)"
                            data-dismiss="modal">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- modalEliminar -->
        <div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog" aria-labelledby="modalEliminar"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="exampleModalLabel">Eliminar {{ singular}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-6">
                                <label>¿ Confirma eliminar el {{ singular }} ?</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                        <button type="button" class="btn btn-danger" @click="eliminarRegistro($event)"
                            data-dismiss="modal">Si, eliminar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import datatables from 'datatables';

    export default {
        name: 'users',

        data() {
            return {
                plural: 'Usuarios',
                singular: 'Usuario',
                seleccionado: '',
                errores: [],
                registro: {},
                registros: [],
                rol: [],
                dataTable: null
            }
        },

        mounted() {
            this.listarRegistros();
            this.listarRoles();

            this.dataTable = $('#divTable').DataTable({ responsive: true });
        },

        methods: {
            listarRegistros() {
                let url = "api/users/";
                axios.get(url).then(response => {
                    this.registros = response.data.data; //twice data
                    console.log(this.registros);
                });
            },

            listarRoles() {
				let url = "api/roles/";
				axios.get(url).then(response => {
					this.rol = response.data.data; //twice data
				});
			},

            doVer(index) {
                this.registro = this.registros[index];
            },

            doLimpiar() {
                this.registro = {
                    name: '',
                    email: '',
                    password: '',
                    role_id: '-1',
                    emp_id: '-1'
                };
            },

            insertarRegistro(e) {
                this.errores = [];

                if (this.registro && !this.registro.name) {
                    this.errores.push('Falta Nombres de Usuario');
                    console.log("error nombreds");
                }
                if (this.registro && !this.registro.email) {
                    this.errores.push('Falta Email');
                    console.log("error email");
                }
                if (this.registro && !this.registro.password) {
                    this.errores.push('Falta Clave');
                    console.log("error password");
                }
                if (this.registro && !this.registro.branch_id) {
                    this.errores.push('Falta Branch');
                    console.log("error branch");
                }
                /*if (this.registro.branch_id == '-1') {
                    this.errores.push('Falta Branch');
                }*/
                if (this.registro.role_id == '-1') {
                    this.errores.push('Falta Rol');
                }

                console.log("Errores >>> ", this.errores);

                if (this.errores.length === 0) {
                    let gRegistro = this.registro;
                    //gRegistro.com_tcom_id = this.seleccionado;
                    //gRegistro.srv_usr_id = 1;
                    //gRegistro.com_data = JSON.stringify(gRegistro.com_data);
                    let that = this;
                    let url = "api/users";
                    axios.post(url, gRegistro)
                        .then(function (response) {
                            that.output = response.data;
                            that.listarRegistros();
                        })
                        .catch(function (error) {
                            that.output = error;
                        });
                } else {
                    e.preventDefault();
                    this.$swal({
                        title: 'Error!',
                        text: 'Corrija errores',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                }
            },

            actualizarRegistro(e) {
                this.errores = [];
                
                if (this.registro && !this.registro.name) {
                    this.errores.push('Falta Nombres de Usuario');
                    console.log("error nombreds");
                }
                if (this.registro && !this.registro.email) {
                    this.errores.push('Falta Email');
                    console.log("error email");
                }
                if (this.registro && !this.registro.password) {
                    this.errores.push('Falta Clave');
                    console.log("error password");
                }
                if (this.registro && !this.registro.branch_id) {
                    this.errores.push('Falta Branch');
                    console.log("error branch");
                }
                /*if (this.registro.branch_id == '-1') {
                    this.errores.push('Falta Branch');
                }*/
                if (this.registro.role_id == '-1') {
                    this.errores.push('Falta Rol');
                }

                console.log("Errores >>> ", this.errores);


                if (this.errores.length === 0) {
                    let gRegistro = this.registro;
                    //gRegistro.srv_usr_id = 1;
                    //gRegistro.com_data = JSON.stringify(gRegistro.com_data);
                    let that = this;
                    let url = "api/users/" + gRegistro.id;
                    axios.put(url, gRegistro)
                        .then(function (response) {
                            that.output = response.data;
                            that.listarRegistros();
                        })
                        .catch(function (error) {
                            that.output = error;
                        });
                } else {
                    e.preventDefault();
                    this.$swal({
                        title: 'Error!',
                        text: 'Corrija errores',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                }
            },

            eliminarRegistro() {
                let gRegistro = this.registro;
                //gRegistro.com_usr_id = 1;

                let that = this;
                let url = "api/users/" + gRegistro.id;
                axios.post(url, gRegistro)
                    .then(function (response) {
                        that.output = response.data;
                        that.listarRegistros();
                    })
                    .catch(function (error) {
                        that.output = error;
                    });
            },

            doTable() {
                $(function () {
                    $('#divTable').DataTable({});
                });
            },
        },

        beforeUpdate: function () {
            if (this.dataTable) {
                this.dataTable.destroy()
            }
        },

        updated: function () {
            this.dataTable = $("#divTable").DataTable({ 
				responsive: true, 
				order: [],
				"language": {
					"lengthMenu": "Desplegar _MENU_ registros por página",
					"zeroRecords": "Sin registros",
					"info": "Página _PAGE_ de _PAGES_",
					"infoEmpty": "No existen registros disponibles",
					"infoFiltered": "(filtrados de _MAX_ registros en total)",

				    "search": "Buscar",
					"paginate": {
    				    "first":      "Primera",
    				    "last":       "Última",
    				    "next":       "Siguiente",
    				    "previous":   "Próxima"
    				},

				}
			 })

        }

    }
</script>

<style>
    .btn-circle {
        width: 30px;
        height: 30px;
        padding: 6px 0px;
        border-radius: 15px;
        text-align: center;
        font-size: 12px;
        line-height: 1.42857;
    }

    .combo {
        font-size: 10px;
        height: 25px !important;
    }
</style>