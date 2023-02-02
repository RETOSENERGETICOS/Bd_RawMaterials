<template>
    <div>
        <v-dialog v-model="active">
            <v-card>
                <v-card-title>¿Está usted seguro de guardar?/Confirm?</v-card-title>
                <v-card-actions>
                    <v-btn color="success" text @click.prevent="createTool">Guardar/Save</v-btn>
                    <v-btn color="error" text @click="active = false">Cancelar/Cancel</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="snackbar.active" :color="snackbar.color" :timeout="1500" > {{ snackbar.message }}</v-snackbar>
        <v-btn @click="active = true" :disabled="disabled" :loading="loading">Guardar/Save</v-btn>
        <v-form v-model="valid">
            <div class="form-container">
                <div class="form-column">
                    <div class="form-row">
                        <v-combobox v-if="verifyAccess([1])" v-model.trim="tool.family" label="Familia" :items="familys" item-text="name" :rules="[rules.required]" clearable item-value="name"></v-combobox>
                        <v-select v-else v-model.trim="tool.family" label="Familia" :items="familys" item-text="name" :rules="[rules.required]" clearable item-value="name"></v-select>
                    </div>
                    <div class="form-row">
                        <v-combobox v-if="verifyAccess([1])" v-model.trim="tool.countrysu" label="Pais de suministro" :items="countrysus" item-text="name" :rules="[rules.required]" clearable item-value="name"></v-combobox>
                        <v-select v-else v-model.trim="tool.countrysu" label="Pais de suministro" :items="countrysus" item-text="name" :rules="[rules.required]" clearable item-value="name"></v-select>
                    </div>
                    <div class="form-row">
                        <v-combobox v-if="verifyAccess([1])" v-model.trim="tool.countryor" label="Pais de origen" :items="countryors" item-text="name" :rules="[rules.required]" clearable item-value="name"></v-combobox>
                        <v-select v-else v-model.trim="tool.countryor" label="Pais de origen" :items="countryors" item-text="name" :rules="[rules.required]" clearable item-value="name"></v-select>
                    </div>
                    <div class="form-row">
                        <v-combobox v-if="verifyAccess([1])" v-model.trim="tool.hazard" label="Peligrosidad" :items="hazards" item-text="name" :rules="[rules.required]" clearable item-value="name"></v-combobox>
                        <v-select v-else v-model.trim="tool.hazard" label="Peligrosidad" :items="hazards" item-text="name" :rules="[rules.required]" clearable item-value="name"></v-select>
                    </div>
                    <div class="form-row">
                        <v-combobox v-if="verifyAccess([1])" v-model.trim="tool.king" label="Tipo peligrosidad" :items="kings" item-text="name" :rules="[rules.required]" clearable item-value="name"></v-combobox>
                        <v-select v-else v-model.trim="tool.king" label="Tipo peligrosidad" :items="kings" item-text="name" :rules="[rules.required]" clearable item-value="name"></v-select>
                    </div>
                    <div class="form-row">
                        <v-menu ref="datePickerMenu" v-model="menu" :close-on-content-click="false" offset-y min-width="auto">
                            <template v-slot:activator="{on, attrs}">
                                <v-text-field v-model="tool.installation" label="F Instalacion/Set Up D" v-on="on" v-bind="attrs"></v-text-field>
                            </template>
                            <v-date-picker v-model="tool.installation" label="F Instalacion/Set Up D" no-title></v-date-picker>
                        </v-menu>
                    </div>
                    <div class="form-row">
                        <v-text-field v-model="tool.product" label="Producto"></v-text-field>
                    </div>
                </div>
                <div class="form-column">
                    <div class="form-row">
                        <v-text-field v-model="tool.brand" label="Marca"></v-text-field>
                    </div>
                    <div class="form-row">
                        <v-text-field v-model="tool.reference" label="Referencia"></v-text-field>
                    </div>
                    <div class="form-row">
                        <v-text-field v-model="tool.spec1" label="Caracteristica 1"></v-text-field>
                    </div>
                    <div class="form-row">
                        <v-text-field v-model="tool.spec2" label="Caractristica 2"></v-text-field>
                    </div>
                    <div class="form-row">
                        <v-text-field v-model="tool.spec3" label="Caracteristica 3"></v-text-field>
                    </div>
                    <div class="form-row">
                        <v-text-field v-model="tool.supplier1" label="Suministrador 1"></v-text-field>
                    </div>
                    <div class="form-row">
                        <v-text-field v-model="tool.contact1" label="Persona de contacto 1"></v-text-field>
                    </div>
                    <div class="form-row">
                        <v-text-field v-model="tool.email1" label="Email 1"></v-text-field>
                    </div>
                </div>
                <div class="form-column">
                    <div class="form-row">
                        <v-text-field v-model="tool.supplier2" label="Suministrador 2"></v-text-field>
                    </div>
                    <div class="form-row">
                        <v-text-field v-model="tool.contact2" label="Persona de contacto 2"></v-text-field>
                    </div>
                    <div class="form-row">
                        <v-text-field v-model="tool.email2" label="Email 2"></v-text-field>
                    </div>
                     <div class="form-row">
                        <v-text-field v-model="tool.supplier3" label="Suministrador 3"></v-text-field>
                    </div>
                    <div class="form-row">
                        <v-text-field v-model="tool.contact3" label="Persona de contacto 3"></v-text-field>
                    </div>
                     <div class="form-row">
                        <v-text-field v-model="tool.email3" label="Email 3"></v-text-field>
                    </div>
                    <div class="form-row">
                        <file-pond name="documents" ref="documents" label-idle="Archivos" accepted-file-types="application/pdf" @processfile="onProcessFile" :allow-multiple="true"></file-pond>
                    </div>
                </div>
            </div>
        </v-form>
    </div>
</template>

<script>
import { getToken, verifyAccess } from "../../lib/auth";
import { required } from "../../lib/rules";
import vueFilePond, { setOptions } from "vue-filepond";
import "filepond/dist/filepond.min.css"
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type"
const FilePond = vueFilePond(FilePondPluginFileValidateType);

export default {
    name: "create",
    data: () => ({
        snackbar: { active: false, message: null, color: 'success' },
        active: false,
        loading: true,
        menu: false,
        valid: false,
        rules : { required: required },
        families: [],
        countrysus: [],
        countryors: [],
        hazards: [],
        kings: [],
        tool: {
            family: null,
            countrysu: null,
            countryor: null,
            hazard: null,
            king: null,
            date: null,
            product: null,
            brand: null,
            reference: null,
            spec1: null,
            spec2: null,
            spec3: null,
            supplier1: null,
            contact1: null,
            email1: null,
            supplier2: null,
            contact2: null,
            email2: null,
            supplier3: null,
            contact3: null,
            email3: null,
            documents: []
        }
    }),
    methods: {
        verifyAccess(roles) {
            return verifyAccess(roles)
        },
        async onProcessFile(error, file) {
            if (error === null) {
                this.tool.documents.push(file.serverId)
            }
        },
        async createTool() {
            this.active = false
            this.loading = true
            const response = await axios.post('/api/tools', this.tool, getToken())
            if (response.status === 200) {
                this.snackbar = {
                    active: true,
                    message: 'Equipo registrado',
                    color: 'success'
                }
                this.clearForm()
            } else {
                this.snackbar = {
                    active: true,
                    message: 'Error al registrar',
                    color: 'error'
                }
            }
            this.loading = false
        },
        clearForm() {
            this.tool = {
                family: null,
                countrysu: null,
                countryor: null,
                hazard: null,
                king: null,
                date: null,
                product: null,
                brand: null,
                reference: null,
                spec1: null,
                spec2: null,
                spec3: null,
                supplier1: null,
                contact1: null,
                email1: null,
                supplier2: null,
                contact2: null,
                email2: null,
                supplier3: null,
                contact3: null,
                email3: null,
                documents: []
            }
            this.$refs.documents.removeFiles()
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
    async created() {
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
        await axios.get('/api/countryors', getToken()).then(response => this.countryors = response.data)
        await axios.get('/api/hazards', getToken()).then(response => this.hazards = response.data)
        await axios.get('/api/kings', getToken()).then(response => this.kings = response.data)
        this.loading = false
    },
    components: {
        FilePond
    }

}
</script>

<style scoped>
.form-container {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 1rem;
}
.form-row {
    margin: 1rem 0;
}
</style>
