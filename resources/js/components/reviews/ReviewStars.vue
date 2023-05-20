<template>
    <div class="d-flex">
        <i class="fa-solid fa-star" v-for="star in fullStars" :key="'full' + star" @click="$emit('reviewChanged' , star)"></i>
        <i class="fa-solid fa-star-half-stroke" v-if="halfStars"></i>
        <i class="fa-regular fa-star" v-for="star in emptyStars" :key="'empty' + star"  @click="$emit('reviewChanged' , fullStars + star)"></i>
    </div>
</template>
<script>
export default{
    props:{
        rating : Number
    },
    computed:{
        fullStars(){
            return Math.round(this.rating);
        },
        halfStars(){
            const fraction  = Math.round((Number(this.rating).toFixed(2) - Math.floor(this.rating)) * 100);
            return fraction > 0 && fraction < 50;
        },
        emptyStars(){
            return 5 - Math.ceil(this.rating);
        }
    }
}
</script>