<template>
    <div>
        <v-snackbar v-model="snackbar.active" :timeout="1500" :color="snackbar.color">{{ snackbar.message }}</v-snackbar>
        <show ref="showTool" @updated="updateItem"/>
        <v-data-table :headers="headers" :items="items" @click:row="viewTool" :search="search">
            <template #top>
                <v-toolbar flat>
                    <v-toolbar-title>Suministros</v-toolbar-title>
                    <v-divider inset vertical class="mx-4"></v-divider>
                    <v-spacer></v-spacer>
                    <v-text-field v-model="search" label="Buscar/Search" hide-details></v-text-field>
                </v-toolbar>
            </template>
            <template #item.has_validation="{item}">{{ item.has_validation ? 'Si' : 'No' }}</template>
        </v-data-table>
    </div>
</template>

<script>
import { getToken } from "../../lib/auth";
import show from "./show";

export default {
    name: "edit",
    data: () => ({
        snackbar: { active: false, message: null, color: 'success' },
        dialog: false,
        tool: null,
        items: [],
        search: null,
        headers: [
            {text: 'ITEM', value: 'item'},
            {text: 'Familia/Family', value: 'family.name'},
            {text: 'Pais de Suministro/Country of Supply', value: 'countrysu.name'},
            {text: 'Pais de Origen/Country of Origin', value: 'countryor.name'},
            {text: 'Peligrosidad/Hazard', value: 'hazard.name'},
            {text: 'Tipo de Peligrosidad/King of Hazard', value: 'king.name'},
            {text: 'Caducidad/Due', value: 'date.name'},
            {text: '', value: 'edit'}
        ]
    }),
    methods: {
        updateItem(item) {
            const itemIndex = this.items.findIndex(stream => stream.id === item.id)
            this.items.splice(itemIndex, 1, item)
            this.snackbar = { active: true, message: 'Registro actualizado', color: 'success' }
        },
        async viewTool(data) {
            await this.$refs.showTool.open(data)
        }
    },
    async created() {
        const response = await axios.get('/api/tools', getToken())
        if (response.status === 200) {
            this.items = response.data
        }
    },
    components: {
        show
    }
}
</script>
