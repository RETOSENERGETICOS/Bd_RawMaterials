<template>
    <div>
        <v-dialog v-model="active">
            <v-card>
                <v-card-title>Esta usted seguro?</v-card-title>
                <v-card-actions>
                    <v-btn color="success" text @click.prevent="update">Guardar</v-btn>
                    <v-btn color="error" text @click="active = false">Cancelar/Cancel</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="show" v-if="tool !== null" scrollable>
            <v-card>
                <v-card-title>Herramienta {{ tool.item }}</v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <v-form v-model="valid">
                        <v-row>
                            <v-col cols="4">
                                <v-combobox label="Familia/Family" v-model="tool.family" item-text="name" :items="families" :rules="[rules.required]" clearable item-value="name" disabled></v-combobox>
                            </v-col>
                            <v-col cols="4">
                                <v-combobox label="Pais de suministro/Country of supply" v-model="tool.countrysu" item-text="name" :items="countrysus" clearable item-value="name"></v-combobox>
                            </v-col>
                            <v-col cols="4">
                                <v-combobox label="Pais de origen/Country of origin" v-model="tool.countryor" item-text="name" :items="countryors" :rules="[rules.required]" clearable item-value="name" disabled></v-combobox>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="4">
                                <v-combobox label="Peligrosidad/Hazard" v-model="tool.hazard" item-text="name" :items="hazards" item-value="name" disabled></v-combobox>
                            </v-col>
                            <v-col cols="4">
                                <v-combobox label="Tipo peligrosidad/King of hazard" v-model="tool.king" item-text="name" :items="kings" clearable item-value="name"></v-combobox>
                            </v-col>
                            <v-col cols="4">
                                <v-menu ref="datePickerMenu" v-model="menu" :close-on-content-click="false" offset-y min-width="auto">
                                    <template v-slot:activator="{on, attrs}">
                                        <v-text-field v-model="tool.date" label="Caducidad" v-on="on" v-bind="attrs"></v-text-field>
                                    </template>
                                    <v-date-picker v-model="tool.date" label="Caducidad" no-title></v-date-picker>
                                </v-menu>
                            </v-col>
                        </v-row>
                        <v-row>
                           <v-col cols="4">
                                <v-text-field label="Producto" v-model="tool.product" :rules="[rules.required]"></v-text-field>
                            </v-col>
                            <v-col cols="4">
                                <v-text-field label="Marca" v-model="tool.brand" :rules="[rules.required]"></v-text-field>
                            </v-col>
                            <v-col cols="4">
                                <v-text-field label="Referencia" v-model="tool.reference" :rules="[rules.required]"></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                           <v-col cols="4">
                                <v-text-field label="Caracteristica 1" v-model="tool.spec1" :rules="[rules.required]"></v-text-field>
                            </v-col>
                            <v-col cols="4">
                                <v-text-field label="Caracteristica 2" v-model="tool.spec2" :rules="[rules.required]"></v-text-field>
                            </v-col>
                            <v-col cols="4">
                                <v-text-field label="Caracteristica 3" v-model="tool.spec3" :rules="[rules.required]"></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                           <v-col cols="4">
                                <v-text-field label="Suministrador 1" v-model="tool.supplier1" :rules="[rules.required]"></v-text-field>
                            </v-col>
                            <v-col cols="4">
                                <v-text-field label="Persona de contacto 1" v-model="tool.contact1" :rules="[rules.required]"></v-text-field>
                            </v-col>
                            <v-col cols="4">
                                <v-text-field label="Email 1" v-model="tool.email1" :rules="[rules.required]"></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                           <v-col cols="4">
                                <v-text-field label="Suministrador 2" v-model="tool.supplier2" :rules="[rules.required]"></v-text-field>
                            </v-col>
                            <v-col cols="4">
                                <v-text-field label="Persona de contacto 2" v-model="tool.contact2" :rules="[rules.required]"></v-text-field>
                            </v-col>
                            <v-col cols="4">
                                <v-text-field label="Email 2" v-model="tool.email2" :rules="[rules.required]"></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                           <v-col cols="4">
                                <v-text-field label="Suministrador 3" v-model="tool.supplier3" :rules="[rules.required]"></v-text-field>
                            </v-col>
                            <v-col cols="4">
                                <v-text-field label="Persona de contacto 3" v-model="tool.contact3" :rules="[rules.required]"></v-text-field>
                            </v-col>
                            <v-col cols="4">
                                <v-text-field label="Email 3" v-model="tool.email3" :rules="[rules.required]"></v-text-field>
                            </v-col>
                            <v-col cols="4">
                                <file-pond name="documents" ref="documents" label-idle="Archivos" accepted-file-types="application/pdf" :disabled="true"></file-pond>
                            </v-col>
                        </v-row>
                    </v-form>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-btn text color="success" @click="active = true">Guardar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import { getToken } from "../../lib/auth";
import { required } from "../../lib/rules";
import vueFilePond, { setOptions } from "vue-filepond";
import "filepond/dist/filepond.min.css"
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type"
const FilePond = vueFilePond(FilePondPluginFileValidateType);

export default {
    name: "show",
    data: () => ({
        active: false,
        loading: false,
        valid: false,
        show: false,
        tool: null,
        movingQuantity: 0,
        menu: false,
        rules : { required: required },
        families: [],
        countrysus: [],
        countryors: [],
        hazards: [],
        kings: [],
    }),
    methods: {
        async update() {
            this.active = false
            this.tool = { ...this.tool, movingQuantity: this.movingQuantity }
            const response = await axios.put(`/api/tools/${this.tool.id}`, this.tool, getToken())
            if (response.status === 200) {
                const newItem = {
                    id: this.tool.id,
                    item: this.tool.item,
                    family: this.tool.family,
                    countrysu: this.tool.countrysu,
                    countryor: this.tool.countryor,
                    hazard: this.tool.hazard,
                    king: this.tool.king
                }
                this.$emit('updated', newItem)
                this.show = false
                this.movingQuantity = 0
            }

        },
        async open(tool) {
            const response = await axios.get(`/api/tools/${tool.id}`, getToken())
            this.tool = response.data
            this.show = true
        }
    },
    computed: {
        disabled() {
            if (this.tool.hasValidation && this.tool.calibrationExpiration === null) {
                return true
            }
            return !this.valid
        }
    },
    async mounted() {
        setOptions({
            server: {
                url: '/api/uploads',
                withCredentials: true,
                headers: {
                    Authorization: `Bearer ${localStorage.getItem('token')}`
                }
            }
        })
        await axios.get('/api/families', getToken()).then(response => this.families = response.data)
        await axios.get('/api/countrysus', getToken()).then(response => this.countrysus = response.data)
        await axios.get('/api/countryors', getToken()).then(response => this.countryors =  response.data )
        await axios.get('/api/hazards', getToken()).then(response => this.hazards = response.data)
        await axios.get('/api/kings', getToken()).then(response => this.kings = response.data)
        this.loading = false
    },
    components: {
        FilePond
    }
}
</script>
