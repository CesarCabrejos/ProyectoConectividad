<template>
  <v-content>
    <v-row align="center" class="pa-5 align-center">
      <v-col cols="4">
        <v-alert
          v-model="alert"
          text
          prominent
          type="error"
          icon="mdi-cloud-alert"
          close-text="Close Alert"
          dismissible
          >Ocurrio un error.</v-alert
        >
      </v-col>
    </v-row>
    <v-row class="pa-5 align-center">
      <v-col>
        <v-btn
          fab
          large
          dark
          color="blue darken-3"
          @click="dialogEjemplo = true"
        >
          <v-icon>mdi-plus</v-icon>
        </v-btn>
      </v-col>
      <v-col cols="11">
        <h2 class="font-weight-regular text-center">Mantenimiento Local</h2>
      </v-col>
    </v-row>
    <v-dialog v-model="dialogEjemplo" persistent scrollable max-width="60vw">
      <v-card>
        <v-card-title class="headline indigo darken-4">
          <span v-if="edit" class="headline" style="color: white"
            >Editar Local</span
          >
          <span v-else-if="ver" class="headline" style="color: white"
            >Ver Local</span
          >
          <span v-else class="headline" style="color: white"
            >Nuevo Local</span
          >
        </v-card-title>
        <v-card-text>
          <v-form ref="form" v-model="valid" lazy-validation>
            <v-row>
              <v-col cols="6">
                <v-text-field
                  :disabled="ver"
                  v-model="Nombre"
                  label="Nombre"
                  prepend-icon="mdi-store"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  :disabled="ver"
                  v-model="Telefono"
                  label="Teléfono"
                  type="number"
                  maxlength="9"
                  prepend-icon="mdi-phone"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  :disabled="ver"
                  v-model="Direccion"
                  label="Dirección Local"
                  prepend-icon="mdi-map-marker"
                  required
                ></v-text-field>
              </v-col>
            </v-row>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            v-if="!ver"
            color="indigo darken-4"
            text
            @click="(dialogEjemplo = false), limpiar()"
            >Cancelar</v-btn
          >
          <v-btn
            v-if="!ver"
            :loading="saveLoading"
            :disabled="saveLoading"
            color="indigo darken-4"
            class="ma-2 white--text"
            depressed
            @mousedown="validate"
            @click="executeEventClick"
            >Guardar</v-btn
          >
          <v-btn
            v-if="ver"
            color="indigo darken-4"
            text
            @click="(dialogEjemplo = false), limpiar()"
            >Cancelar</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-data-table
      background= "blue darken-3"
      :headers="headers"
      :items="locales"
      :search="search"
      :item-class="itemFilaColor"
    >
      <template v-slot:[`item.index`]="{ item }">
        {{ locales.indexOf(item) + 1 }}
      </template>
      <template v-slot:[`item.actions`]="{ item }">
        <v-tooltip bottom>
          <template v-slot:activator="{ on, attrs }">
            <v-icon
              v-bind="attrs"
              v-on="on"
              class="mr-2"
              color="blue-grey"
              @click="showactualizar(item)"
              >mdi-pencil</v-icon
            >
          </template>
          <span>Editar</span>
        </v-tooltip>
        <v-tooltip bottom>
          <template v-slot:activator="{ on, attrs }">
            <v-icon
              v-bind="attrs"
              v-on="on"
              class="mr-2"
              :color="item.Vigencia ? 'red lighten-1' : 'green' "
              @click="deleteLocal(item)"
              >{{
                item.Vigencia ? "mdi-close-circle-outline" : "mdi-checkbox-marked-circle-outline"
              }}</v-icon
            >
          </template>
          <span>{{ item.Vigencia ? "Dar de baja" : "Dar de alta" }}</span>
        </v-tooltip>
      </template>
    </v-data-table>
  </v-content>
</template>

<script>
import { get, enviarConArchivos, patch } from "../api/api";
import Swal from "sweetalert2";

export default {
  //components: {
  //  TablesMostrar: () => import("../components/TablesMostrar"),
  //},
  data() {
    return {
      edit: false,
      ver: false,
      alert: false,
      valid: true,
      saveLoading: false,
      dialogEjemplo: false,
      itemsEmpresa: [],
      headers:[
        {text: "Nº", value: "index", width: "10%", class: 'light blue darken-4 white--text' },
        {
          text: "Nombre",
          align: "start",
          // sortable: false,
          value: "Nombre",
          width: "25%",
          class: 'light blue darken-4 white--text',
        },
        { text: "Dirección", value: "Direccion", width: "30%", class: 'light blue darken-4 white--text' },
        { text: "Teléfono", value: "Telefono", width: "20%", class: 'light blue darken-4 white--text' },
        { text: "Acciones", value: "actions", width: "15%", class: 'light blue darken-4 white--text' },
      ],
      headers2: [
        {
          text: "Nombre",
          align: "start",
          // sortable: false,
          value: "Nombre",
        },
        { text: "Dirección", value: "Direccion" },
        { text: "Teléfono", value: "Telefono" },
        { text: "Acciones", value: "actions" },
      ],
      options2: [
        {
          name: "Ver",
          icon: "mdi-eye",
          function: this.showactualizar,
        },
        {
          name: "Cambiar vigencia",
          icon: "mdi-check-box-outline",
          function: this.deleteLocal,
        },
      ],
      options: [
        {
          name: "Editar",
          icon: "mdi-pencil",
          function: this.showactualizar,
        },
        {
          name: "Eliminar",
          icon: "mdi-delete",
          function: this.deleteLocal,
        },
      ],
      locales: [],
      search: "",
      Codigo: "",
      Nombre: "",
      Direccion: "",
      Telefono: "",
    };
  },
  methods: {
    validate() {
      this.$refs.form.validate();
    },
    limpiar() {
      this.codigo = "";
      this.Nombre = "";
      this.Direccion = "";
      this.Telefono = "";
      this.ver = false;
    },
    executeEventClick() {
      if (this.edit === false) {
        this.registerLocal();
      } else {
        this.actualizar();
      }
    },
    itemFilaColor: function (item) {
      return item.Vigencia ? "black--text" : "red--text";
    },
    assembleLocal() {
      let form = new FormData();
      form.append("Codigo", this.codigo);
      form.append("Nombre", this.Nombre);
      form.append("Direccion", this.Direccion);
      form.append("Telefono", this.Telefono);
      form.append("CodigoEmpresa", this.CodigoEmpresa);
      return form;
    },

    registerLocal() {
      if (this.valid == false) return;
      this.saveLoading = true;
      enviarConArchivos("locales", this.assembleLocal()).then(() => {
        this.saveLoading = false;
        this.dialogEjemplo = false;
        //this.$refs.localTable.fetchData();
        this.limpiar();
        Swal.fire({
          title:"Sistema",
          text:"Local registrado exitosamente.",
          icon:"éxito",
          confirmButtonText:"Aceptar",
          timer: 2500,
        });
        this.actualizarLocales();
      });
    },
    actualizar() {
      if (this.valid == false) return;
      this.saveLoading = true;
      enviarConArchivos("locales/" + this.editId, this.assembleLocal())
        .then(() => {
          this.saveLoading = false;
          this.editId = null;
          this.dialogEjemplo = false;
          this.actualizarLocales();
          //this.$refs.localTable.fetchData();
          this.limpiar();
            Swal.fire({
          title:"Sistema",
          text:"Local actualizado exitosamente.",
          icon:"éxito",
          confirmButtonText:"Aceptar",
          timer: 2500,
        });
        })
        .catch(() => {
          this.alert = true;
        });
    },
    showactualizar(Local) {
      this.edit = true;
      this.editId = Local.Codigo;
      this.mostrarLocal(Local.Codigo).then(() => {
        this.dialogEjemplo = true;
      });
    },
    verLocal(Local) {
      this.ver = true;
      this.mostrarLocal(Local.Codigo).then(() => {
        this.dialogEjemplo = true;
      });
    },
    deleteLocal(Local) {
      patch("locales/" + Local.Codigo)
        .then((data) => {
          console.log(data);
            if(data == false){
              Swal.fire({
                title:"Sistema",
                text:"Local dado de alta exitosamente.",
                icon: "éxito",
                confirmButtonText: "Aceptar",
                timer: 2500,
              });
            }else{
              Swal.fire({
                title:"Sistema",
                text:"Local dado de baja exitosamente.",
                icon: "éxito",
                confirmButtonText: "Aceptar",
                timer: 2500,
              });
            }
          this.actualizarLocales();
        })
        //.catch(() => {
          //this.alert = true;
        //});
        .catch (() => {});
    },
    cambiarEstadoLocal(Local) {
      patch("locales/" + Local.Codigo)
        .then(() => {
          this.actualizarLocales();
        })
        .catch(() => {
          this.alert = true;
        });
    },

    actualizarLocales() {
      get("locales").then((data) => {
        this.locales = data;
      });
    },

    mostrarEmpresas() {
      get("Local/Empresas").then((data) => {
        this.itemsEmpresa = data;
        console.log(data);
      });
    },

    async mostrarLocal(codigo) {
      const Local = await get("locales/" + codigo);
      this.codigo = Local.codigo;
      this.Nombre = Local.Nombre;
      this.Direccion = Local.Direccion;
      this.Telefono = Local.Telefono;
    },
  },
  created() {
    this.actualizarLocales();
    this.mostrarEmpresas();
  },
};

</script>

<style>
</style>
<style></style>