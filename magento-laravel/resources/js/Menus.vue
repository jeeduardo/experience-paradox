<template>
  <ul className="menu">
    <li className="menu-item" v-for="(menu, index) in categoryMenus" :key="index">
      <Menu :menu="menu" :key="index" @showSubmenu="showSubmenu" />
    </li>
  </ul>
</template>
<script>
  import Menu from './Menu.vue';
  export default {
    props: {
      categoryMenus: Array
    },
    components: {
      Menu
    },
    methods: {
      showSubmenu(menu) {
        if (menu.showSubmenu == undefined) {
          menu.showSubmenu = false;
        }
        menu.showSubmenu = !menu.showSubmenu;
      }
    }
  }
</script>