<template>
	<div class="container-fluid">
		<div class="card">
			<div class="card-header">
				<h5>{{ plural }}</h5>
				<div class="row">
					<div class="col-md-2">
						<input type="number" v-model="filtro.cas_nro_caso" class="form-control" placeholder="Nro. Caso">
					</div>
					/
					<div class="col-md-2">
						<select v-model="filtro.cas_gestion" class="form-control" > 
							<option value='2020'>-- Todas --</option>
							<option value='2020'>2020</option>
							<option value='2021'>2021</option>
							<option value='2022'>2022</option>
							<option value='2023'>2023</option>
							<option value='2024'>2024</option>
						</select>
					</div>
					<div class="col-md-2">
						<button class="form-control btn btn-primary" @click="listarRegistros()">
							<i class="fa fa-search white" aria-hidden="true"></i> Buscar
						</button>
					</div>
				</div>
			</div>
			<div class="card-body">
				<table class="table table-hover table-striped table-responsive" id="divTable">
					<thead class="thead-dark">
						<tr>
							<th scope="col">#</th>
							<th scope="col">NODO</th>
							<th scope="col">PROCESO<br>ACTIVIDAD</th>
							<th scope="col">No. CASO</th>
							<th scope="col">CAMPOS CLAVE</th>
							<th scope="col">REGISTRADO<br>MODIFICADO</th>
							<th scope="col">E</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(r, index) in registros">
							<td width="3%" scope="row">{{ r.cas_id }}</td>
							<td scope="row">
								<span class="badge badge-success">{{ r.nodo_codigo}}</span> -
								{{ r.nodo_descripcion}}
							</td>
							<td>
								<strong>{{ r.prc_data.prc_descripcion}}</strong><br>
								<span class="badge badge-dark">{{ r.act_data.act_orden}}</span> -
								{{ r.act_data.act_descripcion}}
							</td>
							<td align="center">
								{{ r.cas_data.cas_nro_caso}} / {{ r.cas_data.cas_gestion}}
							</td>
							<td>
								<span v-html="r.cas_data.cas_nombre_caso"></span>
							</td>
							<td>{{ r.cas_registrado.substr(0,16) }} <br> {{ r.cas_modificado.substr(0,16) }}</td>
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
</template>

<script>
	import datatables from 'datatables';
	import jsPDF from 'jspdf';

	export default {
		name: 'servicios',
		data() {
			return {
				plural: 'Buscar Casos',
				singular: 'Caso',
				usrId: window.Laravel.usr_id,
				seleccionado: '',
				errores: [],
				registro: { cas_data: {} },
				registros: [],
				procesos: [],
				siguiente: { act_data: {} },
				impresiones: [],

				filtro: { cas_nro_caso: '', cas_gestion: '2022'},
				dataTable: null,
			}
		},

		mounted() {
			const current = new Date();
      		const yyyy = current.getFullYear();			
			this.filtro = { cas_nro_caso: '', cas_gestion: yyyy };

			this.dataTable = $('#divTable').DataTable({ });
		},

		created() {

		},

		methods: {
			listarRegistros() {
				let url = "api/buscarCasos";
				let gRegistro = this.filtro;
				axios.post(url, gRegistro).then(response => {
					this.registros = response.data.data; //twice data
					this.registros.forEach(function (row) {
						row.cas_data = JSON.parse(row.cas_data);
						// corregir undefined en cas_nombre_caso
						row.cas_data.cas_nombre_caso = row.cas_data.cas_nombre_caso ? row.cas_data.cas_nombre_caso : ''
						row.cas_data.cas_nombre_caso = row.cas_data.cas_nombre_caso.replaceAll('undefined', '-');

						row.cas_data_valores = JSON.parse(row.cas_data_valores);
						row.prc_data = JSON.parse(row.prc_data);
						row.act_data = JSON.parse(row.act_data);
						row.cas_data.cas_nombre_caso = (row.cas_data.cas_nombre_caso) ? row.cas_data.cas_nombre_caso.replaceAll('|', "<br>") : '';
						row.act_data_reglas = JSON.parse(row.act_data_reglas);
					});
				});
			},

			doVer(index) { // Para tomarCaso() y liberarCaso()
				this.registro = this.registros[index];
			},

			doLimpiar(index) { // Para derivarCaso()
				this.registro = [];
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