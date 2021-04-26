<template>
    <selectize v-model="selected">
        <option :value="null">Please select</option>
        <option v-for="(occupation, index) in occupations" :key="index" :value="occupation.code">{{ occupation.title }}</option>
    </selectize>
</template>

<script>
    import Selectize from 'vue2-selectize';

    export default {
        name: 'select-occupation',
        components: {
            Selectize
        },
        data() {
            return {
                occupations: [],
                selected: null
            };
        },
        props: {
            value: {
                default: null
            }
        },
        watch: {
            selected() {
                this.$emit('input', this.selected);
            }
        },
        async created() {
            let response = await this.axios.get('/api/occupations');
            this.occupations = response.data;
            this.selected = this.value;
        }
    }
</script>

<style lang="scss" scoped>
    @import '~selectize/dist/css/selectize.default.css';
</style>