<template id="items">
    <li class="item">
      <button :class="className">
        {{ task.title }}
      </button>
      <button>
        <i class="fa fa-times"></i>
      </button>
    </li>
</template>