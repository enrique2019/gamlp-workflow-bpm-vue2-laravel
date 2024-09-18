<template>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5>{{ plural }}</h5>
                <div class="row col-12">
                    <table class="table table-hover table-striped table-responsive" id="divTable000">
                        <tr v-for="(p, index) in procesos">
                            <td>
                                <span v-for="index in p.cat_codigo.length" :key="index">&nbsp;&nbsp;</span>
                                {{ p.cat_codigo }} - {{ p.cat_descripcion }}
                            </td>
                            <td>{{ p.prc_data.prc_codigo }} - {{ p.prc_data.prc_descripcion }}</td>
                            <td>
                                <button @click="listarRegistros(p.prc_id)" class="btn btn-button btn-primary">
                                    <i class="fa fa-list white" aria-hidden="true"></i> Ver Actividades
                                </button>
                            </td>
                            <td>
                                <button v-if="primer_act_id !== 0 && primer_prc_id == p.prc_id"
                                    class="btn btn-button btn-success" v-on:click="doLimpiar()" data-toggle="modal"
                                    data-target="#modalCrear">
                                    <i class="fa fa-plus white" aria-hidden="true"></i> Crear Caso
                                </button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-hover table-striped table-responsive" id="divTable">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">ORDEN</th>
                            <th scope="col">DESCRIPCIÓN</th>
                            <th scope="col">DURACIÓN HORAS</th>
                            <th scope="col">SIGUIENTE ACTIVIDAD</th>
                            <th scope="col">ESTADO</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(r, index) in registros">
                            <td width="3%" scope="row">{{ r.act_id }}</td>
                            <td>
                                {{ r.act_data.act_orden}}
                                <i v-if="r.tact_codigo == 'I'" class="far fa-circle green" aria-hidden="true"></i>
                                <i v-if="r.tact_codigo == 'A'" class="far fa-square blue" aria-hidden="true"></i>
                                <i v-if="r.tact_codigo == 'B'" class="fas fa-project-diagram blue"
                                    aria-hidden="true"></i>
                                <i v-if="r.tact_codigo == 'F'" class="far fa-circle red" aria-hidden="true"></i>
                            </td>
                            <td>
                                {{ r.act_data.act_descripcion}}
                            </td>
                            <td>
                                {{ r.act_data.act_duracion_horas}}
                            </td>
                            <td>
                                {{ r.act_data.act_siguiente}}
                            </td>
                            <td>
                                <span v-if="r.act_estado === 'A'" class="badge badge-success">ACTIVO</span>
                                <span v-else-if="r.act_estado !== 'A'" class="badge badge-warning">{{
                                    r.act_estado}}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- modalCrear -->
        <div class="modal fade" id="modalCrear" tabindex="-1" role="dialog" aria-labelledby="modalCrear"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title" id="exampleModalLabel">Crear {{ singular }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-6">
                                <label>¿ Confirma crear el {{ singular }} ?</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                        <button type="button" class="btn btn-success" @click="crearCaso()" data-dismiss="modal">Si,
                            crear</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    //import datatables from 'datatables';
    // import Vue from 'vue'
    // import VueRouter from 'vue-router';
    // Vue.use(VueRouter);

    export default {
        name: 'crear-caso',
        data() {
            return {
                plural: 'Crear Caso',
                singular: 'Caso',
                usrId: window.Laravel.usr_id,
                seleccionado: '',
                errores: [],
                registro: {},
                registros: [],
                procesos: [],

                primer_act_id: 0,
                primer_act_nodo_id: 0,
                primer_prc_id: 0,

                dataTable: null,
            }
        },

        mounted() {
            this.listarProcesos();
        },

        methods: {
            listarRegistros(prc_id) {
                let that = this;
                let url = "api/actividades/" + prc_id;
                axios.get(url).then(response => {
                    this.primer_act_id = 0;
                    this.registros = response.data.data; //twice data
                    this.registros.forEach(function (row) {
                        row.act_data = JSON.parse(row.act_data);
                        if (that.primer_act_id == 0 && (row.act_tact_id !== 1 && row.act_tact_id !== 4)) { // la actividad no es inicio ni fin
                            that.primer_act_id = row.act_id;
                            that.primer_act_nodo_id = row.act_nodo_id;
                            that.primer_prc_id = row.prc_id;
                        }
                    });
                });
            },

            listarProcesos() {
                //let url = "api/procesosTodos/";
                var params = {"usr_id": this.usrId};
                let url = "api/procesosXUsrId";
                axios.post(url, params).then(response => {
                    this.procesos = response.data.data; //twice data
                    this.procesos.forEach(function (row) {
                        row.prc_data = JSON.parse(row.prc_data);
                    });
                });
            },

            doLimpiar() {
                this.registro = {
                    cas_gestion: '2022',
                    cas_nro_caso: '',
                    cas_nombre_caso: '',
                };
            },

            crearCaso() {
                let gRegistro = { cas_data: {}, cas_data_valores: {} };

                gRegistro.cas_data.cas_gestion = "2022";
                gRegistro.cas_data.cas_nro = "2020";
                gRegistro.cas_act_id = this.primer_act_id;
                gRegistro.cas_nodo_id = this.primer_act_nodo_id;
                gRegistro.cas_data = this.registro;
                gRegistro.cas_data_valores = [];
                gRegistro.cas_data_campos_clave = [];
                gRegistro.cas_usr_id = this.usrId;
                let that = this;
                let url = "api/casos";
                axios.post(url, gRegistro)
                    .then(function (response) {
                        that.output = response.data;
                        window.location = '/home#/misCasos';
                    });
            },
        },
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