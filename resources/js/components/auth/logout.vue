<template>

</template>


<script>
import { useAuthStore } from '../../store/auth';

export default {
    created(){
        useAuthStore().logout();
        Toast.fire({
            type: 'success',
            icon: 'success',
            title: 'Logout Successfully'
        })
        this.$router.push('/')
    }
}
</script>


<style>

</style>
