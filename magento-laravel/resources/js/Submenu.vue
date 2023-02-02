<template>
  <ul :class="shouldShowSubmenu() ? 'menu menu-level-2 menu-display': 'menu menu-level-2'">
    <li v-for="(submenu, index) in submenus" className="menu-item" :key="index">
      <a v-bind:href="getUrlPath(submenu.url_path)">{{ submenu.name }}</a>
    </li>
  </ul>
</template>
<script>
  export default {
    props: {
      submenus: Array,
      menu: Object,
    },
    data() {
      // {'menu menu-level-2': hide, 'menu menu-level-2 menu-display': !hide}
      let showSubmenu = (this.menu.showSubmenu != undefined && this.menu.showSubmenu);
      return {
        showSubmenu
      };
    },
    methods: {
      shouldShowSubmenu() {
        return (this.menu.showSubmenu != undefined) && this.menu.showSubmenu;
      },
      getUrlPath(urlPath) {
        return '/catalog/' + urlPath;
      }
    }
  }
  </script>