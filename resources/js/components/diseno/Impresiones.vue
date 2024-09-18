<template>
	<div class="container-fluid">
		<div class="card">
			<div class="card-header">
				<h5>{{ plural }}</h5>
				<div class="row col-md-12">
					<div class="col-6">
						Catalogo:
						<select v-model="seleccionado" class="form-control mt3" @change="listarProcesos(); seleccionado2 = ''; seleccionado3 = ''; procesos = []; actividades = []; registros = [];" size="10">
							<option value="-1" disabled>-- Seleccione Catalogo --</option>
							<option v-for="item in catalogos" :value="item.cat_id">
								<span v-for="index in item.cat_codigo.length" :key="index">&nbsp;&nbsp;</span>
								[ {{ item.cat_codigo}} ] {{ item.cat_descripcion}}
							</option>
						</select>
					</div>
					<div class="col-6">
						Proceso:
						<select v-model="seleccionado2" class="form-control mt3" @change="listarActividades(); seleccionado3 = ''; actividades = []; registros = [];" size="10">
							<option value="-1" disabled>-- Seleccione Proceso --</option>
							<option v-for="item in procesos" :value="item.prc_id">
								{{ item.prc_data.prc_descripcion}} ( {{ item.prc_data.prc_codigo}} )</option>
						</select>
					</div>
				</div>
			</div>
			<div class="card-body">
				<div class="row col-md-12">
					<!-- col izquierda -->
					<div class="col-md-6">
						Actividades:
						<select v-model="seleccionado3" class="form-control mt3" @change="listarRegistros()" size="20">
							<option value="-1" disabled>-- Seleccione Actividad --</option>
							<!--option v-for="(item, index) in actividades" :value="item.act_id" @click="validarTipoActividad(index)"-->
							<option v-for="(item, index) in actividades" :value="item.act_id" :disabled="item.act_tact_id==1 || item.act_tact_id==4">
								[ {{ item.act_data.act_orden}} ] {{ item.act_data.act_descripcion}} - ({{ item.tact_descripcion }})
							</option>
						</select>
					</div>
					<!-- col derecha -->
					<div class="col-md-6">
						{{ plural }}:
						<table class="table table-hover table-striped table-responsive" id="divTable">
							<thead class="thead-dark">
								<tr>
									<th scope="col">#</th>
									<th>
										<button v-if="seleccionado3 && verNuevo" class="btn btn-success btn-circle" size="sm" @click="doLimpiar()" data-toggle="modal" data-target="#modal" title="Nuevo">
											<i class="fa fa-plus white" aria-hidden="true"></i>
										</button>
									</th>
									<th scope="col">NOMBRE</th>
									<th scope="col">ESTADO</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(r, index) in registros">
									<td width="3%" scope="row">{{ r.imp_id }}</td>
									<td width="20%" scope="row">
										<button class="btn btn-warning btn-circle btn-xl" v-on:click="doVer(index, 'Editar')" data-toggle="modal" data-target="#modal" title="Editar">
											<i class="fa fa-pen white" aria-hidden="true"></i>
										</button>
										<button class="btn btn-danger btn-circle btn-xl" v-on:click="doVer(index, 'Eliminar')" data-toggle="modal" data-target="#modal" title="Eliminar">
											<i class="fa fa-trash white" aria-hidden="true"></i>
										</button>
									</td>
									<td>{{ r.imp_nombre }} </td>
									<td>
										<span v-if="r.imp_estado == 'A'" class="badge badge-success">A</span>
										<span v-else-if="r.imp_estado !== 'A'" class="badge badge-warning">{{ r.imp_estado }}</span>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<!-- Ventana para Nuevo, Editar y Eliminar  -->
		<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
			<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
				<div class="modal-content">
					<div id="mTitulo">
						<h5 class="modal-title" id="exampleModalLabel">{{ accion }} {{ singular }}</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row justify-content-left">
							<div class="col-md-12">
								<label>Nombre</label>
								<input v-model="registro.imp_nombre" class="form-control" placeholder="Nombre" :disabled="disable">
								<p v-if="!registro.imp_nombre" class="mensaje">Complete</p>
							</div>
							<div class="col-md-12">
								<label>Informe</label>
							</div>
							<div class="col-md-12">
								<button class="form-control btn btn-primary" @click="doSwVerCodigo()">Ver Código</button>
								<!-- antiguo TinyMCE Editor
								<tinymce id="contenido" v-model="registro.imp_data"
									toolbar1='formatselect | bold italic strikethrough forecolor backcolor | image link | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | table code'
									:other_options="{ menubar:false }"
									:height="600"
								></tinymce>
								-->
								<vue2-tinymce-editor v-if="!swVerCodigo" v-model="registro.imp_data" :height="600"></vue2-tinymce-editor>
							</div>
							<hr>
							<div class="col-md-12">
								<textarea v-if="swVerCodigo" v-model="registro.imp_data" cols="100%" rows="25" :options="options"></textarea>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
						<button type="button" class="btn btn-primary" @click="registrar()" data-dismiss="modal">Guardar</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import tinymce from 'vue-tinymce-editor'
	import { Vue2TinymceEditor } from "vue2-tinymce-editor";

	export default {
		name: 'impresiones',
		data() {
			return {
				filtro: {},
				plural: 'Impresiones',
				singular: 'Impresión',
				seleccionado: '',
				seleccionado2: '',
				seleccionado3: '',
				errores: [],
				registro: {},
				registros: [],
				catalogos: [],
				nodos: [],
				procesos: [],
				actividades: [],
				tactividades: [],
				campos: [],
				verNuevo: true,
				accion: '',
				disable: false,
				clase: '',
				swVerCodigo: false,

				options:{
                    menubar: true,
                    plugins: 'advlist autolink charmap code directionality emoticons table',
                    toolbar1:'fontselect | fontsizeselect | formatselect | bold italic underline strikethrough forecolor backcolor',
                    toolbar2:'alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | link table removeformat code',
                  }
			}
		},

		components: {
			'tinymce': tinymce, Vue2TinymceEditor,
		},

		mounted() {
			this.listarCatalogos();
		},

		methods: {
			listarRegistros() {
				let url = "api/impresiones/" + this.seleccionado3;
				axios.get(url).then(response => {
					this.registros = response.data.data; //twice data
				});
			},

			listarCatalogos() {
				let url = "api/catalogos/";
				axios.get(url).then(response => {
					this.catalogos = response.data.data; //twice data
				});
			},

			listarProcesos() {
				let url = "api/procesos/" + this.seleccionado;
				axios.get(url).then(response => {
					this.procesos = response.data.data; //twice data
					this.procesos.forEach(function (row) {
						row.prc_data = JSON.parse(row.prc_data);
					});
				});
			},

			listarActividades() {
				let url = "api/actividades/" + this.seleccionado2;
				axios.get(url).then(response => {
					this.actividades = response.data.data; //twice data
					this.actividades.forEach(function (row) {
						row.act_data = JSON.parse(row.act_data);
					});
				});
			},

			listarTActividades() {
				let url = "api/tactividades/";
				axios.get(url).then(response => {
					this.tactividades = response.data.data; //twice data
				});
			},

			listarNodos() {
				let url = "api/nodos/";
				axios.get(url).then(response => {
					this.nodos = response.data.data; //twice data
				});
			},

			doVer(index, accion) {
				this.accion = accion;
				this.registro = this.registros[index];
				if (this.accion == 'Eliminar') {
					document.getElementById("mTitulo").className = "modal-header bg-danger";
					this.disable = true;
				} else {
					document.getElementById("mTitulo").className = "modal-header bg-warning";
					this.disable = false;
				}
			},

			doLimpiar() {
				this.registro = {};
				this.accion = 'Nuevo';
				document.getElementById("mTitulo").className = "modal-header bg-primary";
				this.disable = false;
			},

			/*
			validarTipoActividad(idx) {
				let that = this;
				let res = true;
				if ((this.actividades[idx].act_tact_id == 1) || (this.actividades[idx].act_tact_id == 4)) {
					res = false;
				}
				this.verNuevo = res;
			},
			*/
			
			registrar() {
				if (this.accion == 'Nuevo') {
					var reg = this.registro;
					reg.imp_act_id = this.seleccionado3;
					axios.post('/api/impresiones', reg).then(response => {
						this.listarRegistros();
					});
				} else if (this.accion == 'Eliminar') {
					var url = 'api/impresiones/' + this.registro.imp_id;
					axios.delete(url, this.registro).then(response => {
						this.listarRegistros();
					});
				} else if (this.accion == 'Editar') {
					axios.put('/api/impresiones/' + this.registro.imp_id, this.registro).then(response => {
						this.listarRegistros();
					});
				}
			},

			doSwVerCodigo () {
				this.swVerCodigo = ! this.swVerCodigo;
			}
		},
	}
</script>