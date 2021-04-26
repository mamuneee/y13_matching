<template>
    <div class="body" :style="{'--brand-color': '#fbb040'}">
        <top-bar></top-bar>
        <transition name="fade">
            <router-view></router-view>
        </transition>
    </div>
</template>

<style>
    .fade-enter-active, .fade-leave-active {
        transition: opacity .5s
    }
    .fade-enter, .fade-leave-active {
        opacity: 0
    }
</style>

<script>
    import TopBar from './components/TopBar';
    export default{
        components: {TopBar}
    }
</script>