<template>
    <v-expansion-panels v-model="panel">
        <v-expansion-panel>
            <v-expansion-panel-header>Filtros/Filters</v-expansion-panel-header>
            <v-expansion-panel-content>
                <v-row>
                    <v-col>
                        <active-filters />
                    </v-col>
                    <v-col>
                        <v-btn color="success" text @click.prevent="search">Aplicar filtros/Apply field <v-icon>mdi-download</v-icon></v-btn>
                    </v-col>
                    <v-col>
                        <v-btn color="cyan" text @click.prevent="history">Consultar Historial/History <v-icon>mdi-history</v-icon></v-btn>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col cols="4" v-if="filters.family.active"><v-select v-model="filter.family" label="Familia" :items="families" item-text="name" return-object clearable></v-select></v-col>
                    <v-col cols="4" v-if="filters.countrysu.active"><v-select v-model="filter.countrysu" label="Pais de suministro" :items="countrysus" item-text="name" return-object clearable></v-select></v-col>
                    <v-col cols="4" v-if="filters.countryor.active"><v-select v-model="filter.countryor" label="Pais de origen" :items="countryors" item-text="name" return-object clearable></v-select></v-col>
                    <v-col cols="4" v-if="filters.hazard.active"><v-select v-model="filter.hazard" label="Peligrosidad" :items="hazards" item-text="name" return-object clearable></v-select></v-col>
                    <v-col cols="4" v-if="filters.king.active"><v-select v-model="filter.king" label="Tipo peligrosidad" :items="kings" item-text="name" return-object clearable></v-select></v-col>
                   
                    <v-col cols="4" v-if="filters.date.active">
                        <v-menu ref="datePickerMenu" v-model="menu" :close-on-content-click="false" offset-y min-width="auto">
                            <template v-slot:activator="{on, attrs}">
                                <v-text-field v-model="filter.date" label="Caducidad" v-on="on" v-bind="attrs"></v-text-field>
                            </template>
                            <v-date-picker v-model="filter.date" label="Caducidad" no-title></v-date-picker>
                        </v-menu>
                    </v-col>
                    <v-col cols="4" v-if="filters.product.active"><v-text-field v-model="filter.product" label="Localizacion principal" clearable></v-text-field></v-col>
                    <v-col cols="4" v-if="filters.brand.active"><v-text-field v-model="filter.brand" label="Localizacion de estante" clearable></v-text-field></v-col>
                    <v-col cols="4" v-if="filters.spec1.active"><v-text-field v-model="filter.spec1" label="Estante" clearable></v-text-field></v-col>
                    <v-col cols="4" v-if="filters.spec2.active"><v-text-field v-model="filter.spec2" label="Medida" clearable></v-text-field></v-col>
                    <v-col cols="4" v-if="filters.spec3.active"><v-text-field v-model="filter.spec3" label="Inventario minimo" clearable></v-text-field></v-col>
                    <v-col cols="4" v-if="filters.supplier1.active"><v-text-field v-model.number="filter.supplier1" label="Cantidad" clearable></v-text-field></v-col>
                    <v-col cols="4" v-if="filters.contact1.active"><v-text-field v-model="filter.contact1" label="Descripcion" clearable></v-text-field></v-col>
                    <v-col cols="4" v-if="filters.email1.active"><v-text-field v-model="filter.email1" label="Modelo" clearable></v-text-field></v-col>
                    <v-col cols="4" v-if="filters.supplier2.active"><v-text-field v-model.number="filter.supplier2" label="Cantidad" clearable></v-text-field></v-col>
                    <v-col cols="4" v-if="filters.contact2.active"><v-text-field v-model="filter.contact2" label="Descripcion" clearable></v-text-field></v-col>
                    <v-col cols="4" v-if="filters.email2.active"><v-text-field v-model="filter.email2" label="Modelo" clearable></v-text-field></v-col>
                    <v-col cols="4" v-if="filters.supplier3.active"><v-text-field v-model.number="filter.supplier3" label="Cantidad" clearable></v-text-field></v-col>
                    <v-col cols="4" v-if="filters.contact3.active"><v-text-field v-model="filter.contact3" label="Descripcion" clearable></v-text-field></v-col>
                    <v-col cols="4" v-if="filters.email3.active"><v-text-field v-model="filter.email3" label="Modelo" clearable></v-text-field></v-col>
                    <v-col cols="4" v-if="filters.item.active"><v-text-field v-model="filter.item" label="Item" clearable></v-text-field></v-col>
                    <v-col cols="4" v-if="filters.user.active"><v-select v-model="filter.user" label="Usuario" :items="users" item-text="email" return-object clearable></v-select></v-col>
        
                </v-row>
            </v-expansion-panel-content>
        </v-expansion-panel>
    </v-expansion-panels>
</template>

<script>
import { getToken } from "../../lib/auth";
import activeFilters from "./activeFilters";
import { mapGetters } from "vuex"

export default {
    name: "filters",
    data: () => ({
        panel: 0,
        families: [{id: 0, name: 'TODOS'}],
        countrysus: [{id: 0, name: 'TODOS'}],
        countryors: [{id: 0, name: 'TODOS'}],
        hazards: [{id: 0, name: 'TODOS'}],
        kings: [{id: 0, name: 'TODOS'}],
        users: [{id: 0, email: 'TODOS'}],
        menu: false,
        filter: {
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
            item: null,
            user: null,
        },
        historyHeaders: [
            {text: 'Item', value: 'tool.item'},
            {text: 'Familia', value: 'family.name'},
            {text: 'Fecha', value: 'created_at'},
            {text: 'Ejecutor', value: 'user.email'},
            {text: 'Actividad', value: 'comment'},
            {text: 'Informacion Actual', value: 'after'},
            {text: 'Informacion Anterior', value: 'before'}
        ]
    }),
    methods: {
        async history() {
            await this.$store.dispatch('filters/setHistoryMode', { value: true })
            this.$emit('loading', true)
            const response = await axios.get('/api/history', getToken())
            if (response.status === 200) {
                const items = response.data.map(item => {
                    return {
                        ...item,
                        before: JSON.parse(item.before),
                        after: JSON.parse(item.after)
                    }
                })
                await this.$store.dispatch('filters/setHistoryItems', { items })
            }
            this.$emit('loading', false)
        },
        async search() {
            this.$emit('loading', true)
            await this.$store.dispatch('filters/setHistoryMode', { value: false })
            const query = {}
            const activeFilters = Object.keys(this.filters).filter(filter => this.filters[filter].active)
            for (let key of activeFilters) {
                if (!_.isEmpty(this.filter[key])) {
                    query[key] = this.filter[key]
                }
            }
            const response = await axios.post('/api/search', query, getToken())
            if (response.status === 200) {
                await this.$store.dispatch('filters/setItems', { items: response.data })
            }
            this.$emit('loading', false)
        }
    },
    computed: {
        ...mapGetters('filters', ['filters','activeFilters'])
    },
    created() {
        axios.get('/api/families', getToken())
            .then(response => {
                this.families = this.families.concat(response.data)
                this.filter.family = this.families[0]
            })
        axios.get('/api/countrysus', getToken())
            .then(response => {
                this.countrysus = this.countrysus.concat(response.data)
                this.filter.countrysu = this.countrysus[0]
            })
        axios.get('/api/countryors', getToken())
            .then(response => {
                this.countryors = this.countryors.concat(response.data)
                this.filter.countryor = this.countryors[0]
            })
        axios.get('/api/hazards', getToken())
            .then(response => {
                this.hazards = this.hazards.concat(response.data)
                this.filter.hazard = this.hazards[0]
            })
        axios.get('/api/kings', getToken())
            .then(response => {
                this.kings = this.kings.concat(response.data)
                this.filter.king = this.kings[0]
            })
        axios.get('/api/users', getToken())
          .then(response => {
              this.users = this.users.concat(response.data)
          })
    },
    components: {
        activeFilters
    }

}
</script>

<style scoped>

</style>
