<script setup lang="ts">

</script>

<template>
<div class="chat-window">
  <header>Administrateur</header>
  <div class="chat-content"></div>
  <footer>
    <div class="message-zone">
      <textarea class="form-control resize-none" rows="1"></textarea>
    </div>
  </footer>
</div>
</template>

<style scoped>
.chat-window{
  height: 0;
  transition: all ease-in .5s;
  &.is-open{
    height: 350px;
    transition: all ease-in .5s;
    width: 350px;
    background-color: #FFF;
    border: 1px solid #ddd;
    position: relative;
    right: 10%;
    .chat-content, footer{
      padding: 1rem;
    }

    footer{
      padding: 1rem;
      position: absolute;
      width: 100%;
      bottom: -.5rem;

      .message-zone{
        textarea{
          padding-left: 2rem;
          padding-right: 2rem;
          resize: none;
        }
      }
    }
  }
}
</style>
