<template>
	<div class="container-fluid">
		<div class="card">
			<div class="card-header">
				<div class="row">
					<div class="col-md-3">
						<h5>{{ plural }}</h5>
					</div>
					<div class="col-md-5">-
					</div>
					<div class="col-md-2">
						<button class="form-control btn btn-warning" @click="swListarArchivados()">
							<i class="fa fa-refresh white" aria-hidden="true"></i> {{ !swArchivo ? "Ir a Archivo" : "Ir a Pendientes" }}
						</button>
					</div>
					<div class="col-md-2">
						<button class="form-control btn btn-primary" @click="listarRegistros()" :disabled="swArchivo">
							<i class="fa fa-refresh white" aria-hidden="true"></i> Refrescar
						</button>
					</div>
				</div>
			</div>
			<div class="card-body">
				<div v-if="!swArchivo">
					<table class="table table-hover table-striped table-responsive" id="divTable">
						<thead class="thead-dark">
							<tr>
								<th scope="col">#</th>
								<th>
								</th>
								<th scope="col">PROCESO / ACTIVIDAD</th>
								<th scope="col">No. CASO</th>
								<th scope="col">CAMPOS CLAVE</th>
								<th scope="col">REGISTRADO</th>
								<th scope="col">E</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(r, index) in registros">
								<td width="3%" scope="row">{{ r.cas_id }}</td>
								<td width="17%" scope="row">
									<span v-if="r.cas_usr_id === usrId  || r.cas_estado === 'A'">
									<router-link v-if=" r.cas_estado==='T'" :to="'/atenderCaso/' + r.cas_id">
										<button type="button" class="btn btn-primary btn-circle" title="Atender">
											<i class="fa fa-pen white" aria-hidden="true"></i>
										</button>
									</router-link>
									<button v-if="r.cas_estado === 'A'" type="button" class="btn btn-warning btn-circle"
										title="Tomar" v-on:click="doVer( index )" data-toggle="modal"
										data-target="#modalTomar">
										<i class="fa fa-lock white" aria-hidden="true"></i>
									</button>
									<button v-if="r.cas_estado === 'T'" type="button" class="btn btn-success btn-circle"
										title="Liberar" v-on:click="doVer( index )" data-toggle="modal"
										data-target="#modalLiberar">
										<i class="fa fa-lock-open white" aria-hidden="true"></i>
									</button>
									<button v-if="r.cas_estado === 'T'" type="button" class="btn btn-primary btn-circle"
										title="Histórico" v-on:click="doHistorico( r.cas_id )" data-toggle="modal"
										data-target="#modalHistorico">
										<i class="fa fa-list-ol" aria-hidden="true"></i>
									</button>
									<button v-if="r.cas_estado === 'T'" type="button" class="btn btn-danger btn-circle "
										title="Derivar" v-on:click="doLimpiar( index )" data-toggle="modal"
										data-target="#modalDerivar">
										<i class="fa fa-paper-plane white" aria-hidden="true"></i>
									</button>
									</span>
								</td>
								<td>
									<strong>[{{ r.prc_data.prc_codigo }}] {{ r.prc_data.prc_descripcion}}</strong><br>
									<span class="badge badge-dark">{{ r.act_data.act_orden}}</span> -
									{{ r.act_data.act_descripcion}}
								</td>
								<td>
									{{ r.cas_data.cas_nro_caso}} / {{ r.cas_data.cas_gestion}}
								</td>
								<td>
									<span v-html="r.cas_data.cas_nombre_caso"></span>
								</td>
								<td>{{ r.cas_registrado.substr(0,16) }}</td>
								<td>
									<span v-if="r.cas_estado === 'A'" class="badge badge-success">Libre</span>
									<span v-else-if="r.cas_estado === 'T'" class="badge badge-warning">Tomado</span>
									<span v-else class="badge badge-warning">{{ r.cas_estado}}</span>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div v-if="swArchivo">
					<table class="table table-hover table-striped table-responsive" id="divTable">
						<thead class="thead-dark">
							<tr>
								<th scope="col">#</th>
								<th>
								</th>
								<th scope="col">PROCESO / ACTIVIDAD</th>
								<th scope="col">No. CASO</th>
								<th scope="col">CAMPOS CLAVE</th>
								<th scope="col">REGISTRADO</th>
								<th scope="col">E</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(r, index) in archivos">
								<td width="3%" scope="row">{{ r.cas_id }}</td>
								<td width="17%" scope="row">
									<button  type="button" class="btn btn-success btn-circle"
										title="desarchivar" >
										<i class="fa fa-lock-open white" aria-hidden="true"></i>
									</button>
								</td>
								<td>
									<strong>{{ r.prc_data.prc_descripcion}}</strong><br>
									<span class="badge badge-dark">{{ r.act_data.act_orden}}</span> -
									{{ r.act_data.act_descripcion}}
								</td>
								<td>
									{{ r.cas_data.cas_nro_caso}} / {{ r.cas_data.cas_gestion}}
								</td>
								<td>
									<span v-html="r.cas_data.cas_nombre_caso"></span>
								</td>
								<td>{{ r.cas_registrado.substr(0,16) }}</td>
								<td>
									<span v-if="r.cas_estado === 'A'" class="badge badge-success">Libre</span>
									<span v-else-if="r.cas_estado === 'T'" class="badge badge-warning">Tomado</span>
									<span v-else class="badge badge-warning">{{ r.cas_estado}}</span>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<!-- modalTomar -->
		<div class="modal fade" id="modalTomar" tabindex="-1" role="dialog" aria-labelledby="modalTomar"
			aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header bg-warning">
						<h5 class="modal-title" id="exampleModalLabel">Tomar {{ singular }}</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row justify-content-left">
							<div class="col-md-6">
								<label>{{ registro.cas_data.cas_nombre_caso }}</label>
								<label>¿ Confirma tomar el {{ singular }} ?</label>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
						<button type="button" class="btn btn-warning" @click="tomarCaso($event)"
							data-dismiss="modal">Si, tomar</button>
					</div>
				</div>
			</div>
		</div>

		<!-- modalLiberar -->
		<div class="modal fade" id="modalLiberar" tabindex="-1" role="dialog" aria-labelledby="modalLiberar"
			aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header bg-success">
						<h5 class="modal-title" id="exampleModalLabel">Liberar el {{ singular }}</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row justify-content-left">
							<div class="col-md-6">
								<label>{{ registro.cas_data.cas_nombre_caso }}</label>
								<label>¿ Confirma liberar el {{ singular }} ?</label>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
						<button type="button" class="btn btn-success" @click="liberarCaso($event)"
							data-dismiss="modal">Si, liberar</button>
					</div>
				</div>
			</div>
		</div>

		<!-- modalDerivar -->
		<div class="modal fade" id="modalDerivar" tabindex="-1" role="dialog" aria-labelledby="modalDerivar"
			aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header bg-danger">
						<h5 class="modal-title" id="exampleModalLabel">Derivar el {{ singular }}</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row justify-content-left">
							<div class="col-md-12">
								<label>Caso:</label><br>
								<label><span v-html="registro.cas_data.cas_nombre_caso"></span></label><br>
								<label>Impresión:</label><br>
								<span v-for="(r, index) in impresiones">
									<button type="button" class="btn btn-success" @click="doImprimir(index)">
										<i class="fa fa-print white" aria-hidden="true"></i> {{ r.imp_nombre }}
									</button><br>
								</span><br>
								<label>Siguiente Actividad:</label>
								<table class="table table-hover table-striped table-responsive">
									<tr>
										<td><span class="badge badge-dark">{{ siguiente.act_data.act_orden }}</span>
										</td>
										<td>{{ siguiente.act_data.act_descripcion}}</td>
										<td>{{ siguiente.act_data.act_duracion_horas }} hora(s)</label></td>
									</tr>
								</table>
								<label>¿ Confirma derivar el {{ singular }} ?</label>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
						<button type="button" class="btn btn-danger" @click="derivarCaso($event)"
							data-dismiss="modal">Sí, derivar</button>
					</div>
				</div>
			</div>
		</div>

		<!-- modalHistorico -->
		<div class="modal fade" id="modalHistorico" tabindex="-1" role="dialog" aria-labelledby="modalHistorico"
			aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<h5 class="modal-title" id="exampleModalLabel">Historico {{ singular }}</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row justify-content-left">
							<div class="col-md-12">
								<label>Caso:</label><br>
								<table class="table table-hover table-striped table-responsive">
									<thead class="thead-dark">
										<tr>
											<th>Nro</th>
											<th>Actividad</th>
											<th>Nodo</th>
											<th>Modificado</th>
											<th>Usuario</th>
										</tr>
									</thead>
									<tbody>
										<tr v-for="(h, index) in historico">
											<td>{{index + 1}}</td>
											<td>{{(JSON.parse(h.act_data)).act_descripcion}}</td>
											<td>{{h.nodo_descripcion}}</td>
											<td>{{h.htc_cas_modificado}}</td>
											<td>{{h.name}}</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					</div>
				</div>
			</div>
		</div>

	</div>
</template>

<script>
	import datatables from 'datatables';
	import jsPDF from 'jspdf';

	export default {
		name: 'servicios',
		data() {
			return {
				plural: 'Mis Pendientes',
				singular: 'Caso',
				usrId: window.Laravel.usr_id,
				seleccionado: '',
				errores: [],
				registro: { cas_data: {} },
				registros: [],
				archivos: [],
				procesos: [],
				siguiente: { act_data: {} },
				impresiones: [],
				historico: [],
				dataTable: null,

				swArchivo: false
			}
		},

		mounted() {
			this.listarRegistros();
			this.dataTable = $('#divTable').DataTable({
				responsive: true,
				order: []
			});
		},

		created() {

		},

		methods: {
			listarRegistros() {
				let url = "api/casos/" + this.usrId;
				axios.get(url).then(response => {
					this.registros = response.data.data; //twice data
					this.registros.forEach(function (row) {
						row.cas_data = JSON.parse(row.cas_data);
						// corregir undefined en cas_nombre_caso
						row.cas_data.cas_nombre_caso = row.cas_data.cas_nombre_caso ? row.cas_data.cas_nombre_caso : ''
						row.cas_data.cas_nombre_caso = row.cas_data.cas_nombre_caso.replaceAll('undefined', '-');

						row.cas_data_valores = JSON.parse(row.cas_data_valores);
						row.prc_data = JSON.parse(row.prc_data);
						row.act_data = JSON.parse(row.act_data);
						row.cas_data.cas_nombre_caso = (row.cas_data.cas_nombre_caso) ? row.cas_data.cas_nombre_caso.replaceAll('|', "<br/>") : '';
						row.act_data_reglas = JSON.parse(row.act_data_reglas);
					});
				});
			},

			listarArchivados() {
				let url = "api/casosArchivados/" + this.usrId;
				axios.get(url).then(response => {
					this.archivos = response.data.data; //twice data
					this.archivos.forEach(function (row) {
						row.cas_data = JSON.parse(row.cas_data);
						// corregir undefined en cas_nombre_caso
						row.cas_data.cas_nombre_caso = row.cas_data.cas_nombre_caso ? row.cas_data.cas_nombre_caso : ''
						row.cas_data.cas_nombre_caso = row.cas_data.cas_nombre_caso.replaceAll('undefined', '-');

						row.cas_data_valores = JSON.parse(row.cas_data_valores);
						row.prc_data = JSON.parse(row.prc_data);
						row.act_data = JSON.parse(row.act_data);
						row.cas_data.cas_nombre_caso = (row.cas_data.cas_nombre_caso) ? row.cas_data.cas_nombre_caso.replaceAll('|', "<br/>") : '';
						row.act_data_reglas = JSON.parse(row.act_data_reglas);
					});
				});
			},

			swListarArchivados() {
				this.swArchivo = !this.swArchivo;
				if(this.swArchivo)
					this.listarArchivados();
			},

			doVer(index) { // Para tomarCaso() y liberarCaso()
				this.registro = this.registros[index];
			},

			doLimpiar(index) { // Para derivarCaso()
				this.registro = this.registros[index];
				let that = this;

				// Si hay reglas, evaluarlas
				this.registro.act_data_reglas.forEach(function (regla) {
					let reg = regla.act_regla;
					that.registro.cas_data_valores.forEach(function (campito) {
						reg = reg.replaceAll('#' + campito.frm_campo + '#', campito.frm_value);
						if ((reg.indexOf('#') < 0) && eval(reg)) {
							that.registro.act_data.act_siguiente = regla.act_siguiente;
						}
					});
				});

				let url = "api/actividad/" + this.registro.prc_id + "/" + this.registro.act_data.act_siguiente;
				axios.get(url).then(response => {
					this.siguiente = response.data.data;
					this.siguiente.forEach(function (row) {
						row.act_data = JSON.parse(row.act_data);
					});
					// solecciona solo el primer registro
					this.siguiente = this.siguiente[0];
				});

				url = "api/impresiones/" + this.registro.act_id;
				axios.get(url).then(response => {
					this.impresiones = response.data.data; //twice data
				});
			},

			tomarCaso(e) {
				let gRegistro = {
					"cas_estado": "T",
					"cas_usr_id": this.usrId
				};
				let that = this;
				let url = "api/estadoCaso/" + this.registro.cas_id;
				axios.put(url, gRegistro)
					.then((response) => {
						this.$router.push("/atenderCaso/" + this.registro.cas_id);
					})
					.catch(function (error) {
						that.output = error;
					});
			},

			tomarCasoRoundRobin(usrId) {
				let gRegistro = {
					"cas_estado": "T",
					"cas_usr_id": usrId
				};
				let that = this;
				let url = "api/estadoCaso/" + this.registro.cas_id;
				axios.put(url, gRegistro)
					.then((response) => {
						//this.$router.push("/atenderCaso/" + this.registro.cas_id);
					})
					.catch(function (error) {
						that.output = error;
					});
			},

			//historico
			doHistorico(id) {
				let that = this;
				let url = "api/casosHistorico/" + id;
				axios.get(url)
					.then((response) => {
						this.historico = response.data.data;
					})
					.catch(function (error) {
						that.output = error;
					});
			},

			liberarCaso(e) {
				let gRegistro = {
					"cas_estado": "A",
					"cas_usr_id": this.usrId
				};
				let that = this;
				let url = "api/estadoCaso/" + this.registro.cas_id;
				axios.put(url, gRegistro)
					.then(function (response) {
						that.output = response.data;
						that.listarRegistros();
					})
					.catch(function (error) {
						that.output = error;
					});
			},

			derivarCaso(e) {
				let gRegistro = this.registro;
				gRegistro.cas_estado = 'A';
				gRegistro.cas_act_id = this.siguiente.act_id;
				gRegistro.cas_nodo_id = this.siguiente.act_nodo_id;
				gRegistro.cas_estado = 'A';
				gRegistro.cas_usr_id = this.usrId;
				let that = this;
				let url = "api/casosDerivar/" + this.registro.cas_id;
				let arrayUsrNodo = [];
				let ultimoUsr = 0;
				let usrTomar = 0;
				axios.put(url, gRegistro)
					.then(function (response) {
						that.output = response.data;
						// aqui validar round robijn o sel service.
						let tipoDer = that.siguiente.act_data.act_tipo_derivacion;
						if( tipoDer == 'ROUND_ROBIN')
						{
							// a partir de aquí Round-robin
							// indice = -1
							let indice = -1;
							// 1. leer los usuarios del nodo 
							// con el servicio GET usrNodosXNodoId/{nodo_id} OK servicio programado
							let url2 = "api/usrNodosXNodoId/" + that.siguiente.act_nodo_id;
							axios.get(url2)
							.then(function (response2) {
								let array = response2.data.data;
							// 2. leer los registros en un array local
								for ( var i = 0; i < array.length; i++){
									var a = array[i].id;
									arrayUsrNodo[i] = a;
								}
								// 3. leer el ultimo_usr de rmx_vys_usrnodos_roundrobin
								// con el servicio GET usrNodosRR/{nodo_id} 
								// si no hay registros LEN==0, 
								// entonces index=0 para JUGAR con arreglo
								// si es -1 va a 4. sino 5.
								let url3 = "api/usrNodosRR/" + that.siguiente.act_nodo_id;
								axios.get(url3)
								.then(function (response3) {
									ultimoUsr = response3.data.data[0].rr_ultimo_usr_id;
									// 4. JUGAR con el arreglo para saber el "siguiente usuario"
									// forzar usar el servicio PUT estadoCaso/{cas_id}
									// con body 
									// para tomar el usuario <------??? analizar
									if (ultimoUsr == 0){
										usrTomar = arrayUsrNodo[0];
									} else {
										for (var j = 0; j < arrayUsrNodo.length; j++ ){
											if (arrayUsrNodo[j] == ultimoUsr){
												if (j+1 == arrayUsrNodo.length){
													usrTomar = arrayUsrNodo[0];
												} else {
													usrTomar = arrayUsrNodo[j+1];
												}
											}
										}
									}
									that.tomarCasoRoundRobin(usrTomar);
									// 5. Actualizar el ultimo usuario 
									// en servicio PUT usrNodosRR/{nodo_id}
									// con body "siguiente usuario"
									let gData = {};
									gData.nodo_id = that.siguiente.act_nodo_id;
									gData.ult_usr = usrTomar;
									let url = "api/usrNodosRR";
									axios.put(url, gData)
										.then(function (response) {
											that.output = response.data;
											that.listarRegistros();
										})
										.catch(function (error) {
											that.output = error;
										});
								})
								.catch(function (error2) {
									that.output = error2;
								});
							})
							.catch(function (error2) {
								that.output = error2;
							});
						} else { //if( tipoDer == 'SELF_SERVICE'){
							that.listarRegistros();
						}
					})
					.catch(function (error) {
						that.output = error;
					});
			},

			doImprimir(i) {
				var impress = this.impresiones[i].imp_data;
				this.registro.cas_data_valores.forEach(valor => {
					if (Array.isArray(valor.frm_value)) {
						var grilla = '<table cellpadding="0" cellspacing="0">';
						valor.frm_value.forEach(fila => {
							grilla += '<tr style="height: 6pt; font-size: 6pt; font-family: arial, helvetica, sans-serif;">';
							//11.1/var col = 0;
							//11.1/var colsValidas = [0, 1, 2, 3];
							fila.forEach(campo => {
								//11.1/if ( colsValidas.includes(col)) {
									grilla += '<td style="height: 6pt; font-size: 6pt; font-family: arial, helvetica, sans-serif;">' + campo.col_value + '</td>';
								//11.1/}
								//11.1/col++;
							});
							grilla += '</tr>';
						});
						grilla += '</table>'
						impress = impress.replaceAll('#' + valor.frm_campo.trim() + '#', grilla);
					} else {
						var vValue = (typeof valor.frm_value === "undefined") ? "-?-" : valor.frm_value;
						var vValueLabel = (typeof valor.frm_value_label === "undefined") ? "-?-" : valor.frm_value_label;

						impress = impress.replaceAll("#" + valor.frm_campo.trim() + "!LABEL#", vValueLabel);
						impress = impress.replaceAll("#" + valor.frm_campo.trim() + "!EVAL#", '"' + vValue + '"');
						impress = impress.replaceAll('#' + valor.frm_campo.trim() + '#', vValue);
					}
				});

				//this.registro.cas_data_valores.forEach(valor => {
					// todavia hay resto
					const patron = /#\b["0-9A-Za-z_=?]*\B#/ig;
					var evaluados = impress.match(patron);
					console.log("=============================================");
					if (Array.isArray(evaluados)) {
						console.log(evaluados);
						evaluados.forEach( function (ev, idx) {
							var tok = ev.replaceAll("#", "").split("?");
							if (tok.length > 1) {
								var	 result = "";
								try {
									if ( result = eval(tok[1]) ) {
										impress = impress.replaceAll(evaluados[idx], "+++");//vValue);
									} else {
										impress = impress.replaceAll(evaluados[idx], "...");
									}
								} catch (error) {
									console.log(error);
								}
							}
						});
					}
				//});

				var doc = new jsPDF('p', 'pt', 'letter');
				doc.setFont("arial");

				doc.html(impress, {
					callback: function (pdf) {
						doc.output('dataurlnewwindow');
					},
					x: 30,
					y: 30,
					width: 800,
					margin: [30, 30, 30, 30]
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