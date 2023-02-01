export default {
    namespaced: true,
    state: {
        items: [],
        filters: {
            family: { text: 'Familia', value: 'family.name', active: true, key: 'family' },
            countrysu: { text: 'Pais de suministro', value: 'countrysu.name', active: true, key: 'countrysu' },
            countryor: { text: 'Pais de origen', value: 'countryor.name', active: true, key: 'countryor' },
            hazard: { text: 'Peligrosidad', value: 'hazard.name', active: true, key: 'hazard' },
            king: { text: 'Tipo peligrosidad', value: 'king.name', active: true, key: 'king' },
            date: { text: 'Caducidad', value: 'date', active: false, key: 'date' },
            product: { text: 'Producto', value: 'product', active: false, key: 'product' },
            brand: { text: 'Marca', value: 'brand', active: false, key: 'brand' },
            reference: { text: 'Referencias', value: 'reference', active: false, key: 'reference' },
            spec1: { text: 'Caracteristica 1', value: 'spec1', active: false, key: 'spec1' },
            spec2: { text: 'Caracteristica 2', value: 'spec2', active: false, key: 'spec2' },
            spec3: { text: 'Caracteristica 3', value: 'spec3', active: false, key: 'spec3' },
            supplier1: { text: 'Suministrador 1', value: 'supplier1', active: false, key: 'supplier1' },
            contact1: { text: 'Persona de contacto 1', value: 'contact1', active: false, key: 'contact1' },
            email1: { text: 'Email 1', value: 'email1', active: false, key: 'email1' },
            supplier2: { text: 'Suministrador 2', value: 'supplier2', active: false, key: 'supplier2' },
            contact2: { text: 'Persona de contacto 2', value: 'contact2', active: false, key: 'contact2' },
            email2: { text: 'Email 2', value: 'email2', active: false, key: 'email2' },
            supplier3: { text: 'Suministrador 3', value: 'supplier3', active: false, key: 'supplier3' },
            contact3: { text: 'Persona de contacto 3', value: 'contact3', active: false, key: 'contact3' },
            email3: { text: 'Email 3', value: 'email3', active: false, key: 'email3' },
            item: { text: 'Item', value: 'item', active: false, key: 'item' },
            user: { text: 'Usuario', value: 'user.name', active: false, key: 'user_id' },
        },
        historyMode: false,
        historyItems: [],
    },
    mutations: {
        setFilters(state, { filters }) {
            state.filters = filters
        },
        setItems(state, { items }) {
            state.items = items
        },
        setHistoryMode(state, { value }) {
            state.historyMode = value
        },
        setHistoryItems(state, { items }) {
            state.historyItems = items
        }
    },
    actions: {
        setHistoryItems({ commit }, { items }) {
            commit('setHistoryItems', { items })
        },
        setHistoryMode({ commit }, { value }) {
            commit('setHistoryMode', { value })
        },
        setFilters({ commit }, { filters }) {
            commit('setFilters', { filters })
        },
        setItems({ commit }, { items }) {
            commit('setItems', { items })
        }
    },
    getters: {
        historyItems: state => {
            return state.historyItems
        },
        historyMode: state => {
            return state.historyMode
        },
        activeFilters: state => {
            const activeFiltersKeys = Array.from(Object.keys(state.filters)).filter(key => state.filters[key].active)
            return activeFiltersKeys.map(key => state.filters[key])
        },
        filters: state => {
            return JSON.parse(JSON.stringify(state.filters))
        },
        items: state => {
            return state.items
        }
    }
}
