<template>
  <div className="menu-item-toggle" v-if="hasChildren(menu)" @click="emitShowSubmenu">
    <div className="arrow-1"></div>
    <div className="arrow-2"></div>
  </div>
  <a v-bind:href="getUrlPath(menu.url_path)" className="has-child">{{ menu.name }}</a>
  <Submenu v-if="hasChildren(menu)" :menu="menu" :submenus="menu.children"/>
</template>
<script>
  import Submenu from './Submenu.vue';
  export default {
    components: {
      Submenu
    },
    emits: ['showSubmenu'],
    props: {
      menu: Object,
      show: Boolean
    },
    methods: {
      emitShowSubmenu(e) {
        this.$emit('showSubmenu', this.menu);
      },
      hasChildren(menu) {
        if (menu.children != undefined && menu.children.length > 0) {
          return true;
        }
        return false;
      },
      getUrlPath(urlPath) {
        return '/catalog/' + urlPath;
      }
    }
  }</script>