<template>
	<div class="container-fluid">
		<div class="card">
			<div class="card-header">
				<div class="row">
					<div class="col-md-3">
						<button type="button" class="form-control btn btn-success" title="Descartar y Volver"
							@click="$router.push('/misCasos')">
							<i class="fa fa-backward white" aria-hidden="true"></i> Descartar y Volver
						</button>
					</div>
					<div class="col-md-6">
						<h5>{{ plural }}</h5>
					</div>
					<div class="col-md-3 float-end pull-right ">
						<button class="btn btn-primary btn-circle" data-target="#modalMensajeria"
							title="Ver Mensajeria" v-on:click="doGetMensajes()" data-toggle="modal">
							<i class="fa fa-envelope white" aria-hidden="true"></i>
						</button>
						<button class="btn btn-warning btn-circle" data-target="#modalArchivar"
							title="Archivar" data-toggle="modal">
							<i class="fa fa-archive white" aria-hidden="true"></i>
						</button>
						<button class="btn btn-secondary btn-circle" data-target="#modalCampos"
							title="Ver Campos" v-on:click="verCampos()" data-toggle="modal">
							<i class="fa fa-eye white" aria-hidden="true"></i>
						</button>
					</div>
				</div>
				<div class="row">
					<div class="col-md-7">
						<div class="col-12">
							Proceso:
							<strong>{{ proceso.prc_descripcion }}</strong>
						</div>
						<div class="col-12">
							Actividad:
							<span class="badge badge-dark">{{ actividad.act_orden }}</span>
							<strong>{{ actividad.act_descripcion }}</strong>
						</div>
					</div>
					<div class="col-md-5">
						<div class="col-12">
							No. Caso:
							<h1>
								{{ caso.cas_nro_caso }}/{{ caso.cas_gestion }}
							</h1>
						</div>
					</div>
				</div>
			</div>
			<div class="card-body" id="editor">
				<form id="form1" @submit="actualizarCaso()">
					<div class="row col-md-12">
						<div v-for="(c, index) in campos" :class="doDefinirClass(c)">
							<div v-if="c.frm_tipo=='TITLE'" style="background-color:grey; color:white; margin-top:10px;">
								<h3>{{ c.frm_etiqueta }}</h3>
							</div>
							<div v-else-if="c.frm_tipo=='SUBTITLE'" style="background-color:lightgrey; margin-top:10px;">
								<h5>{{ c.frm_etiqueta }}</h5>
							</div>
							<div v-else-if="c.frm_tipo=='HIDDEN'">
								{{ c.frm_etiqueta }}
								<input :id="c.frm_campo" type="hidden" v-model="c.frm_value">
							</div>
							<div v-else-if="c.frm_tipo=='TEXT'">
								<div><div>{{ c.frm_etiqueta }}</div> <div v-show="c.frm_obligatorio=='true'"> (*)</div></div>
								<input :id="c.frm_campo" v-model="c.frm_value" class="form-control" type="text"
									:disabled="c.frm_deshabilitado == 'true' ? true : false" :required="c.frm_obligatorio == 'true'">
							</div>
							<div v-else-if="c.frm_tipo=='NUMBER'">
								{{ c.frm_etiqueta }}  <div v-show="c.frm_obligatorio=='true'"> (*)</div>
								<input :id="c.frm_campo" v-model="c.frm_value" class="form-control" type="number" v-on:keypress="isNumber($event)"
									:disabled="c.frm_deshabilitado == 'true' ? true : false" :required="c.frm_obligatorio == 'true'">
							</div>
							<div v-else-if="c.frm_tipo=='TEXTAREA'">
								{{ c.frm_etiqueta }}  <div v-show="c.frm_obligatorio=='true'"> (*)</div>
								<textarea :id="c.frm_campo" v-model="c.frm_value" class="form-control" rows="5"
									:disabled="c.frm_deshabilitado == 'true' ? true : false" :required="c.frm_obligatorio == 'true'"></textarea>
							</div>
							<div v-else-if="c.frm_tipo=='DATE'">
								{{ c.frm_etiqueta }}  <div v-show="c.frm_obligatorio=='true'"> (*)</div>
								<input :id="c.frm_campo" type="date" v-model="c.frm_value" class="form-control"
									:disabled="c.frm_deshabilitado == 'true' ? true : false" :required="c.frm_obligatorio == 'true'">
							</div>
							<div v-else-if="c.frm_tipo=='TIME'">
								{{ c.frm_etiqueta }}  <div v-show="c.frm_obligatorio=='true'"> (*)</div>
								<input :id="c.frm_campo" type="time" v-model="c.frm_value" class="form-control"
									:disabled="c.frm_deshabilitado == 'true' ? true : false" :required="c.frm_obligatorio == 'true'">
							</div>
							<div v-else-if="c.frm_tipo=='CHECKBOX'">
								<label>
									<br/>  <div v-show="c.frm_obligatorio=='true'"> (*)</div>
									<input :id="c.frm_campo" type="checkbox" v-model="c.frm_value" :disabled="c.frm_deshabilitado == 'true' ? true : false" :required="c.frm_obligatorio == 'true'"> {{ c.frm_etiqueta }}
								</label>
							</div>
							<div v-else-if="c.frm_tipo=='DROPDOWNLIST'">
								{{ c.frm_etiqueta }}  <div v-show="c.frm_obligatorio=='true'"> (*)</div>
								<select :id="c.frm_campo" v-model="c.frm_value" class="form-control" @change="ejecutar(c.frm_funcion);" 
									:disabled="c.frm_deshabilitado == 'true' ? true : false" :required="c.frm_obligatorio == 'true'">
									<option v-bind:value="i.frm_value" v-for="(i, index2) in c.frm_items">
										{{ i.frm_value }} - {{ i.frm_etiqueta }}
									</option>
								</select>
							</div>
							<div v-else-if="c.frm_tipo=='SELECT'">
								{{ c.frm_etiqueta }}  <div v-show="c.frm_obligatorio=='true'"> (*)</div>
								<select :id="c.frm_campo" v-model="c.frm_value" class="form-control" :disabled="c.frm_deshabilitado == 'true' ? true : false" :required="c.frm_obligatorio == 'true'">
									<option v-bind:value="i.frm_value" v-for="(i, index2) in c.frm_items">
										{{ i.frm_value }} - {{ i.frm_etiqueta }}
									</option>
								</select>
							</div>
							<div v-else-if="c.frm_tipo=='MASK'">
								{{ c.frm_etiqueta }}  <div v-show="c.frm_obligatorio=='true'"> (*)</div>
								<input :id="c.frm_campo" v-model="c.frm_value" class="form-control" type="tel" v-mask="c.frm_mask" :disabled="c.frm_deshabilitado == 'true' ? true : false" :placeholder="c.frm_mask" :required="c.frm_obligatorio == 'true'">
							</div>
							<!-- div v-else-if="c.frm_tipo=='SCRIPT'">
								<p v-show="ejecutar_BAK(c.frm_value)"/>
							</div -->
							<div v-else-if="c.frm_tipo=='BUTTON'">
								<br/>
								<button :id="c.frm_campo" type="button" class="btn btn-success" @click="ejecutar(c.frm_funcion);">{{ c.frm_etiqueta }}</button>
							</div>
							<div v-else-if="c.frm_tipo=='IMAGE'">
								{{ c.frm_etiqueta }}   <div v-show="c.frm_obligatorio=='true'"> (*)</div>
								<div class="input-group mb-3">
									<input :id="c.frm_campo" v-model="c.frm_value" class="form-control"
										placeholder="Ingrese Imagen en formato JPG, JPEG o PNG" disabled>
									<div class="input-group-append">
										<button class="btn btn-primary btn-xs" v-on:click.stop.prevent="verImagen( c.frm_value )">
											<i class="fa fa-eye white" aria-hidden="true"></i>
                                    	</button>
									</div>
								</div>
								<input type="file" name="capturaFoto" @change="getImage($event, c.frm_campo, index)" accept="image/png, image/jpg, image/jpeg">
								<img :src=" c.frm_value" alt="Foto" class="img-responsive" >
							</div>
							<div v-else-if="c.frm_tipo=='DOCUMENT'">
								{{ c.frm_etiqueta }}   <div v-show="c.frm_obligatorio=='true'"> (*)</div>
								<div class="input-group mb-3">
									<input :id="c.frm_campo" v-model="c.frm_value" class="form-control"
										placeholder="Ingrese Documento" disabled>
									<div class="input-group-append">
										<button class="btn btn-primary btn-xs" v-on:click.stop.prevent="verImagen( c.frm_value )">
											<i class="fa fa-eye white" aria-hidden="true"></i>
                                    	</button>
									</div>
								</div>
								<input type="file" name="capturaFoto" @change="getDocument($event, c.frm_campo, index)" accept=".xlsx,.xls,.doc, .docx,.ppt, .pptx,.txt,.pdf">
								<!--img :src="foto" alt="Foto" class="img-responsive" -->
							</div>

							<div v-else-if="c.frm_tipo=='GRID'">
								{{ c.frm_etiqueta }}
								<table border="0" width="100%" class="table table-hover table-striped table-responsive" :id="'divTable'+c.frm_campo">
									<tr class="thead-light">
										<th>
											<i class="fa fa-plus white" title="Nuevo" v-on:click="gridAddRow(c.frm_cols, c.frm_value)"></i>
										</th>
										<th v-for="col in c.frm_cols" :key="col.campo">
											{{ col.col_etiqueta }}
										</th>
									</tr>
									<tr v-for="(row, rowIndex) in c.frm_value" :key="rowIndex">
										<td>
											{{ rowIndex + 1}}&nbsp; 
											<i class="fa fa-trash white" title="Eliminar" v-on:click="gridDeleteRow(c.frm_value, rowIndex); "></i>
										</td>
										<td v-for="(col, colIndex) in row" :key="colIndex">
											<div v-if="c.frm_cols[colIndex].col_tipo=='TEXT'">
												<input :placeholder="col.col_etiqueta" v-model="col.col_value" 
													:disabled="col.col_deshabilitado == 'true' ? true : false" :required="c.col_obligatorio == 'true'">
											</div>
											<div v-else-if="c.frm_cols[colIndex].col_tipo=='TEXTAREA'">
												<textarea :placeholder="col.col_etiqueta" v-model="col.col_value" cols="30" rows="3"
													:disabled="col.col_deshabilitado == 'true' ? true : false" :required="c.col_obligatorio == 'true'"></textarea>
											</div>
											<div v-else-if="c.frm_cols[colIndex].col_tipo=='DATE'">
												<input type="date" :placeholder="col.col_etiqueta" v-model="col.col_value"
													:disabled="col.col_deshabilitado == 'true' ? true : false" :required="c.col_obligatorio == 'true'">
											</div>
											<div v-else-if="c.frm_cols[colIndex].col_tipo=='CHECKBOX'">
												<input type="checkbox" :placeholder="col.col_etiqueta" v-model="col.col_value"
													:disabled="col.col_deshabilitado == 'true' ? true : false" :required="c.col_obligatorio == 'true'">
											</div>
											<div v-else-if="c.frm_cols[colIndex].col_tipo=='LINK'">
												<a target="_blank" :href="col.col_value">Ver</a>
											</div>
											<div v-else-if="c.frm_cols[colIndex].col_tipo=='DROPDOWNLIST'"
												:disabled="col.col_deshabilitado == 'true' ? true : false" :required="c.col_obligatorio == 'true'">
												<select v-model="col.col_value">
													<option v-bind:value="itm.itm_value" 
														 v-for="(itm, itmIndex) in c.frm_cols[colIndex].col_items" :key="itmIndex">
														{{ itm.itm_value }} - {{ itm.itm_etiqueta }}
													</option>
												</select>
											</div>
											<div v-else-if="c.frm_cols[colIndex].col_tipo=='BUTTON'">
												<br/>
												<button :id="c.col_campo" type="button" class="btn btn-success" @click="ejecutar(c.col_funcion);">{{ c.col_etiqueta }}</button>
											</div>
											<div v-else>
												NO IDENTIFICADO
											</div>
										</td>
									</tr>
								</table>
							</div>


							<div v-else-if="c.frm_tipo=='GPS_MARKER'" id="container">
								<div class="row">
									<div class="col-md-12">
										<div id="mapContainer"></div>
									</div>
								</div>
							</div>


							<div v-else>
								{{ c.frm_etiqueta }}
								<span>Componente no determinado</span>
							</div>
						</div>
					</div>
				</form>
				<div class="row col-md-12">
				</div>
			</div>
		</div>

		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-md-3">
						<button type="submit" form="form1" class="form-control btn-success" 
							@click="$router.push('/misCasos')">
							<i class="fa fa-backward white" aria-hidden="true"></i>
							Descartar y Volver</button>
					</div>
					<div class="col-md-3">
						<button type="submit" form="form1" class="form-control btn-primary">
							<i class="fa fa-floppy-o white" aria-hidden="true"></i>
							Grabar y Volver</button>
					</div>
					<div class="col-md-6">
					</div>
				</div>
				<br>
			</div>
		</div>

		<div>
			<p v-show="addScript(registro.frm_funciones)"/>
		</div>

		<!-- modalArchivar -->
		<div class="modal fade" id="modalArchivar" tabindex="-1" role="dialog" aria-labelledby="modalHistorico"
			aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header bg-warning">
						<h5 class="modal-title" id="exampleModalLabel">Archivar</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row justify-content-left">
							<div class="col-md-6">
								<label>Motívo Archivo:</label>
								<select v-model="archivo.cas_motivo_archivo" class="form-control" > 
									<option value='NO_COMPLETO'>NO completó los requisitos</option>
									<option value='SIN_SEGUIMIENTO'>Falta de seguimiento</option>
									<option value='NO_SUBSANABLES'>Observaciones NO subsanables</option>
									<option value='FINALIZADO'>Finalizado </option>
								</select>
							</div>
						</div>
						<div class="row justify-content-left">
							<div class="col-md-12">
								<label>Descripción:</label>
								<textarea v-model="archivo.cas_descripcion_archivo" class="form-control" rows="5"></textarea>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
						<button type="button" class="btn btn-warning" @click="archivarCaso($event)"
							data-dismiss="modal">Si, archivar</button>
					</div>
				</div>
			</div>
		</div>

		<!-- modalMensajeria -->
		<div class="modal fade" id="modalMensajeria" tabindex="-1" role="dialog" aria-labelledby="modalMensajeria"
			aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<h5 class="modal-title" id="exampleModalLabel">Mensajería</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row justify-content-left">
							<div class="col-md-12">
								<div class="row"> 
									<div class="col-md-12">
										<select v-model="mensaje_tipo">
											<option value="1">Aviso</option>
											<option value="2">Complementar información</option>
											<option value="3">Complementar adjunto/archivo</option>
											<option value="3">Cambiar adjunto/archivo</option>
										</select>
									</div>
									<div class="col-md-9">
										<label>Mensaje</label>
										<textarea v-model="mensaje" rows="3" class="form-control"></textarea>
									</div>
									<div class="col-md-3">
										<button type="button" class="btn btn-primary" @click="doSendMensaje()"
										data-dismiss="modal">Enviar</button>
									</div>
								</div>
								<label>Mensajes Enviado/Recibidos</label>
								<div v-for="(msj, index) in mensajes" class="row">
									<div class="col-md-12"> 
										<hr>
										ASUNTO: {{ msj.asunto }}
										<span v-if="msj.leido" class="badge badge-success">Leido</span>
										<span v-else class="badge badge-danger">Pendiente</span>
										<br>
										FECHA: {{ msj.fecha_creacion}} <br>
										MENSAJE:<br>
										{{ msj.detalle }}
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Volver</button>
						<!--button type="button" class="btn btn-primary" @click="archivarCaso($event)"
							data-dismiss="modal">Si, archivar</button-->
					</div>
				</div>
			</div>
		</div>

		<!-- modalCampos -->
		<div class="modal fade" id="modalCampos" tabindex="-1" role="dialog" aria-labelledby="modalCampos"
			aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header bg-secondary">
						<h5 class="modal-title" id="exampleModalLabel">Ver Campos</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row justify-content-left">
							<div class="col-md-12">
								<pre id="misCampos"></pre>
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
	//import jsPDF from 'jspdf';
	import {mask} from 'vue-the-mask';

    import "leaflet/dist/leaflet.css";
    import L from "leaflet";
        import { LMap, LTileLayer, LMarker, LIcon, LPopup, LCircleMarker } from "vue2-leaflet";
    import data from "./HL.json";
    import axios from "axios";

    L.Icon.Default.imagePath = '.';
    L.Icon.Default.mergeOptions({
        iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
        iconUrl: require('leaflet/dist/images/marker-icon.png'),
        shadowUrl: require('leaflet/dist/images/marker-shadow.png'),
    });

	export default {
		name: 'atenderCaso',

		components: {
            LMap, LMarker, LCircleMarker, LPopup, LTileLayer,
        },
		props: ['cas_id'],
		directives: {mask},

		data() {
			return {
				plural: 'Atender Caso',
				singular: 'Formulario',
				usrId: window.Laravel.usr_id,
				usrName: window.Laravel.usr_name,
				registro: [],
				proceso: [{ prc_data: {} }],
				actividad: [{ act_data: {} }],
				caso: [{ cas_data: {} }],
				campos: [],
				camposTexto: '',
				selects: [],
				ejecute: false, 
				foto: null,  
				fotoPath: null,
				fotoDir: null,
				documento: null,  
				documentoPath: null,
				documentoDir: null,
				archivo: {},
				mensaje: "",
				mensaje_tipo: "1",
				mensajes: [],
			}
		},

		mounted() {
			//this.setupLeafletMap();

			this.render();
		},

		methods: {
			asignarCampos() {
				this.campos = this.registro.frm_data_campos;
				this.campos.forEach(campo => {
					var res = this.registro.cas_data_valores.find(item => item.frm_campo === campo.frm_campo);
					if (res) {
						campo.frm_value = res.frm_value;
						campo.frm_deshabilitado = res.frm_deshabilitado;
						campo.frm_deshabilitadoo = res.frm_deshabilitadoo;
					}
				});
			},

			render() {
				var url = "api/caso/" + this.cas_id;
				axios.get(url).then(response => {
					this.registro = response.data.data[0]; //twice data
					this.registro.prc_data = JSON.parse(this.registro.prc_data);
					this.registro.prc_data_campos_clave = JSON.parse(this.registro.prc_data_campos_clave);
					this.registro.act_data = JSON.parse(this.registro.act_data);
					this.registro.cas_data = JSON.parse(this.registro.cas_data);
					this.registro.cas_data_valores = JSON.parse(this.registro.cas_data_valores);
					this.registro.frm_data = JSON.parse(this.registro.frm_data);
					this.registro.frm_data_campos = JSON.parse(this.registro.frm_data_campos);
					//

					this.proceso = this.registro.prc_data;
					this.proceso_campos_clave = this.registro.prc_data_campos_clave;
					this.actividad = this.registro.act_data;
					this.caso = this.registro.cas_data;

					this.asignarCampos();

					// revisa y busca campos tipo SELECT
					this.campos.forEach(function (row) {
						if (row.frm_tipo == 'SELECT') {
							url = row.frm_endpoint;
							let acumulado = [];
							axios.get(url).then(response => {
								let items = response.data.data; //twice data
								items.forEach(function (fila) {
									var filita = Object.values(fila);
									acumulado.push({ "frm_value": filita[0], "frm_etiqueta": filita[1] });
								});
								row.frm_items = acumulado;
							});
						}
					});

					// Adiciona Constantes de PLATAFORMA
					var d = new Date();
					var mm = d.getMonth() + 1;
					this.campos.push({"frm_value": d.getFullYear() + '-' + mm.toString().padStart(2, '0') + '-' + d.getDate().toString().padStart(2, '0'), 
										"frm_tipo":"HIDDEN", "frm_campo":"_FECHA", "frm_etiqueta":"", "frm_deshabilitadoo":false});
					this.campos.push({"frm_value": d.getHours().toString().padStart(2, '0') + ':' + d.getMinutes().toString().padStart(2, '0'), 
										"frm_tipo":"HIDDEN", "frm_campo":"_HORA", "frm_etiqueta":"", "frm_deshabilitadoo":false});
					this.campos.push({"frm_value": d.getFullYear(), "frm_tipo":"HIDDEN", "frm_campo":"_CASO_GESTION", 
										"frm_etiqueta":"", "frm_deshabilitadoo":false});
					this.campos.push({"frm_value": (d.getMonth() + 1).toString().padStart(2, '0'), "frm_tipo":"HIDDEN", "frm_campo":"_CASO_PERIODO", 
										"frm_etiqueta":"", "frm_deshabilitadoo":false});
					this.campos.push({"frm_value": this.cas_id, "frm_tipo":"HIDDEN", "frm_campo":"_CASO_NRO", 
										"frm_etiqueta":"", "frm_deshabilitadoo":false});
					this.campos.push({"frm_value": this.cas_id + '/' + d.getFullYear(), "frm_tipo":"HIDDEN", "frm_campo":"_CASO_NOMBRE", 
										"frm_etiqueta":"", "frm_deshabilitadoo":false});
					this.campos.push({"frm_value": this.usrId, "frm_tipo":"HIDDEN", "frm_campo":"_USR_ID", 
										"frm_etiqueta":"", "frm_deshabilitadoo":false});
					this.campos.push({"frm_value": this.usrName, "frm_tipo":"HIDDEN", "frm_campo":"_USR_NOMBRE", 
										"frm_etiqueta":"", "frm_deshabilitadoo":false});

					// solo para ver los campos en tiempo de ejecución
					this.camposTexto = JSON.stringify(this.campos, null, 4);
				});
			},

			asignarValores() {
				this.campos.forEach(campo => {

					//copia estados DISABLED de componentes
					var elemento = document.getElementById(campo.frm_campo);
					var atributoDisabled = "";
					if (elemento) {
						atributoDisabled = elemento.getAttribute('disabled');
						if (atributoDisabled == null) {
							campo.frm_deshabilitado = "false";
							campo.frm_deshabilitadoo = false;
						} else {
							campo.frm_deshabilitado = "true";
							campo.frm_deshabilitadoo = true;
						}
					}

					var dRes = ""
					if (campo.frm_tipo == "DROPDOWNLIST") {
						var dIndex = campo.frm_items.findIndex(dItem => dItem.frm_value === campo.frm_value)
						if (dIndex > -1) {
							dRes = campo.frm_items[dIndex]["frm_etiqueta"];
						}
					}

					// colecta campos, valores y "estado de deshabilitado"
					var iIndex = this.registro.cas_data_valores.findIndex(item => item.frm_campo === campo.frm_campo);
					if (iIndex > -1) {
						this.registro.cas_data_valores[iIndex].frm_value = campo.frm_value;
						this.registro.cas_data_valores[iIndex].frm_deshabilitado = atributoDisabled == null ? "false" : "true";
						this.registro.cas_data_valores[iIndex].frm_deshabilitadoo = atributoDisabled == null ? false : true;
						if (campo.frm_tipo == "DROPDOWNLIST") {
							this.registro.cas_data_valores[iIndex].frm_value_label = dRes;
						}
					} else {
						var campito = {};
						if (campo.frm_tipo == "DROPDOWNLIST") {
							campito = { 
								"frm_campo": campo.frm_campo, 
								"frm_value": campo.frm_value, 
								"frm_deshabilitado": atributoDisabled == null ? "false" : "true", 
								"frm_deshabilitadoo": atributoDisabled == null ? false : true, 
								"frm_value_label": dRes 
							};
						} else {
							campito = { 
								"frm_campo": campo.frm_campo, 
								"frm_value": campo.frm_value, 
								"frm_deshabilitado": atributoDisabled == null ? "false" : "true", 
								"frm_deshabilitadoo": atributoDisabled == null ? false : true 
							};
						}
						this.registro.cas_data_valores.push(campito);
					}
				});
			},

			actualizarCaso() {
				this.asignarValores();

				// obtiene información de CAMPOS CLAVE
				var campos_clave = '';
				this.proceso_campos_clave.forEach(row => {
					var res = this.registro.cas_data_valores.find(item => item.frm_campo === row.prc_campo_clave);
					if (res) {
						campos_clave += '|' + res.frm_value;
					}
				});

				// recolecta y actualiza datos del caso y campos
				this.caso.cas_nombre_caso = campos_clave.substring(1);
				this.caso.cas_nro_caso = this.cas_id;
				var gRegistro = this.registro;
				gRegistro.cas_data = this.caso;
				gRegistro.cas_usr_id = this.usrId;
				var url = "api/casos/" + this.cas_id;
				axios.put(url, gRegistro).then(response => {
					this.$router.push('/misCasos');
				});
			},

			verCampos() {
				document.getElementById("misCampos").innerHTML = this.camposTexto;
			},

  			doDefinirClass(campo) {
				let res = ''
				if (campo.frm_tipo == 'TITLE' || campo.frm_tipo == 'SUBTITLE' || campo.frm_tipo == 'GRID') {
					res = 'col-md-12';
				} else {
					// 2022.11.11 res = (campo.frm_class == 'col-md-4') ? 'col-md-4' : (campo.frm_class == 'col-md-3') ? 'col-md-3' : 'col-md-6';
					res = campo.frm_class;
				}
				return res;
			},

			isNumber: function(evt) {
				evt = (evt) ? evt : window.event;
				var charCode = (evt.which) ? evt.which : evt.keyCode;
				if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
					evt.preventDefault();
				} else {
					return true;
				}
			},

			ejecutar(cod) {
				eval(cod);
				return true;
			},

			loadError(oError) {
			  throw new URIError("The script " + oError.target.src + " didn't load correctly.");
			},
			//'	 //this.campos.find(item => item.frm_campo === "' + campo + '")' +

			addScript(codigoScriptFormulario) {
				// codigo core
				var codigoScriptCore = `
					// Behavior
					function _show(campo) {
						document.getElementById(campo).style.display="block";
					}

					function _hide(campo) {
						document.getElementById(campo).style.display="none";
					}

					function _enable(campo) {
						document.getElementById(campo).disabled = false;
						//document.getElementById(campo).toggleAttribute("disabled", false);
					}

					function _disable(campo) {
						document.getElementById(campo).disabled = true;
						//document.getElementById(campo).toggleAttribute("disabled", true);
					}

					// Value setValue/getValue 
					function _setValue(campo, value) {
						document.getElementById(campo).value = value;
					}

					function _getValue(campo) {
						return (document.getElementById(campo).value);
					}
					
					// Get Pointer
					function _get(campo) {
						return (document.getElementById(campo));
					}

					// Set Style
					function _setStyle(clase, estilo) {
						const campos = document.querySelectorAll(clase);
						campos.forEach(campo => {
							campo.setAttribute("style", estilo);
						});
					}
				`;
				// codigo formulario
				var newScript = document.createElement("script");
				newScript.onerror = this.loadError;
				document.head.appendChild(newScript);
				newScript.text = codigoScriptCore + codigoScriptFormulario;
			},

            verImagen: function (ruta) {
				window.open(ruta, '_blank');
			},

            getImage(event, frm_campo, index) {
                this.foto = event.target.files[0];
				this.fotoPath = "store/vys2022/" + this.cas_id + "/" + frm_campo + "/" + this.foto.name;
				this.fotoDir = "store/vys2022/" + this.cas_id + "/" + frm_campo;
				let ext = this.foto.name.split('.')[1];
				if ( ext == 'jpg' || ext == 'jpeg' || ext == 'png' || ext == 'JPG' || ext == 'JPEG' || ext == 'PNG' )
                {	
					this.createB64(this.foto, frm_campo, index);
					this.campos[index].frm_value = this.fotoPath;
				}
				else
				{	
					this.$swal({
						title: 'Advertencia!',
						text: 'Debe seleccionar un archivo de tipo imagen con formato .JPG, .JPEG o .PNG',
						icon: 'warning',
						confirmButtonText: 'Ok'
					});
					this.campos[index].frm_value = '';
					this.foto = null;
				}
            },

            createB64(fileObject, frm_campo, index) {
                let _this = this;
                const reader = new FileReader();
                reader.readAsDataURL(fileObject);
                reader.onload = (e) => {
                    _this.foto = e.target.result;
					let gRegistro = {};
					let b64 = _this.foto.split(',');
                    gRegistro.foto = b64[1];
                    gRegistro.fotoPath = _this.fotoPath;
                    gRegistro.fotoDir = _this.fotoDir;
                    let url = "api/subirAdjunto";
                    axios.post(url, gRegistro)
                        .then(function (response) {
							_this.campos[index].frm_value = _this.fotoPath;
							_this.foto = null;
							_this.fotoPath = null;
							_this.fotoDir = null;
							_this.$forceUpdate();
                        })
                        .catch(function (error) {
                            _this.output = error; 
							_this.campos[index].frm_value = '';
							this.$swal({
								title: 'Error!',
								text: 'Archivo no subido, intente nuevamente por favor',
								icon: 'error',
								confirmButtonText: 'Ok'
							});
                        });
                };
                //reader.readAsBinaryString(fileObject);
				//this.fotoPath = "img/vys2022/" + this.cas_id + "/" + frm_campo + "/adjunto.jpg";
            },

			getDocument(event, frm_campo, index) {
                this.documento = event.target.files[0];
				this.documentoPath = "store/vys2022/" + this.cas_id + "/" + frm_campo + "/" + this.documento.name;
				this.documentoDir = "store/vys2022/" + this.cas_id + "/" + frm_campo;
				let ext = this.documento.name.split('.')[1];
				if ( ext == 'pdf' || ext == 'xlsx' || ext == 'xls' || ext == 'docx' || ext == 'doc' || ext == 'pptx' || ext == 'ppt' || ext == 'txt' 
				|| ext == 'PDF' || ext == 'XLSX' || ext == 'XLS' || ext == 'DOCX' || ext == 'DOC' || ext == 'PPTX' || ext == 'PPT' || ext == 'TXT' )
                {	
					this.createB64Document(this.documento, frm_campo, index);
					this.campos[index].frm_value = this.documentoPath;
				}
				else
				{	
					this.$swal({
						title: 'Advertencia!',
						text: 'Debe seleccionar un documento con formato .pdf, .xlsx, .docx , .pptx, .txt o .PNG',
						icon: 'warning',
						confirmButtonText: 'Ok'
					});
					this.campos[index].frm_value = '';
					this.documento = null;
				}
            },

            createB64Document(fileObject, frm_campo, index) {
                let _this = this;
                const reader = new FileReader();
                reader.readAsDataURL(fileObject);
                reader.onload = (e) => {
                    _this.documento = e.target.result;
					let gRegistro = {};
					let b64 = _this.documento.split(',');
                    gRegistro.foto = b64[1];
                    gRegistro.fotoPath = _this.documentoPath;
                    gRegistro.fotoDir = _this.documentoDir;
                    let url = "api/subirAdjunto";
                    axios.post(url, gRegistro)
                        .then(function (response) {
							_this.campos[index].frm_value = _this.documentoPath;
							_this.documento = null;
							_this.documentoPath = null;
							_this.documentoDir = null;
							_this.$forceUpdate();
                        })
                        .catch(function (error) {
                            _this.output = error; 
							_this.campos[index].frm_value = '';
							this.$swal({
								title: 'Error!',
								text: 'Archivo no subido, intente nuevamente por favor',
								icon: 'error',
								confirmButtonText: 'Ok'
							});
                        });
                };
                //reader.readAsBinaryString(fileObject);
				//this.fotoPath = "img/vys2022/" + this.cas_id + "/" + frm_campo + "/adjunto.jpg";
            },

			// GRID functions
			gridAddRow(cols, rows) {
				var fila = [];
				var i = 0;
				cols.forEach(campo => {
					// var res = this.registro.cas_data_valores.find(item => item.frm_campo === campo.frm_campo);
					// if (res) {
					// 	campo.frm_value = res.frm_value;
					// }
					i++;
					var parteFila = {"col_campo": campo.col_campo, "col_value": ""};
					fila.push(parteFila);
				});
				rows.push(fila);
				this.$forceUpdate();
			},

			gridDeleteRow(rows, index) {
				rows.splice(index, 1);
				this.$forceUpdate();
			},

			// ARCHIVAR
			archivarCaso() {
				var cas_data = this.registro.cas_data;
				cas_data.cas_motivo_archivo = this.archivo.cas_motivo_archivo;
				cas_data.cas_descripcion_archivo = this.archivo.cas_descripcion_archivo;
				var gRegistro = {};
				gRegistro.cas_data = cas_data;
				gRegistro.cas_id = this.cas_id;
				gRegistro.cas_usr_id = this.usrId;

				var url = "api/casosArchivar/" + this.cas_id;
				axios.put(url, gRegistro).then(response => {
					this.$router.push('/misCasos');
				});
			},

			// MENSAJES
			doGetMensajes() {
				var that = this;
				that.mensaje = ""; // clean mesaje component
				let url2 = "https://multiservdev.cochabamba.bo/api/v1/bpm/get-tramite";
				var gRegistro2 = {
				    //"conv_usr":"limberth770@gmail.com",
				    //"conv_codigo_recepcion": "PCT-000024",
					"conv_usr":that.caso.cas_ext_usr, 
					"conv_codigo_recepcion":that.caso.cas_ext_codigo,
				    "ext_act_id":222,
					"ext_nodo_id":34,
					"ext_usr_id":1
				}
				let url1 = "https://multiservdev.cochabamba.bo/api/v1/bpm/token";
				let gRegistro1 = {
					"username":"bpm_user@cochabamba.bo", 
					"password":"admin123"
				};
				axios.post(url1, gRegistro1)
					.then(function (response) {
						var res = response.data;
						const config = {
							headers: { Authorization: `Bearer ${res.access_token}` }
						};
						axios.post(url2, gRegistro2, config)
							.then(function (response) {
								that.mensajes = response.data.data; //twice data
							})
							.catch(function (error) {
								that.$swal({
									title: 'Error!',
									text: 'Recuperar Mensajes sin exito, intente nuevamente por favor',
									icon: 'error',
									confirmButtonText: 'Ok'
								});
							});
					})
					.catch(function (error) {
						that.$swal({
							title: 'Error!',
							text: 'Login sin exito, intente nuevamente por favor',
							icon: 'error',
							confirmButtonText: 'Ok'
						});
					});
			},

			doSendMensaje() {
				//ext_estado: -------------------------------------------------------------------------------
				//LIBRE = El tramite esta en el nodo pero ningún funcionario lo esta atendiendo
				//TOMADO = El tramite esta siendo atendido por un funcionario en el nodo correspondiente
				//ext_resultado: ----------------------------------------------------------------------------
				//ENPROCESO = Pendiente
				//APROBADO = Aprobado y archivado, no puede desarchivarse
				//RECHAZADO = Rechazado y archivado, no puede desarchivarse
				//ARCHIVADO = Archivado, puede desarchivarse y devolverse a una actividad del "mismo proceso"

				var that = this;
				let url2 = "https://multiservdev.cochabamba.bo/api/v1/bpm/save-notificacion";
				var gRegistro2 = {
					"ext_tipo_mensaje": that.mensaje_tipo,
					"ext_mensaje": that.mensaje,
					"ext_usr_id": 1,
					"conv_usr":that.caso.cas_ext_usr, 
					"conv_codigo_recepcion":that.caso.cas_ext_codigo,
					"ext_adicionales": {
						"ext_act_id":"222",
						"ext_act_nombre":"Actividad de Prueba",
						"ext_nodo_id":"34",
						"ext_nodo_nombre":"Nodo de Prueba",
						"ext_mensaje":that.mensaje,
						"ext_adjunto_nro":"3",
						"ext_estado":"LIBRE",
						"ext_resultado":"RECHAZADO"
					}
				};

				let url1 = "https://multiservdev.cochabamba.bo/api/v1/bpm/token";
				let gRegistro1 = {"username":"bpm_user@cochabamba.bo", "password":"admin123"};
				axios.post(url1, gRegistro1)
					.then(function (response) {
						var res = response.data;
						const config = {
							headers: { Authorization: `Bearer ${res.access_token}` }
						};
						axios.post(url2, gRegistro2, config)
							.then(function (response) {
								that.mensajes = response.data; //twice data
								// _this.$forceUpdate();
							})
							.catch(function (error) {
								that.$swal({
									title: 'Error!',
									text: 'Enviar Mensaje sin exito, intente nuevamente por favor',
									icon: 'error',
									confirmButtonText: 'Ok'
								});
							});
					})
					.catch(function (error) {
						that.$swal({
							title: 'Error!',
							text: 'Login sin exito, intente nuevamente por favor',
							icon: 'error',
							confirmButtonText: 'Ok'
						});
					});
			},


			/*    
			  MAPA
			*/

            setupLeafletMap() {
                //--- carga Iconos
                this.iconoBase = L.Icon.extend({
                    options: {
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34],
                        shadowSize: [41, 41]
                    }
                });

                this.blackIcon = new this.iconoBase({
                    iconUrl: '/img/_marcadores/vh-2x-black.png',
                    shadowUrl: '/img/_marcadores/marker-shadow.png',
                });

                this.redIcon = new this.iconoBase({
                    iconUrl: '/img/_marcadores/vh-2x-red.png',
                    shadowUrl: '/img/_marcadores/marker-shadow.png',
                });

                this.greenIcon = new this.iconoBase({
                    iconUrl: '/img/_marcadores/vh-2x-green.png',
                    shadowUrl: '/img/_marcadores/marker-shadow.png',
                });

                //--- Define Mapa
                this.map = L.map("mapContainer").setView(this.center, 15);
                L.tileLayer(
                    "https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}",
                    {
                        attribution:
                            'Map data © <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery (c) <a href="https://www.mapbox.com/">Mapbox</a>',
                        maxZoom: 18,
                        id: "mapbox/streets-v11",
                        accessToken:
                            "pk.eyJ1IjoiYWJpZGlzaGFqaWEiLCJhIjoiY2l3aDFiMG96MDB4eDJva2l6czN3MDN0ZSJ9.p9SUzPUBrCbH7RQLZ4W4lQ",
                    }
                ).addTo(this.map);

                L.geoJSON(data, {
                        onEachFeature: this.onEachFeature,
                        style: this.styleMap,
                    })
                    .addTo(this.map);
                    //.on("click", this.onClick);

                this.listarCarreras();
            },

            styleMap(feature) {
                const year = feature.properties.datelisted
                    ? parseInt(feature.properties.datelisted.slice(0, 4))
                    : 0;
                const color = year > 2000 ? "red" : "blue";
                return { color: color };
            },

            onEachFeature(feature, layer) {
                if (feature.properties && feature.properties.name) {
                    layer.bindPopup(feature.properties.name);
                    layer.on('mouseover', () => { layer.openPopup(); });
                    layer.on('mouseout', () => { layer.closePopup(); });
                }
            },
		}
	}

//					function _notice() {
//						//PARTE 1 - ciudadano, tipo_mensaje, mensaje
//						var myHeaders = new Headers();
//						myHeaders.append("Content-Type", "application/json");
//
//						var raw = JSON.stringify({
//							"username": "bpm_user@cochabamba.bo",
//							"password": "admin123"
//						});
//
//						var requestOptions = {
//							method: 'POST',
//							headers: myHeaders,
//							body: raw,
//							redirect: 'follow'
//						};
//
//						var res = {};
//						fetch("https://multiservdev.cochabamba.bo/api/v1/bpm/token", requestOptions)
//						.then(response => response.text())
//						.then(result => _noticePart2(result))
//						.catch(error => console.log('error', error));
//					}
//
//					function _noticePart2(result2) {
//						// PARTE 2
//						var myHeaders = new Headers();
//						var myResult = JSON.parse(result2);
//						console.log("res >>> ", myResult);
//						myHeaders.append("Authorization", "Bearer " + myResult.access_token);
//						myHeaders.append("Content-Type", "application/json");
//
//						var raw = JSON.stringify({
//							"CONV_USR": "andrespradolaguna@gmail.com",
//							"CONV_CODIGO_RECEPCION": "PCT-000025",
//							"ext_act_id": 222,
//							"ext_nodo_id": 34,
//							"ext_usr_id": 1
//						});
//
//						var requestOptions = {
//						method: 'POST',
//						headers: myHeaders,
//						body: raw,
//						redirect: 'follow'
//						};
//
//						fetch("https://multiservdev.cochabamba.bo/api/v1/bpm/get-tramite", requestOptions)
//						.then(response => response.text())
//						.then(result => console.log(result))
//						.catch(error => console.log('error', error));
//					}
</script>
<style>
	img {
		width: 30%;
		margin: auto;
		display: block;
		margin-bottom: 10px;
	}

	button {
		
	}
</style>