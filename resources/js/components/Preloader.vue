<template>
    <div v-if="isLoading" class="preloader">
        <div class="preloader-inner">
            <div class="preloader-icon">
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
</template>
  
<script lang="ts">
  import { defineComponent, ref, onMounted } from 'vue';
  
  export default defineComponent({
    name: 'Preloader',
    props: {
      isLoading: {
        type: Boolean,
        required: true,
      }
    },
    setup(props) {
      onMounted(() => {
        setTimeout(() => {
          const preloader = document.querySelector('.preloader') as HTMLElement;
          if (preloader) {
            setTimeout(() => preloader.style.display = 'none', 1000);
          }
        }, 200);
      });
  
      return {
        isLoading: props.isLoading,
      };
    },
  });
</script>

  