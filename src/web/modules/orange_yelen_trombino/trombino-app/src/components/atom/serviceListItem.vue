<script setup lang="ts">
defineProps({
  name: {
    type: String,
    required: true
  },
  isChecked: {
    type: Boolean,
    default: false
  }
})
</script>
<template>
    <li
    class="service-list__item"
    :class="{'is-checked': isChecked}"
    :data-filter="name"
    >
        <span
        class="service-list__item__icon"
        data-svg="/src/assets/icons/icon-check.svg" width="30" height="30" viewbox="0 0 30 30" xmlns="http://www.w3.org/2000/svg" data-attributes-set="width,height,viewBox,xmlns" data-rendered="true">
            <svg width="30" height="30" viewBox="0 0 30 30" xmlns="http://www.w3.org/2000/svg">
                <path d="M27 7c0-1.105-.888-2-1.983-2-.524 0-1 .206-1.355.541L11.93 17l-3.865-4.319a1.968 1.968 0 0 0-2.692.104l-.793.8C4.22 13.949 4 14.449 4 15c0 .473.163.907.435 1.25l6.344 8a1.972 1.972 0 0 0 3.097 0l12.69-16A2.02 2.02 0 0 0 27 7" fill-rule="evenodd"></path>
            </svg>
        </span>
        {{ name }}
    </li>
</template>

<style lang="scss" scoped>
.service-list__item{
  cursor: pointer;
  margin: 2px;
  display: flex;
  align-items: center;
  border-radius: 15px !important;
  padding: 3px 22px 5px 22px !important;
  font-size: 16px;
  height: 30px !important;
  font-weight: bold;
  border: solid 2px #000;
  transition: background-color .15s,color .15s;
  flex-shrink: 0;
  .service-list__item__icon{
    position: relative;
    width: 0;
    height: 10px;
    opacity: 0;
    transition: opacity .15s;
  }
  &:not(.is-checked){
    .service-list__item__icon{
      display: none;
    }
  }
  &.is-checked,
  &:hover{
    background-color: #000;
    color: #fff;
    .service-list__item__icon{
      opacity: 1;
      svg{
        fill: #FFFFFF;
        width: 13px;
        height: 13px;
        position: absolute;
        top: 60%;
        right: 3px;
        transform: translateY(-50%);
      }
    }

  }
}
</style>
